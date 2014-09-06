<?
require_once("php/config.php");

$opml_url = 'http://feedly.com/v3/opml?feedlyToken=AnUP2wV7ImEiOiJmZWVkbHkiLCJlIjoxNDAyOTM2OTYwMDA0LCJpIjoiM2E5NGFiZmMtMDg2OS00N2YwLTllN2MtODkyNjA4ZGQ1NTFjIiwicCI6NiwidCI6MSwidiI6InByb2R1Y3Rpb24iLCJ4IjoicHJvIn0%3Afeedly';
$parser = new IAM_OPML_Parser();
$links = $parser->getFeeds($opml_url);
?>
<?=SCAFFOLD_HEAD?>
<h1><?=TTL_ORGANISE?></h1>
<?
print "<ul>";
foreach($links as $feed)
{
	$link = parse_url($feed['feeds'],PHP_URL_SCHEME).'://'.parse_url($feed['feeds'], PHP_URL_HOST);
	if(trim(parse_url($feed['feeds'], PHP_URL_HOST))=='') {
		$cat = $feed[names];
		$proxenos->addCategory($cat);
		$cat = $proxenos->getCategoryByName($cat);
	}
	
	if($cat != $feed[names]) {
		print "<li><!--[{$cat}]--> <a href=\"$link\">{$feed[names]}</a>";
		
		$fields = array($feed[names],$feed[feeds],$cat[id],$feed[type]);
		$proxenos->addFeed($fields);
		print "</li>";
	}
}
print "</ul>";
?>
<?=SCAFFOLD_FOOT?>