<?php
    require 'config.php';

    /* connect to db */
    mysql_pconnect($db_host, $db_user, $db_pass) or die();
    mysql_select_db($db_name) or die();
    
    mysql_query("SET NAMES 'utf8'"); 
    mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
    mysql_query("SET CHARACTER_SET_RESULTS=utf8"); 
?>
