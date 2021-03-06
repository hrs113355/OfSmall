<html>
    <head>
    <meta charset="utf-8">
    <title> OfSmall - 小的 1.0 [首頁] </title>
    </head>

<body onload="refresh_list()">
<?php $page_name = ' - 關鍵字列表'; ?>
<?php include 'header.php'; ?>


<p>請加上<b>『小的機器人』</b>遇到哪些關鍵字應該有反應 (打完直接按 Enter,上限 140 字): <br>
    <input type="text" size="150" id="input-box" maxlength="140">
    <span id="rest_length"></span>
<hr>
目前資料庫中的關鍵字：
<span id="list"></span>
</p>

<script type="text/javascript">
setTimeout("refresh_list();", 15000);
update_limit();
function update_limit()
{
    $("#rest_length").html((140 - $("#input-box").val().length));
}
function refresh_list()
{
    $.ajax({
	url: 'ajax_origin.php',
	    error: function(xhr) {
	    },
		success: function(response) {
		    $('#list').html(response);
		}
    });
}
function send_content(event)
{
    if (event.keyCode != '13')
	return true;

    $.ajax({
	url: 'ajax_send_origin.php',
	    data: {content: $("#input-box").val()},
	    error: function(xhr) {
		alert('Ajax request 發生錯誤');
		$(e.target).attr('disabled', false);
	    },
		success: function(response) {
		    refresh_list();
		    $("#input-box").val("");
		}
    });
}

$("#input-box").keydown(update_limit).keyup(update_limit);
$("#input-box").keydown(send_content);

</script>
</body>
</html>
