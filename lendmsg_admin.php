<?php
////////////////////
//lendmsg_admin.php
////////////////////

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
$lendmsgid = $_GET["lendmsgid"];

//make full summary
$args = get_lendmsg($lendmsgid);
$trbnum = $args['trbnum'];
$lenduser = $args['lenduser'];
$phase = $args['phase'];
$mtls = $args['mtls'];
$contact = $args['contact'];
$update = $args['update'];
$cngnum = $args['cngnum'];
$subject = $args['subject'];
$mtlstype = $args['mtlstype'];
$sharingMaterials = $args['sharingMtls'];
$sharingTrbnum = $args['sharingTrbnum'];
$itReleaseJudgmentPass = $args['itReleaseJudgmentPass'];
$itRepoCommit = $args['itRepoCommit'];
$itMtls = $args['itMtls'];
$itRelease = $args['itRelease'];
$itPIC = $args['itPIC'];
$stReleaseJudgmentPass = $args['stReleaseJudgmentPass'];
$stRepoCommit = $args['stRepoCommit'];
$stMtls = $args['stMtls'];
$stRelease1 = $args['stRelease1'];
$stRelease2 = $args['stRelease2'];
$stRelease3 = $args['stRelease3'];
$stRelease4 = $args['stRelease4'];
$stPIC = $args['stPIC'];
$shipmentJudgmentPass = $args['shipmentJudgmentPass'];
$rtRepoCommit = $args['rtRepoCommit'];
$rtMtls = $args['rtMtls'];
$mePIC = $args['mePIC'];
$productionRepoCommit = $args['productionRepoCommit'];
$productionMtls = $args['productionMtls'];
$productionRelease = $args['productionRelease'];
$productionPIC = $args['productionPIC'];
$deliverySupport = $args['deliverySupport'];
$deliverySupportPIC = $args['deliverySupportPIC'];
$scManager = $args['scManager'];
$scManagerPIC = $args['scManagerPIC'];
$remoteBkoutput = $args['remoteBkoutput'];
$remoteBkPIC = $args['remoteBkPIC'];
$internalCorrespondenceFlag = $args['internalCorrespondenceFlag'];
$completionFlag = $args['completionFlag'];
$remarks = $args['remarks'];

$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$contact,$update,$cngnum,$subject,$mtlstype,$sharingMaterials,$sharingTrbnum,$itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,$stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,$shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,$deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,$internalCorrespondenceFlag,$completionFlag,$remarks);

//phase check.
//if($phase == '貸出中'){
//$phasestr = <<< STR_END
//	<div class="title">フェーズの移行.</div>
//	<div class="article">
//		[SVN構成管理窓口MENU]<br />
//		!! このフェーズ移行は基本的にユーザ操作です。 !!<br />
//		SVN構成管理窓口の操作として、[返却可]フェーズに移行する。</br >
//	</div>
//	<div class="button">
//		<a href="phase.php?phase=1&lendmsgid=$lendmsgid">返却可 フェーズに移行する</a>
//	</div>
//STR_END;
//}else if($phase == '返却可'){
//$phasestr = <<< STR_END
//	<div class="title">フェーズの移行.</div>
//	<div class="article">
//		SVN構成管理窓口の操作として、[反映完]フェーズに移行する。<br />
//		※[返却可] -> [反映完]への移行タイミングは[リリース判定会議で承認後]です。<br />
//		  リリース判定会議後、ITリポジトリにコミットし、[反映完]にフェーズを移行してください。
//	</div>
//	<div class="button">
//		<a href="phase.php?phase=2&lendmsgid=$lendmsgid">反映完 フェーズに移行する</a>
//	</div>
//STR_END;
//}else if($phase == '反映完'){
//$phasestr = <<< STR_END
//	<div class="title">フェーズの移行.</div>
//	<div class="article">
//		[SVN構成管理窓口MENU]<br />
//		上記資材のITリポジトリへの反映が完了いたしました。<br />
//		また、上記資材のロックを解除しました。<br />
//		次回資材配布タイミングをお待ちください。<br />
//	</div>
//STR_END;
//}
if($phase == '完'){
$phasestr = <<< STR_END
	<div class="article">
		申請フェーズ[完]の貸出申請書です。貸出申請書更新はできません。<br />
	</div>
STR_END;
}else if($phase == '取下げ'){
$phasestr = <<< STR_END
	<div class="article">
		申請フェーズ[取下げ]の貸出申請書です。貸出申請書更新はできません。<br />
	</div>
STR_END;
}else if($phase == '新規管理資材申請'){
$phasestr = <<< STR_END
	<div class="article">
		申請フェーズ[新規管理資材申請]の貸出申請書です。貸出申請書更新はできません。<br />
	</div>
STR_END;
}else if($phase == '資材削除申請'){
$phasestr = <<< STR_END
	<div class="article">
		申請フェーズ[資材削除申請]の貸出申請書です。貸出申請書更新はできません。<br />
	</div>
STR_END;
}else if(!($phase == '完' or $phase == '取下げ')){
$phasestr = <<< STR_END
	<div class="button">
		<!-- <a href="phase.php?phase=1&lendmsgid=$lendmsgid">貸出申請書更新</a> -->
		<!-- <a href="updlendmsg_admin.php" target="_blank">貸出申請書更新画面へ</a> -->
		<!-- <a href="updlendmsg_admin.php">貸出申請書更新画面へ</a> -->
		<a href="updlendmsg_admin.php?lendmsgid=$lendmsgid" target="_top">貸出申請書更新画面へ</a>
	</div>
STR_END;
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
</head>
<body>
<div class="item">
	<div class="title">貸出申請書.</div>
	$phasestr
	<div class="article">
	$smystr
	</div>
</div>
</body>
</html>
DOC_END;
?>