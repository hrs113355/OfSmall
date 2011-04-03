#!/usr/bin/php5
<?php
	require('../config.php');
	require('php-plurk-api/plurk_api.php');
	
	$plurk = new plurk_api();
	$plurk->login($plurk_api_key, $plurk_username, $plurk_password);
	
	echo "\n\n ----- get plurks ----- \n";
	print_r($plurk->get_plurks(NULL, 20, NULL, NULL, NULL));
	
	// echo "\n\n ----- get someone's plurk ----- \n";
	// print_r($plurk->get_plurk(123));
	
	// echo "\n\n ----- get unread plurks ----- \n";
	// print_r($plurk->get_unread_plurks());
	
	// echo "\n\n ----- mark plurk as read ----- \n";
	// $plurk->mark_plurk_as_read(array(123,456,789));
	
	// echo "\n\n ----- add plurk ----- \n";
	// $plurk->add_plurk('en', 'says', 'Hello World');
?>
