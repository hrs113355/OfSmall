#!/usr/bin/php5
<?php
	require('../config.php');
	require('php-plurk-api/plurk_api.php');
	require('../mysql_connect.php');

	$plurk = new plurk_api();
	$plurk->login($plurk_api_key, $plurk_username, $plurk_password);

	$all_origins = array();
	$default_origins = array();

	origins_init();

	echo "\n\n ----- get plurks ----- \n";
	$ret =  $plurk->get_plurks(NULL, 20, NULL, NULL, NULL);

	foreach ($ret->plurks as $p)
	{
	    if (!(isRepeat($p->plurk_id)) && !($p->no_comments))
	    {
		print $p->plurk_id . "\n";
		print $p->content_raw . "\n";
		print $p->response_count . "\n";
		print $p->no_comments . "\n";
		print ($posted = date('Y-m-d\TH:i:s', strtotime($p->posted)))."\n\n";
		$reply = getReply($p->content_raw, $p->response_count);

		print ($reply);

		$plurk->add_response($p->plurk_id, $reply, 'says');
		logThis($p->plurk_id, $p->content_raw, $reply, $p->response_count, $p->no_comments, $posted);
	    }
	}

	function isRepeat($plurkid)
	{
	  $sql = "SELECT `id` FROM `ofsmall_log` where `plurk_id`=$plurkid";
          $result = mysql_query($sql);
	  if ($record = mysql_fetch_array($result, MYSQL_NUM))
	      return true;
	  else
	      return false;
	}

	function origins_init()
	{
	    global $all_origins;
	    global $default_origins;

	    $all_origins = array();
	    $default_origins = array();
	    
	    $sql ="SELECT `ofsmall_origin`.text, `ofsmall_reply`.text FROM `ofsmall_link` inner join `ofsmall_origin` on `ofsmall_link`.origin_id = `ofsmall_origin`.id inner join `ofsmall_reply` on `ofsmall_link`.reply_id = `ofsmall_reply`.id where `ofsmall_origin`.text != '<預設值>'";
	    $result = mysql_query($sql);
	    while ($record = mysql_fetch_array($result, MYSQL_NUM))
	    {
		$r = '';
		$r->origin = $record[0];
		$r->reply = $record[1];
		array_push($all_origins, $r);
	    }
	    $sql ="SELECT `ofsmall_origin`.text, `ofsmall_reply`.text FROM `ofsmall_link` inner join `ofsmall_origin` on `ofsmall_link`.origin_id = `ofsmall_origin`.id inner join `ofsmall_reply` on `ofsmall_link`.reply_id = `ofsmall_reply`.id where `ofsmall_origin`.text = '<預設值>'";	
	    $result = mysql_query($sql);
	    while ($record = mysql_fetch_array($result, MYSQL_NUM))
	    {
		$r = '';
		$r->origin = $record[0];
		$r->reply = $record[1];
		array_push($default_origins, $r);
	    }
	}


	function getReply($origin, $count)
	{
	    global $all_origins;
	    global $default_origins;

	    if ($count == 4)
		return '小的誠惶誠恐地來搶大大的五樓了(worship)';

	// TODO: get Youtube content
	//    if ($origins like '想聽' $origins like '點播')
	//	return Youtube($origin);

	    $reply_queue = array();
	    foreach ($all_origins as $o)
	    {
		if (strpos($origin, $o->origin) !== false)
		    array_push($reply_queue, $o);
	    }

	    if (count($reply_queue) == 0)
	    {
		$reply_queue = $default_origins;
	    }
	    $random = rand(0, count($reply_queue) - 1);
	    if ($random >= 0)
		return $reply_queue[$random]->reply;
	    return false;
	}

	function logThis($plurk_id, $content_raw, $reply, $response_count, $no_comments, $posted)
	{
	    $content_raw = mysql_escape_string($content_raw);
	    $reply = mysql_escape_string($reply);

	    $sql = "INSERT INTO `ofsmall_log` (`plurk_id`, `content_raw`, `reply`, `response_count`, `no_comments`, `posted`) VALUES ($plurk_id, '$content_raw', '$reply', $response_count, $no_comments, '$posted')";
	    mysql_query($sql);
	}

    // TODO: use offset
    /*
	function getOffset()
	{
	  $sql = "SELECT `posted` FROM `ofsmall_log` order by id desc limit 1";
          $result = mysql_query($sql);
	  if ($record = mysql_fetch_array($result, MYSQL_NUM))
	      return $record[0];
	  else
	      return NULL;
	}
     */

?>
