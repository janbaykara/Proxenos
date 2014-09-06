<?
// Load database class
require_once('php/mysql.php');
$db = new mysql();

// Collate statistics
$fields[title] 		= urldecode($_GET['ttl']);
$fields[url] 		= urldecode($_GET['url']);
$fields[columns] 	= urldecode($_GET['col']);
$fields[colour] 	= urldecode($_GET['clr']);
$fields[source] 	= urldecode($_GET['src']);

// Statistics to database
$db->insert("statistics",$fields);

// Now direct on to $url
header("Location: ".$fields[url]);	
?>