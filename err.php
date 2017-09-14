<?php
//////////
//err.php
//////////

//includes.
include_once "conf.php";

//set mlmname.
$mlmname = MLMNAME;

//connect mysql.
$link = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PW);
mysql_select_db(MYSQL_DBNAME,$link);
mysql_query('set names utf8');

//get errlog and errid.
$errlog = $_GET["errlog"];
$errnum = $_GET["errnum"];
$errlog = htmlspecialchars($errlog,ENT_QUOTES);
$errnum = htmlspecialchars($errnum,ENT_QUOTES);

//write errlog.
$errdate = date("Y/m/d H:i");
$query = "INSERT INTO err (`errnum` ,`errlog` ,`errdate`)VALUES ('$errnum', '$errlog', '$errdate')";
$result = mysql_query($query);

//////////////////////
//HTML render start.
//////////////////////
print <<< DOC_END
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MLMS</title>
<!-- load main css -->
<link rel="stylesheet" type="text/css" href="css/inline.css">
<style type="text/css">
	body, .title { background: url(images/bg_admin.png) };
</style>
<body>
<div class="item">
	<div class="title">SYSTEM ERR.</div>
	<div class="article">
		[ERR] mysqlのエラーが発生しました。<br /><br /><br />
		ERRID: $errnum <br /><br />
		ERRMSG: $errlog <br /><br /><br />
		恐れ入りますが、本ページのERRIDおよび、ERRMSGをメモいただきSVN構成管理窓口にご連絡願ください。<br /><br />
	</div>
</div>
</body>
</html>
DOC_END;
?>
