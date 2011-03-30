<html>
<head>
<meta charset="utf-8">
</head>
<?php 
    function print_arrow(){
	echo '<img src="images/arrow.jpg" style="width:30px;" />';
    }
	  include 'mysql_connect.php';
          $result = mysql_query('SELECT `ofsmall_origin`.text, `ofsmall_reply`.text, `ofsmall_link`.ip FROM `ofsmall_link` inner join `ofsmall_origin` on `ofsmall_link`.origin_id = `ofsmall_origin`.id inner join `ofsmall_reply` on `ofsmall_link`.reply_id = `ofsmall_reply`.id order by `ofsmall_link`.id desc');
          while ($record = mysql_fetch_array($result, MYSQL_NUM))
	  {
	      echo "<li>";
	      if ($record[0] == "<預設值>")
		  echo "<span style='color: red; font-weight: bold;'>$record[0]</span>";
	      else
		  echo $record[0];
	      print_arrow();
	      echo $record[1];
	      if ($record[2] != "")
		echo "<span style='color: gray'><em>-- from $record[2] </em> </span>";
	      echo "</li>\n";
	  }
?>
</html>
