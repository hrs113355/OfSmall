#!/usr/bin/php5
<?php
	require('../config.php');
	require('php-plurk-api/plurk_api.php');

	$plurk = new plurk_api();
	$plurk->login($plurk_api_key, $plurk_username, $plurk_password);

	$today = array(date("D M j G:i:s T Y"), date("F j, Y, g:i a"), date("j, n, Y"), date("H:i:s"));
	$r = rand(0, count($today) - 1);

	$words = array('小的向大大們請安(worship)', '祝各位大大有美好的一天(worship)', '小的只能說(worship)');
	$r2 = rand(0, count($words) - 1);
	$plurk->add_plurk('en', 'says', '大大好，' . $words[$r2].' 現在的時間是'.$today[$r]);
?>
