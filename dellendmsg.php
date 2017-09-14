<?php
/////////////////
//dellendmsg.php
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

//get lendmsgid.
$lendmsgid = $_POST["lendmsgid"];

//check lendmsg exist.
$exist = chk_lendmsgexist($lendmsgid);

if($exist){
	//get mtls.
	$args = get_lendmsg($lendmsgid);
	$mtls = $args['mtls'];

	//release mtls.
	rls_mtls($mtls);

	//delete lendmsg.
	del_lendmsg($lendmsgid);
	
	//logging.
	$logmsg = "delete lendmsg and release mtls  lendmsgid = $lendmsgid";
	set_log($logmsg,'admin');
	
	$str = "\t貸出申請書 (id=$lendmsgid) を削除しました。<br / ><br / >\n\t資材のロックを解除しました。";
}else{
	$str = "\t[ERR] 貸出申請書 (id=$lendmsgid) は存在しません。";
	
	//logging.
	$logmsg = "delete lendmsg and release mtls failed";
	set_log($logmsg,'admin');
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
<link rel="stylesheet" type="text/css" href="css/default.css">
<style type="text/css">
	body, .title { background: url(images/bg_admin.png) };
</style>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/admin.php">admin login :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">貸出申請書の削除.</div>
	<div class="article">
$str
		<div class="button"><a href="/$mlmname/admin.php">戻る</a></div>
	</div>
</div>
</body>
</html>
DOC_END;
?>
