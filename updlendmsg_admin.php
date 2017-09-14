<?php
////////////
//updlendmsg_admin
////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;
$targetrep = MYSQL_DBNAME;

//connect mysql.
mysqlconnect();

//get lendmsgid.
$lendmsgid = $_GET["lendmsgid"];

//get POST lendmsg.
$args = get_lendmsg($lendmsgid);
$lenduser = $args["lenduser"]; //申請者
$contact = $args["contact"]; //連絡先
$trbnum = $args["trbnum"]; //関連帳票番号
$cngnum = $args["cngnum"]; //変更管理番号
$subject = $args["subject"]; //件名
$mtlstype = $args["mtlstype"]; //改修資材分類
$mtls = $args["mtls"]; //資材
$phase = $args["phase"]; //申請フェーズ
$update = $args["update"]; //最終更新日時
$sharingMtls = $args["sharingMtls"]; //被相乗資材
$sharingTrbnum = $args["sharingTrbnum"]; //相乗対象番号
$itReleaseJudgmentPass = $args["itReleaseJudgmentPass"]; //ITリリース判定通過
$itRepoCommit = $args["itRepoCommit"]; //ITリポコミット
$itMtls = $args["itMtls"]; //IT資材
$itRelease = $args["itRelease"]; //ITリリース
$itPIC = $args["itPIC"]; //IT担当者
$stReleaseJudgmentPass = $args["stReleaseJudgmentPass"]; //STリリース判定通過
$stRepoCommit = $args["stRepoCommit"]; //STリポコミット
$stMtls = $args["stMtls"]; //ST資材
$stRelease1 = $args["stRelease1"]; //STリリース1
$stRelease2 = $args["stRelease2"]; //STリリース2
$stRelease3 = $args["stRelease3"]; //STリリース3
$stRelease4 = $args["stRelease4"]; //STリリース4
$stPIC = $args["stPIC"]; //ST担当者
$shipmentJudgmentPass = $args["shipmentJudgmentPass"]; //出荷判定通過
$rtRepoCommit = $args["rtRepoCommit"]; //RTリポコミット
$rtMtls = $args["rtMtls"]; //RT資材
$mePIC = $args["mePIC"]; //ME担当者
$productionRepoCommit = $args["productionRepoCommit"]; //本番リポコミット
$productionMtls = $args["productionMtls"]; //本番資材
$productionRelease = $args["productionRelease"]; //本番リリース
$productionPIC = $args["productionPIC"]; //本番担当者
$deliverySupport = $args["deliverySupport"]; //納品支援
$deliverySupportPIC = $args["deliverySupportPIC"]; //納品支援担当者
$scManager = $args["scManager"]; //SC-Manager
$scManagerPIC = $args["scManagerPIC"]; //SC-Manager担当者
$remoteBkoutput = $args["remoteBkoutput"]; //遠隔地BK出力
$remoteBkPIC = $args["remoteBkPIC"]; //遠隔地BK担当者
$internalCorrespondenceFlag = $args["internalCorrespondenceFlag"]; //内部対応フラグ
$completionFlag = $args["completionFlag"]; //終了フラグ
$remarks = $args["remarks"]; //備考


//貸出申請書の値をプルダウンのアクティブ項目に設定
//申請フェーズ
$searchSelect = array("貸出中","IT完","ST完","RT完","完","取下げ");
$phaseHtml = "";
foreach( $searchSelect as $value ){
	if($value == $phase){
		$phaseHtml .= "<option value=\"" . $value . "\" selected>" . $value . "</option>\n";
	}else{
		$phaseHtml .= "<option value=\"" . $value . "\">" . $value . "</option>\n";
	}
}
//内部対応フラグ
$searchSelect = array("", "内部");
foreach($searchSelect as $value){
	if($value == $internalCorrespondenceFlag){
		$internalCorrespondenceFlagHtml .= "<option value=\"" . $value . "\" selected>" . $value . "</option>\n";
	}else{
		$internalCorrespondenceFlagHtml .= "<option value=\"" . $value . "\">" . $value . "</option>\n";
	}
}
//終了フラグ
$searchSelect = array("", "終了");
foreach($searchSelect as $value){
	if($value == $completionFlag){
		$completionFlagHtml .= "<option value=\"" . $value . "\" selected>" . $value . "</option>\n";
	}else{
		$completionFlagHtml .= "<option value=\"" . $value . "\">" . $value . "</option>\n";
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
<!-- load jquery and ui -->
<link rel="stylesheet" type="text/css" href="js/jquery/themes/base/ui.all.css">
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery/ui.datepicker-ja.js"></script>
<script type="text/javascript">
$(function() {
	$("#itReleaseJudgmentPass").datepicker();
});
$(function() {
	$("#itRelease").datepicker();
});
$(function() {
	$("#stReleaseJudgmentPass").datepicker();
});
$(function() {
	$("#stRelease1").datepicker();
});
$(function() {
	$("#stRelease2").datepicker();
});
$(function() {
	$("#stRelease3").datepicker();
});
$(function() {
	$("#stRelease4").datepicker();
});
$(function() {
	$("#shipmentJudgmentPass").datepicker();
});
$(function() {
	$("#productionRelease").datepicker();
});
$(function() {
	$("#deliverySupport").datepicker();
});
$(function() {
	$("#scManager").datepicker();
});
$(function() {
	$("#remoteBkoutput").datepicker();
});
</script>
<!-- load sortable -->
<script src="js/sorttable.js" type="text/javascript"></script>
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
</head>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/admin.php">admin login :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">貸出申請書.</div>
	<div class="article">
		●は修正不可
		<form class="form" action="updlendmsg.php" method="post">
			<fieldset>
				<p class="first">
					<label for="name">●申請番号</label>
					<input type="text" name="lendmsgid" class="trbnum" size="30" value=$lendmsgid readonly="readonly">
				</p>
				<p>
					<label for="name">●申請者</label>
					<input type="text" name="lenduser" class="lenduser" size="30" value=$lenduser readonly="readonly">
				</p>
				<p>
					<label for="contact">連絡先</label>
					<input type="text" name="contact" class="contact" size="30" value=$contact>
				</p>
				<p>
					<label for="snum">●関連帳票番号<br />( / 区切り 複数指定可)<br /><br /></label>
					<input type="text" name="trbnum" class="trbnum" size="30" value=$trbnum readonly="readonly">
				</p>
				<p>
					<label for="snum">変更管理番号</label>
					<input type="text" name="cngnum" class="trbnum" size="30" value=$cngnum>
				</p>
				<p>
					<label for="message">件名</label>
					<textarea name="subject" class="message" cols="30" rows="10">$subject</textarea>
				</p>
				<p>
					<label for="message">●改修資材分類</label>
					<input type="text" name="mtlstype" class="trbnum" size="30" value=$mtlstype readonly="readonly">
				</p>
				<p>
					<label for="message">●資材<br />( / 区切り 複数指定可)</label>
					<textarea name="mtls" class="message" cols="30" rows="10" readonly="readonly">$mtls</textarea>
				</p>
				<p>
					<label for="message">申請フェーズ</label>
					<select name="phase">
						$phaseHtml
					</select>
				</p>
				<p>
					<label for="name">●最終更新日時</label>
					<input type="text" name="update" class="schedule" size="30" value=$update readonly="readonly">
				</p>
				<p>
					<label for="snum">競合資材</label>
					<textarea name="sharingMtls" class="message" cols="30" rows="10">$sharingMtls</textarea>
				</p>
				<p>
					<label for="snum">相乗対象番号<br />(資材が競合しているPMPACK番号1件)<br /><br /></label>
					<input type="text" name="sharingTrbnum" class="trbnum" size="30" value=$sharingTrbnum>
				</p>
				<p>
					<label for="time">ITリリース判定通過</label>
					<input type="text" id="itReleaseJudgmentPass" name="itReleaseJudgmentPass" class="schedule" size="30" value=$itReleaseJudgmentPass>
				</p>
				<p>
					<label for="snum">ITリポコミットrev</label>
					<input type="text" name="itRepoCommit" class="trbnum" size="30" value=$itRepoCommit>
				</p>
				<p>
					<label for="snum">IT資材</label>
					<input type="text" name="itMtls" class="trbnum" size="30" value=$itMtls>
				</p>
				<p>
					<label for="time">ITリリース</label>
					<input type="text" id="itRelease" name="itRelease" class="schedule" size="30" value=$itRelease>
				</p>
				<p>
					<label for="snum">IT担当者</label>
					<input type="text" name="itPIC" class="lenduser" size="30" value=$itPIC>
				</p>
				<p>
					<label for="time">STリリース判定通過</label>
					<input type="text" id="stReleaseJudgmentPass" name="stReleaseJudgmentPass" class="schedule" size="30" value=$stReleaseJudgmentPass>
				</p>
				<p>
					<label for="snum">STリポコミットrev</label>
					<input type="text" name="stRepoCommit" class="trbnum" size="30" value=$stRepoCommit>
				</p>
				<p>
					<label for="snum">ST資材</label>
					<input type="text" name="stMtls" class="trbnum" size="30" value=$stMtls>
				</p>
				<p>
					<label for="time">STリリース１</label>
					<input type="text" id="stRelease1" name="stRelease1" class="schedule" size="30" value=$stRelease1>
				</p>
				<p>
					<label for="time">STリリース２</label>
					<input type="text" id="stRelease2" name="stRelease2" class="schedule" size="30" value=$stRelease2>
				</p>
				<p>
					<label for="time">STリリース３</label>
					<input type="text" id="stRelease3" name="stRelease3" class="schedule" size="30" value=$stRelease3>
				</p>
				<p>
					<label for="time">STリリース４</label>
					<input type="text" id="stRelease4" name="stRelease4" class="schedule" size="30" value=$stRelease4>
				</p>
				<p>
					<label for="snum">ST担当者</label>
					<input type="text" name="stPIC" class="lenduser" size="30" value=$stPIC>
				</p>
				<p>
					<label for="time">出荷判定通過</label>
					<input type="text" id="shipmentJudgmentPass" name="shipmentJudgmentPass" class="schedule" size="30" value=$shipmentJudgmentPass>
				</p>
				<p>
					<label for="snum">RTリポコミットrev</label>
					<input type="text" name="rtRepoCommit" class="trbnum" size="30" value=$rtRepoCommit>
				</p>
				<p>
					<label for="snum">RT資材</label>
					<input type="text" name="rtMtls" class="trbnum" size="30" value=$rtMtls>
				</p>
				<p>
					<label for="snum">ME担当者</label>
					<input type="text" name="mePIC" class="lenduser" size="30" value=$mePIC>
				</p>
				<p>
					<label for="snum">本番リポコミットrev</label>
					<input type="text" name="productionRepoCommit" class="trbnum" size="30" value=$productionRepoCommit>
				</p>
				<p>
					<label for="snum">本番資材</label>
					<input type="text" name="productionMtls" class="trbnum" size="30" value=$productionMtls>
				</p>
				<p>
					<label for="time">本番リリース</label>
					<input type="text" id="productionRelease" name="productionRelease" class="schedule" size="30" value=$productionRelease>
				</p>
				<p>
					<label for="snum">本番担当者</label>
					<input type="text" name="productionPIC" class="lenduser" size="30" value=$productionPIC>
				</p>
				<p>
					<label for="time">納品支援</label>
					<input type="text" id="deliverySupport" name="deliverySupport" class="schedule" size="30" value=$deliverySupport>
				</p>
				<p>
					<label for="snum">納品支援担当者</label>
					<input type="text" name="deliverySupportPIC" class="lenduser" size="30" value=$deliverySupportPIC>
				</p>
				<p>
					<label for="time">SC-Manager</label>
					<input type="text" id="scManager" name="scManager" class="schedule" size="30" value=$scManager>
				</p>
				<p>
					<label for="snum">SC-Manager担当者</label>
					<input type="text" name="scManagerPIC" class="lenduser" size="30" value=$scManagerPIC>
				</p>
				<p>
					<label for="snum">遠隔地BK出力</label>
					<input type="text" id="remoteBkoutput" name="remoteBkoutput" class="schedule" size="30" value=$remoteBkoutput>
				</p>
				<p>
					<label for="snum">遠隔地BK担当者</label>
					<input type="text" name="remoteBkPIC" class="lenduser" size="30" value=$remoteBkPIC>
				</p>
				<p>
					<label for="message">内部対応フラグ</label>
					<select name="internalCorrespondenceFlag">
						$internalCorrespondenceFlagHtml
					</select>
				</p>
				<p>
					<label for="message">終了フラグ</label>
					<select name="completionFlag">
						$completionFlagHtml
					</select>
				</p>
				<p>
					<label for="message">備考</label>
					<textarea name="remarks" class="message" cols="30" rows="10">$remarks</textarea>
				</p>

				<p class="submit"><button type="submit">申請書更新</button></p>
			</fieldset>
		</form>
		<br />
	</div>
</div>
</body>
</html>
DOC_END;
?>
