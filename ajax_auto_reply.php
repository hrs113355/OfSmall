<?php
$q = $_GET['q'];
if (!$q) return;

include 'mysql_connect.php';
$result = mysql_query('SELECT `text` FROM `ofsmall_reply`');
while ($record = mysql_fetch_array($result, MYSQL_NUM))
{
    if (strpos($record[0], $q) != false)
	echo "$record[0]\n";
}
?>
