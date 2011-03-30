<html>
<head>
<meta charset="utf-8">
</head>
<?php
	include 'mysql_connect.php';

	if ($_GET['content'] == '' or strlen($_GET['content']) <= 0)
	    die('no');

	$content = mysql_escape_string($_GET['content']);
	$ip = mysql_escape_string($_SERVER['REMOTE_ADDR']);

	$sql = 'insert into `ofsmall_origin` (`text`, `ip`) values (\''.$content.'\', \'' . $ip . '\')';
	mysql_query($sql);
?>
</html>
