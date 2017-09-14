<?php
/////////////////
//newlendmsg.php
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

//20160218 UPD STA 新規貸出申請時の登録処理見直し
////get POST lendmsg.
//$lenduser = $_POST["lenduser"];
//$contact = $_POST["contact"];
//$schedule = $_POST["schedule"];
//$trbnum = $_POST["trbnum"];
//$mtls = $_POST["mtls"];
//$reason = $_POST["reason"];
//
////POST args and mtls check.
//$chk_newlendmsg = chk_newlendmsg($lenduser,$contact,$schedule,$trbnum,$mtls,$reason);
//$chk_mtls = chk_mtls($mtls);
//if(!$chk_newlendmsg && !$chk_mtls){
//	//new_lendmsg.
//	$lendmsgid = new_lendmsg($trbnum,$lenduser,$mtls,$reason,$contact,$schedule);
//	//rdr summary.
//	$smystr = rdr_summary($lendmsgid,$trbnum,$lenduser,$mtls,$reason,$contact,$schedule,'申請後確認してください。');
//	$rslstr = "\t<div class=\"info_sc\">貸出申請を受付ました。</div>";
//	//logging.
//	$logmsg = "create new lendmsg  lendmsgid = $lendmsgid";
//	set_log($logmsg,'newlendmsg');
//}else if(!$chk_newlendmsg){
//	$chkstr = $chk_mtls;
//	//rdr summary.
//	$smystr = rdr_summary('-',$trbnum,$lenduser,$mtls,$reason,$contact,$schedule,'-');
//	$rslstr = "\t<div class=\"info_err\">上記理由により、貸出申請を棄却します。再度、入力項目をご確認ください。</div>";
//	//logging.
//	$logmsg = "create new lendmsg failed.";
//	set_log($logmsg,'newlendmsg');
//}else{
//	$chkstr = $chk_newlendmsg;
//	//rdr summary.
//	$smystr = rdr_summary('-',$trbnum,$lenduser,$mtls,$reason,$contact,$schedule,'-');
//	$rslstr = "\t<div class=\"info_err\">上記理由により、貸出申請を棄却します。再度、入力項目をご確認ください。</div>";
//	//logging.
//	$logmsg = "create new lendmsg failed.";
//	set_log($logmsg,'newlendmsg');
//}

//get POST lendmsg.
$mtls = $_POST["mtls"]; //資材
$mtls = trim($mtls);

//POST args and mtls check.
$chk_newlendmsg = chk_newlendmsg($mtls);
$chk_mtls = chk_mtls($mtls);
if(!$chk_newlendmsg && !$chk_mtls){
	//new_lendmsg.
	$lendmsgid = new_lendmsg($mtls);
	//rdr summary.
	$smystr = new_lendmsg_inf($lendmsgid,$mtls);
	$rslstr = "\t<div class=\"info_sc\">資材のバッティングはありません。</br>各項目に入力し[新規申請]ボタンを押下すると、上記内容で貸出申請します。</div>";
	//logging.
	$logmsg = "create new lendmsg  lendmsgid = $lendmsgid";
	set_log($logmsg,'newlendmsg');

}else if(!$chk_newlendmsg){
	$chkstr = $chk_mtls;
	//rdr summary.
	$smystr = rdr_summary('-',$trbnum,$lenduser,$mtls,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks);
	$rslstr = "\t<div class=\"info_err\">上記理由により、貸出申請を棄却します。</br>ブラウザの[戻る]ボタンまたは[BackSpace]キーを押下し、再度、入力項目をご確認ください。</div>";
	//logging.
	$logmsg = "create new lendmsg failed.";
	set_log($logmsg,'newlendmsg');
}else{
	$chkstr = $chk_newlendmsg;
	//rdr summary.
	$smystr = rdr_summary('-',$trbnum,$lenduser,$mtls,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks);
	$rslstr = "\t<div class=\"info_err\">上記理由により、貸出申請を棄却します。</br>ブラウザの[戻る]ボタンまたは[BackSpace]キーを押下し、再度、入力項目をご確認ください。</div>";
	//logging.
	$logmsg = "create new lendmsg failed.";
	set_log($logmsg,'newlendmsg');
}
//20160218 UPD END 新規貸出申請時の登録処理見直し

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
<!--20160212 ADD STA 貸出中資材名の隣に申請書ポップアップ表示の追加-->
<!-- load shadowbox -->
<link rel="stylesheet" type="text/css" href="js/shadowbox/shadowbox.css">
<script type="text/javascript" src="js/shadowbox/shadowbox.js"></script>
<!-- load shadowbox config -->
<script type="text/javascript">
Shadowbox.init({
    language: 'en',
    players:  ['img', 'html', 'iframe']
});
</script>
<!--20160212 ADD END 貸出中資材名の隣に申請書ポップアップ表示の追加-->
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
<!--20160218 DEL STA 新規貸出申請時の登録処理見直し-->
<!--	<div class="button"><a href="/$mlmname/">戻る</a></div> -->
<!--20160218 DEL END 新規貸出申請時の登録処理見直し-->
</div>
</body>
</html>
DOC_END;
?>
