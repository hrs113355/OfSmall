<?php 
    function print_arrow(){
	echo '<img src="images/arrow.jpg" style="width:30px;" />';
    }
?>

<html>
    <head>
    <meta charset="utf-8">
    <title> OfSmall - 小的 1.0 [首頁] </title>
    <link rel="stylesheet" type="text/css" href="js/jquery-autocomplete/jquery.autocomplete.css">
    </head>

<body onload="refresh_list()">
<?php $page_name = ' - 關鍵字/回應對應表'; ?>
<?php include 'header.php'; ?>
<script type="text/javascript" src="js/jquery-autocomplete/jquery.autocomplete.js"></script>
<p>請建立<b>『小的機器人』</b>對於『關鍵字』和『回應』的連結 (分別輸入『關鍵字』和相對應的『回應』，會自動帶出系統現有的辭彙,輸入完畢按 Enter): <br>
<span style="font-weight: bold; color: red">注意: 請不要加入具有針對性的關鍵字，否則會考慮封 IP 或直接永久關閉編輯功能</span><br>
關鍵字：<input type="text" size="145" id="origin_input-box" maxlength="140"><span id="origin_rest_length"></span><br />
回應：<input type="text" size="145" id="reply_input-box" maxlength="140"><span id="reply_rest_length"></span>

<hr>
目前資料庫中的關鍵字<?php print_arrow(); ?>回應：
<span id="list"></span>
</p>

<script type="text/javascript">
setTimeout("refresh_list();", 15000);
reply_update_limit();
origin_update_limit();
function origin_update_limit()
{
    $("#origin_rest_length").html((140 - $("#origin_input-box").val().length));
}
function reply_update_limit()
{
    $("#reply_rest_length").html((140 - $("#reply_input-box").val().length));
}
function refresh_list()
{
    $.ajax({
	url: 'ajax_link.php',
	    error: function(xhr) {
	    },
		success: function(response) {
		    $('#list').html(response);
		}
    });
    setTimeout("refresh_list();", 15000);
}
function send_content(event)
{
    if (event.keyCode != '13')
	return true;

    if ($("#origin_input-box").val().length == 0 || $("#reply_input-box").val().length == 0)
	return true;
    
    $.ajax({
	url: 'ajax_send_link.php',
	    data: {origin: $("#origin_input-box").val(), reply:  $("#reply_input-box").val()},
	    error: function(xhr) {
		alert('Ajax request 發生錯誤');
		$(e.target).attr('disabled', false);
	    },
		success: function(response) {
		    refresh_list();
		    $("#origin_input-box").val("");
		    $("#reply_input-box").val("");
		}
    });
}

$("#reply_input-box").keydown(reply_update_limit).keyup(reply_update_limit);
$("#reply_input-box").keydown(send_content);
$("#origin_input-box").keydown(origin_update_limit).keyup(origin_update_limit);
$("#origin_input-box").keydown(send_content);

$("#reply_input-box").autocomplete('ajax_auto_reply.php');
$("#origin_input-box").autocomplete('ajax_auto_origin.php');
</script>
</body>
</html>
