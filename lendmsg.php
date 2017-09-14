<?php
//////////////
//lendmsg.php
//////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;
//20150911 UPD STA
//$targetrep = TARGETREP;
$targetrep = MYSQL_DBNAME;
//20150911 UPD STA

//connect mysql.
mysqlconnect();

//get lendmsgid.
$lendmsgid = $_GET["lendmsgid"];

//make full summary
//20150827 UPD STA
//$args = get_lendmsg($lendmsgid);
//$trbnum = $args['trbnum'];
//$lenduser = $args['lenduser'];
//$phase = $args['phase'];
//$mtls = $args['mtls'];
//$reason = $args['reason'];
//$contact = $args['contact'];
//$schedule = $args['schedule'];
//$update = $args['update'];
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
$sharingMtls = $args['sharingMtls'];
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
//20150827 UPD END

//20150828 DEL STA
////get rdnums.
//$rdnums = get_rdnums($trbnum);
//if(!$rdnums[0]){
//	$rdnum_str = "[ERR] redmine上でチケットを確認することが出来ませんでした。";
//}else{
//	foreach ($rdnums as $rdnum) {
//		if(!$rdnum){
//			$rdnum_str .= "[ERR]/";
//		}else{
//			$rdnum_str .= "#".$rdnum."/";
//		}
//	}
//	$rdnum_str = rtrim($rdnum_str, "/");
//	$rdnum_str .= "<br />(現時点でのredmineDB検索結果を表示)";
//}
//20150828 DEL END
//20150827 UPD STA
//$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$reason,$contact,$schedule,$update,$rdnum_str);
$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$contact,$update,$cngnum,$subject,$mtlstype,$sharingMtls,$sharingTrbnum,$itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,$stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,$shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,$deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,$internalCorrespondenceFlag,$completionFlag,$remarks);
//20150827 UPD END

//20150828 DEL STA
////phase check.
//if($phase == '貸出中'){
//$phasestr = <<< STR_END
//	<div class="title">フェーズの移行.</div>
//	<div class="article">
//		資材修正完了後、SVN構成管理窓口がITリポジトリへ資材の反映を行います。<br />
//		チーム内窓口は上記の資材修正完了を確認した後、フェーズを[返却可]に移行させてください。<br />
//	</div>
//	<div class="button">
//		<a href="phase.php?phase=1&lendmsgid=$lendmsgid">返却可 フェーズに移行する</a>
//	</div>
//STR_END;
//}else if($phase == '返却可'){
//$phasestr = <<< STR_END
//	<div class="title">フェーズの移行.</div>
//	<div class="article">
//		現在、SVN構成管理窓口によるITリポジトリへの反映作業を行っています。<br />
//		[反映完]フェーズまでしばらくお待ちください。<br />
//	</div>
//STR_END;
//}else if($phase == '反映完'){
//$phasestr = <<< STR_END
//	<div class="title">フェーズの移行.</div>
//	<div class="article">
//		上記資材のITリポジトリへの反映が完了しました。<br />
//		また、上記資材のロックを解除しました。<br />
//		次回資材配布タイミングをお待ちください。<br />
//	</div>
//STR_END;
//}
//20150828 DEL END

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
	body, .title { background: url(images/bg.png) };
</style>
</head>
<body>
<div class="item">
	<div class="title">貸出申請書.</div>
	<div class="article">
$smystr
	</div>
</div>
<!--20150828 DEL STA
<div class="item">
$phasestr
</div>
20150828 DEL END-->
</body>
</html>
DOC_END;
?>