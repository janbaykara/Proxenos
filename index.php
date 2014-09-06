<? 
require_once("php/config.php"); ?>
<?=SCAFFOLD_HEAD?>

<h1><? echo TTL_HOME; ?></h1>
<?


// Choose category
$categoryName = "Satire + Humour";
echo "<a class='heading-category'><h2>{$categoryName}</h2></a>";
$category = $proxenos->getCategoryByName($categoryName);
$feeds = $proxenos->selectFeeds("category = ".$category['id']);
$feeds = array_slice($feeds,5);

foreach($feeds as $feed) {
  $proxenos->printFeed($feed['url'],5);
}

/*
$categories = $proxenos->getCategories();

foreach($categories as $category) {
  // Choose category
  $categoryName = $category['name'];
  echo "<a class='heading-category'><h2>{$categoryName}</h2></a>";
  $category = $proxenos->getCategoryByName($categoryName);
  $feeds = $proxenos->selectFeeds("category = ".$category['id']);

  foreach($feeds as $feed) {
    $proxenos->printFeed($feed['url']);
  }
}
*/

/*
  Process:
  
  getCategories();
  each -> selectFeeds();
          each -> getFeedEntries();
                  each -> printEntries();
*/

/*
$categories = $proxenos->getCategories();

foreach($categories as $category) {
    echo "<a class='heading-category'><h2>{$category['name']}</h2></a>";
  
    $categoryFeeds = $proxenos->selectFeeds("CATEGORY = ".$category['id']);
  
    // For each feed, get entries
    foreach($categoryFeeds as $categoryFeed) {
        $catFeedEntries = $proxenos->getFeedEntries($categoryFeed['url']);
    }
  
    foreach($catFeedEntries as $feed) {
        $proxenos->printEntries($feed);
    }
}
*/

?>
<?=SCAFFOLD_FOOT?>