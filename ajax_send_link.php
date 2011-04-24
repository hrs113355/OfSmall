<html>
<head>
<meta charset="utf-8">
</head>
<?php
	include 'mysql_connect.php';

	$reply = mysql_escape_string($_GET['reply']);
	$origin = mysql_escape_string($_GET['origin']);
	$ip = mysql_escape_string($_SERVER['REMOTE_ADDR']);

	if (($reply == '' or strlen($reply) <= 0) or ($origin == '' or strlen($origin) <= 0))
	    die('no');

	$origin_id = '';
	$reply_id = '';

	$result = mysql_query("SELECT `id` FROM `ofsmall_origin` where `text`='$origin'");
        if ($record = mysql_fetch_array($result, MYSQL_NUM))
	    $origin_id = $record[0];
	else
	{
	    $sql = 'insert into `ofsmall_origin` (`text`, `ip`) values (\''.$origin.'\', \'' . $ip . '\')';
	    mysql_query($sql);

	    $result = mysql_query("SELECT `id` FROM `ofsmall_origin` where `text`='$origin'");
	    if ($record = mysql_fetch_array($result, MYSQL_NUM))
	    {
		$origin_id = $record[0];
	    }
	}

	$result = mysql_query("SELECT `id` FROM `ofsmall_reply` where `text`='$reply'");
        if ($record = mysql_fetch_array($result, MYSQL_NUM))
	    $reply_id = $record[0];
	else
	{
	    $sql = 'insert into `ofsmall_reply` (`text`, `ip`) values (\''.$reply.'\', \'' . $ip . '\')';
	    mysql_query($sql);

	    $result = mysql_query("SELECT `id` FROM `ofsmall_reply` where `text`='$reply'");
	    if ($record = mysql_fetch_array($result, MYSQL_NUM))
	    {
		$reply_id = $record[0];
	    }
	}

	$sql = "insert into `ofsmall_link` (`origin_id`, `reply_id`, `ip`) values ($origin_id, $reply_id, '$ip')";
	print $sql;
	mysql_query($sql);
?>
</html>
