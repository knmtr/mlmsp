<?php
/////////////
//delmtl.php
/////////////

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

//get mtlname.
$mtls = $_POST["mtls"];
//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
$mtls = str_replace("'","''",$mtls);
//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
$lenduser = $_POST["lenduser"];
$reason = $_POST["reason"];
//20160229 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
$reason = str_replace("'","''",$reason);
//20160229 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
$contact = $_POST["contact"];
$trbnum = $_POST["trbnum"];
$schedule = "-";

//20160217 ADD STA 管理資材の削除必須項目入力チェック
//管理資材の削除時のフォーム入力チェック
$chk_delmtl = chk_delmtl($lenduser,$trbnum,$mtls);
//20160217 ADD END 管理資材の削除必須項目入力チェック
//20160217 ADD STA 管理資材の削除必須項目入力チェック
if($chk_delmtl){//管理資材の削除必須項目入力チェックがエラーの場合、その内容を表示する。
	$str .= $chk_delmtl;
	$errflg ++;
}else{
//20160217 ADD END 管理資材の削除必須項目入力チェック
	$mtlnames = split("/", "$mtls");
	foreach ($mtlnames as $mtlname) {
		//20160205 ADD STA 削除資材名のtrim
		$mtlname = trim($mtlname);
		//20160205 ADD END 削除資材名のtrim
		if(chk_mtlexist($mtlname)){
			$chk_mtls[] = $mtlname;
		}else{
			$str .= "\t\t資材名=$mtlname は存在しません。<br />\n";
			$errflg ++;
		}
	}
}
if($errflg){
	$str .= "\t\t<div class=\"info_err\">上記理由により、資材削除申請を棄却します。再度、入力項目をご確認ください。</div>";
}else{
	foreach ($chk_mtls as $chk_mtlname) {
		del_mtl($chk_mtlname);
		//logging.
		$logmsg = "delete mtl  mtlname = $chk_mtlname";
		set_log($logmsg,'admin');
		$str .= "\t\t管理資材 (資材名=$chk_mtlname) を削除しました。<br />\n";
	}
	$str .= "\t\t<div class=\"info_sc\">削除申請を受け付けました。</div>";
	$update = date("Y/m/d H:i");
	$phase = "資材削除申請";
	//20160223 UPD STA 「管理資材の削除」の「申請理由」がチケットに表示されない現象の修正
	//$query = "INSERT INTO lendmsg (`trbnum` ,`lenduser` ,`phase` ,`mtls` ,`reason` ,`contact` ,`schedule` ,`update`)VALUES ('$trbnum', '$lenduser', '$phase', '$mtls', '$reason', '$contact', '$schedule', '$update')";
	$query = "INSERT INTO lendmsg (`trbnum` ,`lenduser` ,`phase` ,`mtls` ,`remarks` ,`contact` ,`schedule` ,`update`)VALUES ('$trbnum', '$lenduser', '$phase', '$mtls', '$reason', '$contact', '$schedule', '$update')";
	//20160223 UPD END 「管理資材の削除」の「申請理由」がチケットに表示されない現象の修正
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=003");
		exit;
	}
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
	<div class="logo"><a href = "/$mlmname/admin.php">admin login :: Rep = $targetrep:</a></div>
</div>
<div class="item">
	<div class="title">管理資材の削除.</div>
	<div class="article">
$str
		<div class="button"><a href="/$mlmname/admin.php">戻る</a></div>
	</div>
</div>
</body>
</html>
DOC_END;
?>
