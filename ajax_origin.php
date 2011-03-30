<html>
<head>
<meta charset="utf-8">
</head>
<?php
	  include 'mysql_connect.php';
          $result = mysql_query('SELECT `text`, `ip` FROM `ofsmall_origin` order by id desc');
          while ($record = mysql_fetch_array($result, MYSQL_NUM))
	  {
	      echo "<li> ";
	      if ($record[0] == "<預設值>")
		  echo "<span style='color: red; font-weight: bold;'>".htmlspecialchars($record[0])."</span>";
	      else
		  echo htmlspecialchars($record[0]);
	      if ($record[1] != "")
		echo "<span style='color: gray'><em>-- from ".htmlspecialchars($record[1])." </em> </span>";
	      echo "</li>\n";
	  }
?>
</html>
