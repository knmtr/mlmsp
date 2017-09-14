<?php
/////////////
//dbinit.php
/////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname.
$mlmname = MLMNAME;

//connect mysql.
mysqlconnect();

//get chk flg.
$chk = $_GET["chk"];

if($chk == 1){
//init tables.
$query = "TRUNCATE TABLE `mtl`";
$result = mysql_query($query);

$query = "TRUNCATE TABLE `lendmsg`";
$result = mysql_query($query);

$query = "TRUNCATE TABLE `infomsg`";
$result = mysql_query($query);
$query = "INSERT INTO infomsg (`infomsg`)VALUES ('mlm system DB init.')";
$result = mysql_query($query);

$query = "TRUNCATE TABLE `err`";
$result = mysql_query($query);

$query = "TRUNCATE TABLE `log`";
$result = mysql_query($query);

//reg mtl table.
$strs = file('mtlfind.txt');
foreach($strs as $str){
	$str = substr($str,2);
	$str_delims = split("/",$str);
	$cnt = count($str_delims);
	$cnt--;
	$mtlname = $str_delims[$cnt];
	$path = str_replace($mtlname,' ',$str);
	$path = trim($path);
	$mtlname = trim($mtlname);
	$query = "INSERT INTO mtl (`mtlname` ,`LENDMSGID` ,`path`)VALUES ('$mtlname', '0', '$path')";
	$result = mysql_query($query);
}
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
<!-- load sortable -->
<script src="js/sorttable.js" type="text/javascript"></script>
</head>
<body>
<div class="item">
	<div class="title">[確認] データベースの初期化.</div>
	<div class="article">
		貸出システムのdbを初期化し、mtlfind.txt から新規にシステムを構成しました。<br /><br />
	</div>
</div>
</body>
</html>
DOC_END;
}else{
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
<!-- load sortable -->
<script src="js/sorttable.js" type="text/javascript"></script>
</head>
<body>
<div class="item">
	<div class="title">[確認] データベースの初期化.</div>
	<div class="article">
		貸出システムのdbを初期化し、mtlfind.txt(資材DIRのfind -type f 結果)から新規にシステムを構成します。<br /><br />
		本当によろしいですか? <br /><br />
		<div class="button"><a href="/$mlmname/dbinit.php?chk=1">はい</a></div>
		<div class="button">いいえ</div>
	</div>
</div>
</body>
</html>
DOC_END;
}
?>
