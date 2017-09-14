<?php
///////////////
//newmtlreg.php
///////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;
//20150911 UPD STA
//$targetrep = TARGETREP;
$targetrep = MYSQL_DBNAME;
//20150911 UPD END

//set repurl.
$repurl = REPURL;

//connect mysql.
mysqlconnect();

foreach ($_POST as $key => $val){
	if($key == "hdndata"){
		$mtls = $_POST["hdndata"]["mtls"];
		$lenduser = $_POST["hdndata"]["lenduser"];
		$reason = $_POST["hdndata"]["reason"];
		$contact = $_POST["hdndata"]["contact"];
		$trbnum = $_POST["hdndata"]["trbnum"];
	}else{
//20120403 「[」,「]」,「.」への対応　tanaka
//		$needle = strrpos($key,"_");
//		$key = substr_replace($key,".",$needle,1);
		$key=str_replace("@",".",$key);
		$key=str_replace("!","[",$key);
		$key=str_replace("?","]",$key);
		//20160219 ADD STA 半角スペースを含むユニークファイル名の登録処理追加
		$key=str_replace("~"," ",$key);
		//20160219 ADD END 半角スペースを含むユニークファイル名の登録処理追加
		//20160126 ADD STA パス登録画面（newmtl.php）で資材格納パスに入力された「\」を「\\」に置換し、DB登録する修正
		$val=str_replace("\\","\\\\",$val);
		//20160126 ADD END パス登録画面（newmtl.php）で資材格納パスに入力された「\」を「\\」に置換し、DB登録する修正
		new_mtl($key,$val);
		$str .= "\t資材 (資材名=$key path=$val) を新規に登録しました。<br />\n";
		//logging.
		$logmsg = "reg new mtl  mtlname = $key path = $val";
		set_log($logmsg,'admin');
	}
}
$update = date("Y/m/d H:i");
$schedule = "-";
$phase = "新規管理資材申請";
//20160223 UPD STA 「新規管理資材登録」の「申請理由」がチケットに表示されない現象の修正
//$query = "INSERT INTO lendmsg (`trbnum` ,`lenduser` ,`phase` ,`mtls` ,`reason` ,`contact` ,`schedule` ,`update`)VALUES ('$trbnum', '$lenduser', '$phase', '$mtls', '$reason', '$contact', '$schedule', '$update')";
$query = "INSERT INTO lendmsg (`trbnum` ,`lenduser` ,`phase` ,`mtls` ,`remarks` ,`contact` ,`schedule` ,`update`)VALUES ('$trbnum', '$lenduser', '$phase', '$mtls', '$reason', '$contact', '$schedule', '$update')";
//20160223 UPD END 「新規管理資材登録」の「申請理由」がチケットに表示されない現象の修正
$result = mysql_query($query);
if(mysql_error()){
	$errlog = mysql_error();
	header("Location: err.php?errlog=$errlog&errnum=003");
	exit;
}
$query = 'insert into table_name ...';
$result = mysql_query($query);
$lendmsgid = mysql_insert_id();
//20150831 DEL STA
//set_svninfo($lendmsgid,$repurl); //capture svninfo to mysql.
//20150831 DEL END
$str .= "\t\t<div class=\"info_sc\">新規管理資材申請を受け付けました。</div>";

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
	<div class="title">新規管理資材登録.</div>
	<div class="article">
$str
		<div class="button"><a href="/$mlmname/admin.php">戻る</a></div>
	</div>
</div>
</body>
</html>
DOC_END;
?>
