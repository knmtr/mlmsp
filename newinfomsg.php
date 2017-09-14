<?php
/////////////////
//newinfomsg.php
/////////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;

//20150911 UPD STA
//$targetrep = TARGETREP;
$targetrep = MYSQL_DBNAME;
//20150911 UPD END

//connect mysql.
mysqlconnect();

//get infomsg.
$infomsg = $_POST["infomsg"];

//set infomsg.
set_infomsg($infomsg);

//logging.
$logmsg = "reg new info msg  msg = $infomsg";
set_log($logmsg,'admin');

//make str.
$str = "お知らせを書き換えました。";

//////////////////////
//HTML render start.
//////////////////////
print <<< DOC_END
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MLMS</title>
<!-- load main css -->
<link rel="stylesheet" type="text/css" href="css/default.css">
<style type="text/css">
	body, .title { background: url(images/bg_admin.png) };
</style>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/admin.php">admin login :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">お知らせ情報登録.</div>
	<div class="article">
$str
		<div class="button"><a href="/$mlmname/admin.php">戻る</a></div>
	</div>
</div>
</body>
</html>
DOC_END;
?>
