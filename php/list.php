<?php
require_once('iam_opml_parser.php');

$opml_url = 'http://feedly.com/v3/opml?feedlyToken=AQAAzYR7InAiOjEsImEiOiJmZWVkbHkiLCJpIjoiM2E5NGFiZmMtMDg2OS00N2YwLTllN2MtODkyNjA4ZGQ1NTFjIiwieCI6InN0YW5kYXJkIiwidCI6MSwidiI6InByb2R1Y3Rpb24iLCJlIjoxMzg3NjcwMDIwMDY4fQ%3Afeedly';
$parser = new IAM_OPML_Parser();
$links = $parser->getFeeds($opml_url);

// Start List
print "<ul>";
foreach($links as $feed)
{
	$link = parse_url($feed['feeds'],PHP_URL_SCHEME).'://'.parse_url($feed['feeds'], PHP_URL_HOST);
	if(trim(parse_url($feed['feeds'], PHP_URL_HOST))=='')
		$cat = $feed[names];
	
	if($cat != $feed[names]) {
		print "<li>[{$cat}] <a href=\"$link\">{$feed[names]}</a> <a href=\"{$feed[feeds]}\">RSS Feed</a></li>";
		
		//urls feeds type
	}
}
print "</ul>";
?>