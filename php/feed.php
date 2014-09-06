<?
/** 
Most relevant news:
- https://news.google.com/news/feeds?ned=uk&topic=h&output=rss
- http://www.faroo.com/api?q=&start=1&length=10&l=en&src=news&f=xml
- https://news.google.com/
- http://newsmap.jp/
*/

/**
To be done:
- getFeeds
- getChannel
- getEntry
- printEntry (standardised display for Atom/RSS)
*/

/** RSS/ATOM REFERENCE
--------------------------------------------------
RSS 2.0			| Atom 1.0
----------------|----------------------------------
author			| author*
category		| category
channel			| feed
copyright		| rights
description		| subtitle
description*	| summary and/or content
generator		| generator
guid			| id*
image			| logo
item			| entry
lastBuildDate 	| 
  (in channel)	| updated*
link*			| link*
managingEditor	| author or contributor
pubDate			| published (subelement of entry)
title*			| title*
ttl	-			| 
*/

/*
function getFeeds($OPML_file) {
	$content = file_get_contents($OPML_file);  
    $x = new SimpleXmlElement($content);
	print_r($x);
	foreach($x->xpath('//outline/attributes[@type="xmlUrl"]') as $feed) {
		echo $feed;
		getFeed($feed);
	}
}
*/

class Feed {

	public $title;
	public $url;
	public $feed;
	
	function __construct($url = null) {
		$content = file_get_contents($url);  
		$this->feed = new SimpleXmlElement($content);
		
		// assign values to Feed->Properties
		$this->title = $this->feed->channel->title;
		$this->url = $this->feed->channel->link;
	}
	
	public function printEntries() {
		echo "<ul>";  
		
		$i = 0;
		$cells = null;
			
		foreach($this->feed->channel->item as $entry) {	
			if($cells == null) {
				$cells = genRandomBatch();
				echo "<div class='entry-row'>";
			}
			
			$colWidth = $cells[$i];
			$displayType = rand(1,10);
			
			if($displayType < 7) {
				/* Print block with description etc. */
				echo
				"<li class='entry col".$colWidth."'>
					<div class='entry-inner'>
						<div>
							<a class='title' href='".$entry->link."' title='".$entry->title."'>
								<h3>" . $entry->title ."</h3>
							</a>
							<div class='date'>".date('j G\0\0 M', $entry->pubDate)."</div>
							<div class='summary'>".$entry->description."</div>
						</div>";
			} elseif($colWidth < 3) {
				/* Print block as large title panel */
				$flashyBgColor = genPastel(); //Random colour
				$flashyTxtColor = "#FFF"; //Complementary colour of $flashyBgColor
				
				echo 
				"<li class='entry flashy col".$colWidth."'>
					<div class='entry-inner'>
						<div>
							<a class='title' style='background: ".$flashyBgColor."' href='".$entry->link."' title='".$entry->title."'>
								<h3 style='color: ".$flashyTxtColor."'>" . $entry->title ."</h3>
							</a>
						</div>";
			} else {
				/* Print block with description etc. */
				echo 
				"<li class='entry col".$colWidth."'>
					<div class='entry-inner'>
						<div>
							<a class='title' href='".$entry->link."' title='".$entry->title."'>
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
		
		echo "</ul>";  
	}
}
?>