#!/usr/bin/php5
<?php
	require('../config.php');
	require('php-plurk-api/plurk_api.php');
	require('../mysql_connect.php');

	$plurk = new plurk_api();
	$plurk->login($plurk_api_key, $plurk_username, $plurk_password);

	$all_origins = array();
	$default_origins = array();

while(1)
{
	origins_init();

	echo "\n\n ----- get plurks ----- \n";
	$ret =  $plurk->get_plurks(NULL, 10, NULL, NULL, NULL);

	foreach ($ret->plurks as $p)
	{
	    if (!(isRepeat($p->plurk_id)))
	    {
		print $p->plurk_id . "\n";
		print $p->content_raw . "\n";
		print $p->response_count . "\n";
		print $p->no_comments . "\n";
		print getResponseRate($p->owner_id)."\n";
		print ($posted = date('Y-m-d\TH:i:s', strtotime($p->posted)))."\n";
		$reply = getReply($p->content_raw, $p->response_count, $p->owner_id);

		if ($reply === false)
		{
			print "===> 回噗率設定 => skip\n";
			logThis($p->plurk_id, $p->content_raw, '(skip)', $p->response_count, $p->no_comments, $posted);
			continue;
		}
		else if ($reply == '')
		{
			print 'skip';
			logThis($p->plurk_id, $p->content_raw, '(skip)', $p->response_count, $p->no_comments, $posted);
			continue;
		}
		$reply = str_replace('(worship)', ' http://ppt.cc/GdjX#.jpg ', $reply);

		print "===> $reply\n\n";

		$plurk->add_response($p->plurk_id, $reply, 'says');
		logThis($p->plurk_id, $p->content_raw, $reply, $p->response_count, $p->no_comments, $posted);
	    }
	}
	sleep(20);
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


	function getReply($origin, $count, $user_id)
	{
	    global $all_origins;
	    global $default_origins;
	    $reply_queue = array();

	    if (($pos = mb_strpos($origin, '小的回噗率', 0, 'UTF-8')) !== false)
		return responseRate(mb_substr($origin, $pos + 5, 200, 'UTF-8'), $user_id);

	    if (rand(0, 99) >= getResponseRate($user_id))
		return false;
	    
	    if ($count == 4)
		return '小的誠惶誠恐地來搶大大的五樓了(worship)';
	    
	    if ($count == 1)
		return '樓上動作好快... 小的都沒搶到頭香 :(';

	    if (mb_strpos($origin, '想聽', 0, 'UTF-8') !== false)
	    {
		$parseY = preg_split('/想聽/', $origin);
		print ($parseY[count($parseY) - 1]);
		 $reply_queue = Youtube($parseY[count($parseY) - 1]);
	    }
	    else if ($pos = mb_strpos($origin, '點播', 0, 'UTF-8') !== false)
	    {
		$parseY = preg_split('/點播/', $origin);
		print ($parseY[count($parseY) - 1]);
		$reply_queue = Youtube($parseY[count($parseY) - 1]);
	    }
	    else
	    {
		foreach ($all_origins as $o)
		{
		    if (strpos($origin, $o->origin) !== false)
			array_push($reply_queue, $o);
		}

		if (count($reply_queue) == 0)
		{
		    $reply_queue = $default_origins;
		}
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

	function Youtube($q)
	{
	    $ret = array();

	    $q = urlencode($q);
	    print $q."\n";
	    $url = "http://gdata.youtube.com/feeds/api/videos?q=$q&max-results=2&v=2";

	    $fp = fopen($url, 'r');

	    $data = '';
	    while ($buf = fgets($fp, 1024))
		$data = $data.$buf;

	    preg_match_all("/<link rel='alternate' type='text\/html' href='(.*?)&amp;feature=youtube_gdata'\/>/", $data, $match);

	    $x = 0;
	    $adverb = array('畢恭畢敬地', '內牛滿面地', '誠惶誠恐地', '感激不盡地', '煞气地', '五體投地地', '心花怒放地');
	    foreach($match[1] as $m)
		if ($x++ != 0)
		{
		    print "==Youtube=> $m\n";
		    $o->reply = "小的". $adverb[rand(0, count($adverb) - 1)]."為大大獻上一曲: $m";
		    array_push($ret, $o);
		}
	    if (count($ret) == 0)
	    {
		    $o->reply = '大大抱歉, 小的無能為您找到想要聽的音樂(yay)';
		    array_push($ret, $o);
	    }else {
	    print_r($ret);
	    }

	    return $ret;
	}

	function responseRate($rate, $user_id)
	{
		$rate = intval($rate);
	//	print "=> $rate, $user_id\n";
		if ($rate < 0 || $rate > 100)
			return '大大輸入的數字有誤喔! 請再試試 (worship)';
		$sql = "INSERT INTO ofsmall_rate (rate, user_id) VALUES($rate, $user_id)";
		if (mysql_query($sql))
			return '好的!小的謹遵大大教誨!(code)已經將回噗率設定成'.$rate.'%';
	}

	function getResponseRate($user_id)
	{
		$sql = "SELECT rate FROM ofsmall_rate WHERE user_id=$user_id order by id desc";
		$rs = mysql_query($sql);
		if ($r = mysql_fetch_array($rs, MYSQL_NUM))
			return $r[0];
		else
			return 100;
	}
		

	function getOffset()
	{
	  $sql = "SELECT `posted` FROM `ofsmall_log` order by id desc limit 1";
          $result = mysql_query($sql);
	  if ($record = mysql_fetch_array($result, MYSQL_NUM))
	      return $record[0].'+00:00';
	  else
	      return NULL;
	}

?>
