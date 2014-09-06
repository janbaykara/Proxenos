<?
# Dependencies
require_once("php/mysql.php");
require_once("php/proxenos.php");
require_once('php/iam_opml_parser.php');
require_once("php/functions.php");
//require_once("php/feed.php");
//require_once("php/color.php");

# Global variables
$db = new mysql();
$proxenos = new Proxenos();

# * Global constants
define("TTL_HOME", "Today's News");
define("TTL_ORGANISE", "Organise");

# * Scaffold HTML
$SCAFFOLD_HEAD = <<<EOF
<!DOCTYPE html>
<html>
<head>
	<!-- Metadata -->
	<title>Proxenos</title>
	<!-- Stylesheets -->
	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet/less" type="text/css" href="css/main.less" />
	<!-- Dependencies -->
	<script src="js/less-1.5.0.min.js" type="text/javascript"></script>
	<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://www.appelsiini.net/download/jquery.jeditable.mini.js" type="text/javascript"></script>
	<script type="text/javascript">
		 $(document).ready(function() {
			 $('.edit').editable('http://ecitizentools.com/proxenos/index.php', { 
				 id   : 'feed',
				 name : 'feed',
				 indicator : 'Loading new feed...',
				 tooltip   : 'Click to input new feed url',
				 callback : function(value, settings) {
					 window.location.reload();
				}
			 });
		 });
	</script>-->
</head>
<body>
EOF;

$SCAFFOLD_FOOT = <<<EOF
<div class='clear'></div>
<footer>
<hr>
<center>Proxenos</center>
<br>
</footer>
</body>
</html>
EOF;

define("SCAFFOLD_HEAD", $SCAFFOLD_HEAD);
define("SCAFFOLD_FOOT", $SCAFFOLD_FOOT);

?>