<?
class Proxenos {

/* Categories */
	public function getCategories() {
		global $db;
		return $db->select(array(
			'table' => 'categories'
		));
	}
	
	public function getCategoryByID($id) {
		global $db;
		return $db->row(array(
			'table' => 'categories',
			'condition' => "id = '{$id}'"
		));
	}
	
	public function getCategoryByName($name) {
		global $db;
		return $db->row(array(
			'table' => 'categories',
			'condition' => "name = '{$name}'"
		));
	}
	
	public function addCategory($name) {
		global $db;
		$cat = $this->getCategoryByName($name);
		if($cat == null) {
			$fields['name'] = $name;
			$db->insert("categories",$fields);
			echo "Category added";
		} else {
			echo "<hr> Category [ID:{$cat['id']}] exists in db";
		}
	}
	
/* Feeds */

	public function selectFeeds($conditions = null) {
		global $db;
		
		if($conditions == null) {
			$feeds = $db->select(array(
				'table' => 'feeds'
			));
		} else {
			$feeds = $db->select(array(
				'table' => 'feeds',
				'condition' => $conditions
			));	
		}				
        
        return $feeds;
	}
	
	public function selectFeedByURL($url) {
		global $db;
		return $db->row(array(
			'table' => 'feeds',
			'condition' => "url = '{$url}'"
		));
	}
	
	public function addFeed($feed) {
		$fields[name] = $feed[0];
		$fields[url] = $feed[1];
		$fields[category] = $feed[2];
		$fields[type] = $feed[3];
		
		$fd = $this->selectFeedByURL($fields[url]);
		
		if($fd == null) {
			global $db;
			$db->insert("feeds",$fields);
			echo "Feed added";
		} else {
			echo "Feed [ID:".$fd['id']."] exists in db";
		}
	}
	
	public function getFeedEntries($source) {
        $content = file_get_contents($source);
        $feed = new SimpleXmlElement($content);
      
        foreach($feed->channel->item as $entry) {
            $entry->source = $feed->channel->title;
            $entries[] = $entry;
        }
        return $entries;
	}
	
	public function printFeed($url,$limit=10) {
		$entries = $this->getFeedEntries($url);
		$this->printEntries(array_slice($entries,$limit),$limit);
	}
	
	public function printEntries($entries,$limit = 10) {
		echo "<ul>";
		
		$i = 0;
        $xax = 0;
		$cells = null;
        while($xax < $limit) {
          foreach($entries as $entry) {
                if($cells == null) {
                    $cells = genRandomBatch(10,4,2);
                    echo "<div class='entry-row'>";
                }

                $colWidth = $cells[$i];
                $displayType = rand(1,10);
                $flashyBgColor = genPastel();

                $logLink = "http://ecitizentools.com/proxenos/log.php";
                $logLink .= "?url=".urlencode($entry->link);
                $logLink .= "&ttl=".urlencode($entry->title);
                $logLink .= "&col=".urlencode($colWidth);
                $logLink .= "&clr=".urlencode($flashyBgColor);
                $logLink .= "&src=".urlencode($entry->title);

                if($displayType < 7) {
                    // Print block with description etc.
                    echo
                    "<li class='entry col".$colWidth."'>
                        <div class='entry-inner'>
                            <div>
                                <span class='entry_source_feed'>{$entry->source}</span>
                                <a class='title' href='".$logLink."' title='".$entry->title."'>
                                    <h3>" . $entry->title ."</h3>
                                </a>
                                <div class='date'>".date('j G\0\0 M', $entry->pubDate)."</div>
                                <div class='summary'>".$entry->description."</div>
                            </div>";
                } elseif($colWidth < 5) {
                    echo 
                    "<li class='entry flashy col".$colWidth."'>
                        <div class='entry-inner'>
                            <div>
                                <a class='title' style='background: ".$flashyBgColor."' href='".$logLink."' title='".$entry->source."'>
                                    <span class='entry_source_feed' style='color: {$flashyBgColor} !important'>{$entry->title}</span>
                                    <h3>" . $entry->title ."</h3>
                                </a>
                            </div>";
                } else {
                    // Print block with description etc. (Repeat)
                    echo 
                    "<li class='entry col".$colWidth."'>
                        <div class='entry-inner'>
                            <div>
                                <span class='entry_source_feed'>{$entry->source}</span>
                                <a class='title' href='".$logLink."' title='".$entry->title."'>
                                    <h3>" . $entry->title ."</h3>
                                </a>
                                <div class='date'>".date('j G\0\0 M', $entry->pubDate)."</div>
                                <div class='summary'>".$entry->description."</div>
                            </div>";
                }

                echo "</div></li>";

                if(($i+1) == count($cells)) {
                    $i = 0;
                    $cells = null;
                    echo "<div class='clear'></div></div>";
                }
                else
                    $i++;
              }
          $xax++;
		}
		
		echo "</ul>";
	}
	
	/*
	public function removeFeed($feedID) {
		global $db;
		$db->d("feeds", $fields);
	}
	
	public function removeCategory($id) {
		global $db;
		$db->delete(array(
			"table" => "categories",
			"condition" => "id = {$id}"
		));
		// Also remove all feeds from category
		$db->update(array("feeds", "category = null", "category = {$id}");
	}
	
	public function editCategory($id, $name) {
		global $db;
		$db->update("categories", "name = ".$name, "id = {$id}");
	}
	*/
}
?>