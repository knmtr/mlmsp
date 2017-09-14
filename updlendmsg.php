<?php
/////////////////
//updlendmsg.php
/////////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;
$targetrep = MYSQL_DBNAME;

//connect mysql.
mysqlconnect();

//get POST lendmsg.
$lendmsgid = $_POST["lendmsgid"]; //申請番号
$lenduser = $_POST["lenduser"]; //申請者
$contact = $_POST['contact']; //連絡先
$trbnum = $_POST["trbnum"]; //関連帳票番号
$cngnum = $_POST["cngnum"]; //変更管理番号
$subject = $_POST["subject"]; //件名
$mtlstype = $_POST["mtlstype"]; //改修資材分類
$mtls = $_POST["mtls"]; //資材
$phase = $_POST["phase"]; //申請フェーズ
$sharingMtls = $_POST['sharingMtls']; //競合資材
//20160229 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
$sharingMtls = str_replace("'","''",$sharingMtls);
//20160229 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
$sharingTrbnum = $_POST["sharingTrbnum"]; //相乗対象番号
$itReleaseJudgmentPass = $_POST['itReleaseJudgmentPass']; //ITリリース判定通過
$itRepoCommit = $_POST['itRepoCommit']; //ITリポコミット
$itMtls = $_POST['itMtls']; //IT資材
$itRelease = $_POST['itRelease']; //ITリリース
$itPIC = $_POST['itPIC']; //IT担当者
$stReleaseJudgmentPass = $_POST['stReleaseJudgmentPass']; //STリリース判定通過
$stRepoCommit = $_POST['stRepoCommit']; //STリポコミット
$stMtls = $_POST['stMtls']; //ST資材
$stRelease1 = $_POST['stRelease1']; //STリリース1
$stRelease2 = $_POST['stRelease2']; //STリリース2
$stRelease3 = $_POST['stRelease3']; //STリリース3
$stRelease4 = $_POST['stRelease4']; //STリリース4
$stPIC = $_POST['stPIC']; //ST担当者
$shipmentJudgmentPass = $_POST['shipmentJudgmentPass']; //出荷判定通過
$rtRepoCommit = $_POST['rtRepoCommit']; //RTリポコミット
$rtMtls = $_POST['rtMtls']; //RT資材
$mePIC = $_POST['mePIC']; //ME担当者
$productionRepoCommit = $_POST['productionRepoCommit']; //本番リポコミット
$productionMtls = $_POST['productionMtls']; //本番資材
$productionRelease = $_POST['productionRelease']; //本番リリース
$productionPIC = $_POST['productionPIC']; //本番担当者
$deliverySupport = $_POST['deliverySupport']; //納品支援
$deliverySupportPIC = $_POST['deliverySupportPIC']; //納品支援担当者
$scManager = $_POST['scManager']; //SC-Manager
$scManagerPIC = $_POST['scManagerPIC']; //SC-Manager担当者
$remoteBkoutput = $_POST['remoteBkoutput']; //遠隔地BK出力
$remoteBkPIC = $_POST['remoteBkPIC']; //遠隔地BK担当者
$internalCorrespondenceFlag = $_POST['internalCorrespondenceFlag']; //内部対応フラグ
$completionFlag = $_POST['completionFlag']; //終了フラグ
$remarks = $_POST["remarks"]; //備考
//20160229 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
$remarks = str_replace("'","''",$remarks);
//20160229 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加

//POST args and mtls check. 更新申請時の内容チェックと結果返却
$chk_updlendmsg = chk_updlendmsg($phase); //必須入力チェック
//$chk_mtls = chk_mtls($mtls);
if($chk_updlendmsg == 0){
	//upd_lendmsg.
	upd_lendmsg($lendmsgid,$trbnum,$phase,$contact,$cngnum,$subject,$sharingMtls,$sharingTrbnum,
		    $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,
		    $stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
		    $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
		    $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
		    $internalCorrespondenceFlag,$completionFlag,$remarks);
	$update = "現在日時に更新されました";
	//rdr summary.
	$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$contact,$update,$cngnum,$subject,$mtlstype,$sharingMtls,$sharingTrbnum,
			  $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,$stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
			  $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
			  $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
			  $internalCorrespondenceFlag,$completionFlag,$remarks);
	$rslstr = "\t<div class=\"info_sc\">貸出申請を更新しました。</div>";
	//logging.
	$logmsg = "update lendmsg  lendmsgid = $lendmsgid";
	set_log($logmsg,'updlendmsg');

}else if($chk_updlendmsg == 1){
	//申請フェーズ「完」「取下げ」に伴う資材解放
	//logging.
	$logmsg = "change lendmsg phase  phase = $phase lendmsgid = $lendmsgid";
	set_log($logmsg,'phasechg');

	//release mtl locks.
	$args = get_lendmsg($lendmsgid);
	$mtlstr = $args['mtls'];
	//mtls release.
	rls_mtls($mtlstr);

	//upd_lendmsg.
	upd_lendmsg($lendmsgid,$trbnum,$phase,$contact,$cngnum,$subject,$sharingMtls,$sharingTrbnum,
		    $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,
		    $stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
		    $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
		    $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
		    $internalCorrespondenceFlag,$completionFlag,$remarks);
	//rdr summary.
	$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$contact,$update,$cngnum,$subject,$mtlstype,$sharingMtls,$sharingTrbnum,
			  $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,$stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
			  $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
			  $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
			  $internalCorrespondenceFlag,$completionFlag,$remarks);
	$rslstr = "\t<div class=\"info_sc\">貸出申請を更新しました。</div>";
	//logging.
	$logmsg = "update lendmsg  lendmsgid = $lendmsgid";
	set_log($logmsg,'updlendmsg');

//}else{
//	$chkstr = $chk_updlendmsg;
//	//rdr summary.
//	$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$contact,$update,$cngnum,$subject,$mtlstype,$sharingMtls,$sharingTrbnum,
//			  $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,$stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
//			  $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
//			  $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
//			  $internalCorrespondenceFlag,$completionFlag,$remarks);
//	$rslstr = "\t<div class=\"info_err\">上記理由により、貸出申請が更新できません。再度、入力項目をご確認ください。</div>";
//	//logging.
//	$logmsg = "update lendmsg failed.";
//	set_log($logmsg,'updlendmsg');
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
</head>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/admin.php">mlm :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">申請サマリ.</div>
	<div class="article">
$smystr
	</div>
</div>
<div class="item">
	<div class="title">システム受付結果.</div>
<!--20151112 DEL STA
	<div class="article">
$chkstr
	</div>
20151112 DEL END-->
$rslstr
	<div class="button"><a href="/$mlmname/admin.php">戻る</a></div>
</div>
</body>
</html>
DOC_END;
?>
