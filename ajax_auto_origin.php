<?php
$q = $_GET['q'];
if (!$q) return;

include 'mysql_connect.php';
$result = mysql_query('SELECT `text` FROM `ofsmall_origin`');
while ($record = mysql_fetch_array($result, MYSQL_NUM))
{
    if (strpos($value, $q) != false)
	echo "$record[0]\n";
}
?>
