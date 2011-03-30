<html>
<head>
<meta charset="utf-8">
</head>
<?php
	  include 'mysql_connect.php';
          $result = mysql_query('SELECT `text`, `ip` FROM `ofsmall_reply` order by id desc');
          while ($record = mysql_fetch_array($result, MYSQL_NUM))
	  {
	      echo "<li> " . htmlspecialchars($record[0]);
	      if ($record[1] != "")
		echo "<span style='color: gray'><em>-- from ".htmlspecialchars($record[1])." </em> </span>";
	      echo "</li>\n";
	  }
?>
</html>
