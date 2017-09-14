<?php
////////////////////
//newlendmsginf.php
////////////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;
$targetrep = MYSQL_DBNAME;

//connect mysql.
mysqlconnect();

//get pager property.
$msg_p = $_GET["msg_p"];
if(!$msg_p){
	$msg_p = 1;
}

//get htmlcodes.
$tbllendmsg = rdr_tbllendmsg(COLLIM,$msg_p);
$tbllendmsgpage = rdr_tbllendmsgpage(COLLIM,$msg_p);

//get POST lendmsg.
$lendmsgid = $_POST["lendmsgid"]; //申請番号
$lenduser = $_POST["lenduser"]; //申請者
$trbnum = $_POST["trbnum"]; //関連帳票番号
$cngnum = $_POST["cngnum"]; //変更管理番号
$subject = $_POST["subject"]; //件名
$mtlstype = $_POST["mtlstype"]; //改修資材分類
$mtls = $_POST["mtls"]; //資材
$sharingTrbnum = $_POST["sharingTrbnum"]; //相乗対象番号
$remarks = $_POST["remarks"]; //備考
//20160229 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
$remarks = str_replace("'","''",$remarks);
//20160229 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加

//POST args check.
$chk_newlendmsginf = chk_newlendmsginf($lenduser,$trbnum,$mtlstype);
if(!$chk_newlendmsginf){
	//upd_lendmsg.
	upd_newlendmsg($lendmsgid,$trbnum,$lenduser,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks);
	//rdr summary.
	$smystr = rdr_summary($lendmsgid,$trbnum,$lenduser,$mtls,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks);
	$rslstr = "\t<div class=\"info_sc\">貸出申請を受付ました。</br><a href=\"index.php\">＞＞topページへ戻る＜＜</a></div>";
	//logging.
	$logmsg = "update new lendmsg  lendmsgid = $lendmsgid";
	set_log($logmsg,'newlendmsginf');
}else{
	$chkstr = $chk_newlendmsginf;
	//rdr summary.
	$smystr = rdr_summary($lendmsgid,$trbnum,$lenduser,$mtls,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks);
	$rslstr = "\t<div class=\"info_err\">上記理由により、貸出申請を棄却します。</br>ブラウザの[戻る]ボタンまたは[BackSpace]キーを押下し、再度、入力項目をご確認ください。</div>";
	//logging.
	$logmsg = "create new lendmsg failed.";
	set_log($logmsg,'newlendmsginf');
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
	body, .title { background: url(images/bg.png) };
</style>
</head>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/">mlm :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">申請サマリ.</div>
	<div class="article">
$smystr
	</div>
</div>
<div class="item">
	<div class="title">システム受付結果.</div>
	<div class="article">
$chkstr
	</div>
$rslstr
</div>
</body>
</html>
DOC_END;
?>
