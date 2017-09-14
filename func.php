<?php
///////////
//func.php
///////////

//includes.
include_once "conf.php";

function mysqlconnect() { //mysqlの接続を取得する。
	$link = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PW);
	mysql_select_db(MYSQL_DBNAME,$link);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=001");
		exit;
	}
	mysql_query('set names utf8');
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=002");
		exit;
	}
}
//20160218 UPD STA 新規貸出申請時の登録処理見直し
//function new_lendmsg($trbnum,$lenduser,$mtls,$reason,$contact,$schedule) { //新規に貸出申請を登録する。
function new_lendmsg($mtls) { //新規に貸出申請（資材名）を登録する。
//20160218 UPD END 新規貸出申請時の登録処理見直し
	$update = date("Y/m/d H:i");
	$phase = "貸出中";
	//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
	$mtls = str_replace("'","''",$mtls);
	//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
	//20160218 UPD STA 新規貸出申請時の登録処理見直し
	//$query = "INSERT INTO lendmsg (`trbnum` ,`lenduser` ,`phase` ,`mtls` ,`reason` ,`contact` ,`schedule` ,`update`)VALUES ('$trbnum', '$lenduser', '$phase', '$mtls', '$reason', '$contact', '$schedule', '$update')";
	$query = "INSERT INTO lendmsg (`phase`,`mtls`,`update`)VALUES ('$phase','$mtls','$update')";
	//20160218 UPD END 新規貸出申請時の登録処理見直し
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=003");
		exit;
	}
	$query = 'insert into table_name ...';
	$result = mysql_query($query);
	$lendmsgid = mysql_insert_id();
	$mtls_ary = split("/", "$mtls");
	foreach ($mtls_ary as $mtl) {
		$query = "UPDATE mtl SET `LENDMSGID`='$lendmsgid' WHERE `mtlname`='$mtl'";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=005");
			exit;
		}
	}
	return $lendmsgid;
}
//20160218 UPD STA 新規貸出申請時の登録処理見直し
function upd_newlendmsg($lendmsgid,$trbnum,$lenduser,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks){ //申請番号、資材名のみ新規貸出申請を更新する。
	$update = date("Y/m/d H:i");
	$query = "UPDATE lendmsg
		  SET `trbnum` = '$trbnum',`lenduser` = '$lenduser',`update` = '$update',`cngnum` = '$cngnum',`subject` = '$subject',`mtlstype` = '$mtlstype',`sharingTrbnum` = '$sharingTrbnum',`remarks` = '$remarks'
		  WHERE `LENDMSGID` = '$lendmsgid'";
	$result = mysql_query($query);
	return $update;
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=003");
		exit;
	}
}
//20160218 UPD END 新規貸出申請時の登録処理見直し
//20150914 ADD STA 貸出申請更新処理追加対応/20160223 相乗り時の関連帳票番号入力処理
function upd_lendmsg($lendmsgid,$trbnum,$phase,$contact,$cngnum,$subject,$sharingMtls,$sharingTrbnum,
		     $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,
		     $stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
		     $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
		     $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
		     $internalCorrespondenceFlag,$completionFlag,$remarks){ //貸出申請を更新する。
	$update = date("Y/m/d H:i");
	if($sharingTrbnum <> ""){
		$trbnum = $trbnum . "/" . $sharingTrbnum;
		$sharingTrbnum = "";
	}
	$query = "UPDATE lendmsg
		     SET `trbnum` = '$trbnum',`phase` = '$phase',`contact` = '$contact',`update` = '$update',`cngnum` = '$cngnum',`subject` = '$subject',`sharingMtls` = '$sharingMtls',`sharingTrbnum` = '$sharingTrbnum',
		         `itReleaseJudgmentPass` = '$itReleaseJudgmentPass',`itRepoCommit` = '$itRepoCommit',`itMtls` = '$itMtls',`itRelease` = '$itRelease',`itPIC` = '$itPIC',
		         `stReleaseJudgmentPass` = '$stReleaseJudgmentPass',`stRepoCommit` = '$stRepoCommit',`stMtls` = '$stMtls',`stRelease1` = '$stRelease1',`stRelease2` = '$stRelease2',`stRelease3` = '$stRelease3',`stRelease4` = '$stRelease4',`stPIC` = '$stPIC',
		         `shipmentJudgmentPass` = '$shipmentJudgmentPass',`rtRepoCommit` = '$rtRepoCommit',`rtRepoCommit` = '$rtRepoCommit',`rtMtls` = '$rtMtls',`mePIC` = '$mePIC',
		         `productionRepoCommit` = '$productionRepoCommit',`productionMtls` = '$productionMtls',`productionRelease` = '$productionRelease',`productionPIC` = '$productionPIC',
		         `deliverySupport` = '$deliverySupport',`deliverySupportPIC` = '$deliverySupportPIC',`scManager` = '$scManager',`scManagerPIC` = '$scManagerPIC',`remoteBkoutput` = '$remoteBkoutput',`remoteBkPIC` = '$remoteBkPIC',
		         `internalCorrespondenceFlag` = '$internalCorrespondenceFlag',`completionFlag` = '$completionFlag',`remarks` = '$remarks'
		   WHERE `LENDMSGID` = '$lendmsgid'";
	$result = mysql_query($query);
	return $update;
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=003");
		exit;
	}
}
//20150914 ADD END 貸出申請更新処理追加対応/20160223 相乗り時の関連帳票番号入力処理
function set_phase($lendmsgid,$phase) { //貸出申請のフェーズを更新する。
	$update = date("Y/m/d H:i");
	$query = "UPDATE lendmsg SET `phase`='$phase' ,`update`='$update' WHERE `lendmsgid`='$lendmsgid'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=006");
		exit;
	}
}
function rdr_tbllendmsg($lim,$pagenum) { //インデックスページの貸出申請テーブルを1ページ分書き出す。
	$query = "SELECT * FROM lendmsg ORDER BY LENDMSGID DESC";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=007");
		exit;
	}
	$pkey = 1;
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=008");
			exit;
		}
		$lendmsgid = $row['LENDMSGID'];
		$trbnum = $row['trbnum'];
		$lenduser = $row['lenduser'];
		$phase = $row['phase'];
		$update = $row['update'];
		$tbllendmsgs[$pkey] .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		if($i == $lim){
			$pkey++;
			$i = 0;
		}
		$i++;
	}
	$tbllendmsg = $tbllendmsgs[$pagenum];
	return $tbllendmsg;
}
function rdr_tbllendmsgpage($lim,$pagenum) { //インデックスページの貸出申請テーブル用ページリンク(pager)を書き出す。
	$query = "SELECT COUNT(*) AS reccnt FROM lendmsg";
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=009");
		exit;
	}
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$reccnt = $row["reccnt"];
	$lastpagenum = ceil($reccnt / $lim);
	$prevpagenum = $pagenum - 1;
	$nextpagenum = $pagenum + 1;	
	$readendpnt = $pagenum * $lim;
	$readendpnt = $reccnt - $readendpnt;
	$readstartpnt = $readendpnt + $lim;
	$readendpnt = $readendpnt + 1;
	if(1 > $readendpnt){
		$readendpnt = 1;
	}
	$tbllendmsgpage .= "\t\t<div class=\"pager\">";
	if($prevpagenum != 0){
		$tbllendmsgpage .= "<div class=\"pager_prev\"><a href=\"?msg_p=$prevpagenum\">&laquo; Prev</a></div>";
	}else{
		$tbllendmsgpage .= "<div class=\"pager_prev\"></div>";
	}
	$tbllendmsgpage .= "<div class=\"pager_center\">$readstartpnt - $readendpnt of $reccnt</div>";
	if($pagenum != $lastpagenum){
		$tbllendmsgpage .= "<div class=\"pager_next\"><a href=\"?msg_p=$nextpagenum\">Next &raquo;</a></div>";
	}else{
		$tbllendmsgpage .= "<div class=\"pager_next\"></div>";
	}
	$tbllendmsgpage .= "</div>";
	if(!$reccnt){
		$tbllendmsgpage = "\t\t<div class=\"pager\">";
		$tbllendmsgpage .= "<div class=\"pager_prev\"></div>";
		$tbllendmsgpage .= "<div class=\"pager_center\">$readstartpnt - $readstartpnt of $reccnt</div>";
		$tbllendmsgpage .= "<div class=\"pager_next\"></div>";
		$tbllendmsgpage .= "</div>";
	}
	return $tbllendmsgpage;
}
//20150828 インデックスページの貸出資源表示不要対応 DEL STA
//
//function rdr_tblmtl($lim,$pagenum) { //インデックスページの貸出資源テーブルを1ページ分書き出す。
//	$query = "SELECT mtlname, LENDMSGID FROM mtl WHERE NOT LENDMSGID=0 ORDER BY LENDMSGID DESC";
//	$result = mysql_query($query);
//	if(mysql_error()){
//		$errlog = mysql_error();
//		header("Location: err.php?errlog=$errlog&errnum=010");
//		exit;
//	}
//	$pkey = 1;
//	$i = 1;
//	while ($row = mysql_fetch_assoc($result)) {
//		$mtlname = $row['mtlname'];
//		$lendmsgid = $row['LENDMSGID'];
//		$query_n = "SELECT trbnum, lenduser, schedule FROM lendmsg WHERE LENDMSGID='$lendmsgid'";
//		$result_n = mysql_query($query_n);
//		if(mysql_error()){
//			$errlog = mysql_error();
//			header("Location: err.php?errlog=$errlog&errnum=011");
//			exit;
//		}
//		$row_n = mysql_fetch_assoc($result_n);
//		$trbnum = $row_n['trbnum'];
//		$lenduser = $row_n['lenduser'];
//		$schedule = $row_n['schedule'];
//		$tblmtls[$pkey] .= "\t\t\t<tr><th>$mtlname</th><th>$trbnum</th><th>$lendmsgid</th><th>$lenduser</th><th>$schedule</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
//		if($i == $lim){
//			$pkey++;
//			$i = 0;
//		}
//		$i++;
//	}
//	$tblmtl = $tblmtls[$pagenum];
//	return $tblmtl;
//}
//function rdr_tblmtlpage($lim,$pagenum) { //インデックスページの貸出資源テーブル用ページリンク(pager)を書き出す。
//	$query = "SELECT mtlname, LENDMSGID FROM mtl WHERE NOT LENDMSGID=0";
//	$result = mysql_query($query);
//	if(mysql_error()){
//		$errlog = mysql_error();
//		header("Location: err.php?errlog=$errlog&errnum=012");
//		exit;
//	}
//	$reccnt = 0;
//	while ($row = mysql_fetch_assoc($result)) {
//		$reccnt++;
//	}
//	$lastpagenum = ceil($reccnt / $lim);
//	$prevpagenum = $pagenum - 1;
//	$nextpagenum = $pagenum + 1;
//	$readendpnt = $pagenum * $lim;
//	$readendpnt = $reccnt - $readendpnt;
//	$readstartpnt = $readendpnt + $lim;
//	$readendpnt = $readendpnt + 1;
//	if(1 > $readendpnt){
//		$readendpnt = 1;
//	}
//	$tbllendmsgpage .= "\t\t<div class=\"pager\">";
//	if($prevpagenum != 0){
//		$tbllendmsgpage .= "<div class=\"pager_prev\"><a href=\"?mtl_p=$prevpagenum\">&laquo; Prev</a></div>";
//	}else{
//		$tbllendmsgpage .= "<div class=\"pager_prev\"></div>";
//	}
//	$tbllendmsgpage .= "<div class=\"pager_center\">$readstartpnt - $readendpnt of $reccnt</div>";
//	if($pagenum != $lastpagenum){
//		$tbllendmsgpage .= "<div class=\"pager_next\"><a href=\"?mtl_p=$nextpagenum\">Next &raquo;</a></div>";
//	}else{
//		$tbllendmsgpage .= "<div class=\"pager_next\"></div>";
//	}
//	$tbllendmsgpage .= "</div>";
//	if(!$reccnt){
//		$tbllendmsgpage = "\t\t<div class=\"pager\">";
//		$tbllendmsgpage .= "<div class=\"pager_prev\"></div>";
//		$tbllendmsgpage .= "<div class=\"pager_center\">$readstartpnt - $readstartpnt of $reccnt</div>";
//		$tbllendmsgpage .= "<div class=\"pager_next\"></div>";
//		$tbllendmsgpage .= "</div>";
//	}
//	return $tbllendmsgpage;
//}
//20150828 インデックスページの貸出資源表示不要対応 DEL END
function chk_mtls($mtlstr) { //資材の貸出可否を判定する。
	$mtls = split("/", "$mtlstr");
	foreach ($mtls as $mtl) {
		//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
		$mtl = str_replace("'","''",$mtl);
		//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
		$query = "SELECT mtlname, LENDMSGID FROM mtl WHERE mtlname='$mtl'";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=013");
			exit;
		}
		$row = mysql_fetch_assoc($result);
		$q_mtl = $row['mtlname'];
		//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
		$q_mtl = str_replace("'","''",$q_mtl);
		//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
		if($mtl != $q_mtl){
			$mtl_none[] = $mtl;
		}else{
			$lendmsgid = $row['LENDMSGID'];
			if(!($lendmsgid == '0')){
				$mtl_lended[] = $mtl;
				//20160212 UPD STA 貸出中資材名の隣に申請書ポップアップ表示の追加
				$lendmsgid_lended[] = $lendmsgid;
				//20160212 UPD END 貸出中資材名の隣に申請書ポップアップ表示の追加
			}
		}
	}
	if(!$mtl_none && !$mtl_lended){
		return 0;
	}else{
		if($mtl_none){	
			foreach ($mtl_none as $errmtl) {
				$errcode .= "\t\t<div class=\"info\">\n\t\t\t<li>資材管理</li>\n\t\t\t<div class=\"info_article\">以下の資材は管理していません。 資材名 = $errmtl</div>\n\t\t</div>\n";
			}
		}
		if($mtl_lended){
			//20160212 UPD STA 貸出中資材名の隣に申請書ポップアップ表示の追加
			//foreach ($mtl_lended as $errmtl) {
			//	$errcode .= "\t\t<div class=\"info\">\n\t\t\t<li>資材管理</li>\n\t\t\t<div class=\"info_article\">以下の資材は貸出中です。 資材名 = $errmtl</div>\n\t\t</div>\n";
			//}
			while ((list(, $errmtl) = each($mtl_lended)) && (list(, $lendmsgid) = each($lendmsgid_lended))){
				$errcode .= "\t\t<div class=\"info\">\n\t\t\t<li>資材管理</li>\n\t\t\t<div class=\"info_article\">以下の資材は貸出中です。 資材名 = $errmtl<a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></div>\n\t\t</div>\n";
			}
			//20160212 UPD END 貸出中資材名の隣に申請書ポップアップ表示の追加
		}
	}
	return $errcode;
}
//20160218 UPD STA 新規貸出申請時の登録処理見直し
//function chk_newlendmsg($lenduser,$contact,$schedule,$trbnum,$mtls,$reason) { //貸出申請受付時のフォーム入力内容を判定する。
//	if(!(strlen($lenduser) > 1)){
//		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">申請者を入力してください。</div>\n\t\t</div>\n";
//	}
//	if(!(strlen($contact) > 1)){
//		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">連絡先を入力してください。</div>\n\t\t</div>\n";
//	}
//	if(!(strlen($schedule) > 1)){
//		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">返却予定日を入力してください。</div>\n\t\t</div>\n";
//	}
//	if(!(strlen($trbnum) > 1)){
//		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">関連帳票番号を入力してください。</div>\n\t\t</div>\n";
//	}
//	if(!(strlen($mtls) > 1)){
//		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">資材を入力してください。</div>\n\t\t</div>\n";
//	}
//	if(!$err){
//		return 0;
//	}else{
//		return $err;
//	}
function chk_newlendmsg($mtls) { //貸出申請受付時のフォーム入力内容（資材）を判定する。
	if(!(strlen($mtls) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">資材を入力してください。</div>\n\t\t</div>\n";
	}
	if(!$err){
		return 0;
	}else{
		return $err;
	}
}
//20160218 UPD END 新規貸出申請時の登録処理見直し
//20160218 ADD STA 新規貸出申請時の登録処理見直し
function chk_newlendmsginf($lenduser,$trbnum,$mtlstype) { //貸出申請受付時のフォーム入力内容（申請者、関連帳票番号、改修資材分類）を判定する。
	if(!(strlen($lenduser) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">申請者を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($trbnum) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">関連帳票番号を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($mtlstype) > 0)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">改修資材分類を入力してください。</div>\n\t\t</div>\n";
	}
	if(!$err){
		return 0;
	}else{
		return $err;
	}
}
//20160218 ADD END 新規貸出申請時の登録処理見直し
//20160217 ADD STA 新規管理資材登録必須項目入力チェック
function chk_newmtl($lenduser,$trbnum,$mtls,$path) { //新規管理資材登録時のフォーム入力内容を判定する。
	$mtls = trim(mb_convert_kana($mtls, "s"));
	$path = trim(mb_convert_kana($path, "s"));
	if(!(strlen($lenduser) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">申請者を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($trbnum) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">関連帳票番号を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($mtls) > 0)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">新規資材名を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($path) > 0)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">資材格納パスを入力してください。</div>\n\t\t</div>\n";
	}
	if(!$err){
		return 0;
	}else{
		return $err;
	}
}
//20160217 ADD END 新規管理資材登録必須項目入力チェック
//20160217 ADD STA 管理資材の削除必須項目入力チェック
function chk_delmtl($lenduser,$trbnum,$mtls) { //管理資材の削除時のフォーム入力内容を判定する。
	if(!(strlen($lenduser) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">申請者を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($trbnum) > 1)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">関連帳票番号を入力してください。</div>\n\t\t</div>\n";
	}
	if(!(strlen($mtls) > 0)){
		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">削除資材名を入力してください。</div>\n\t\t</div>\n";
	}
	if(!$err){
		return 0;
	}else{
		return $err;
	}
}
//20160217 ADD END 管理資材の削除必須項目入力チェック
//20150914 貸出申請更新時のフォーム入力内容判定（申請フェーズ） ADD STA
function chk_updlendmsg($phase) { //貸出申請更新時のフォーム入力内容を判定する。
//	if($phase == 0){
//		$err .= "\t\t<div class=\"info\">\n\t\t\t<li>受付エラー</li>\n\t\t\t<div class=\"info_article\">申請フェーズを入力してください。</div>\n\t\t</div>\n";
//	}
	if(($phase === "完") or ($phase === "取下げ")){ //申請フェーズが「完」「取下げ」の場合、資材ロック解除する
		return 1;
//	}
//	if(!$err){
//		return 0;
	}else{
//		return $err;
		return 0;
	}
}
//20150914 貸出申請更新時のフォーム入力内容判定（申請フェーズ） ADD END
//20150824 UPD STA
//function rdr_summary($lendmsgid,$trbnum,$lenduser,$mtls,$reason,$contact,$schedule,$rdnum) { //貸出申請受付時のサマリを書き出す。
function rdr_summary($lendmsgid,$trbnum,$lenduser,$mtls,$cngnum,$subject,$mtlstype,$sharingTrbnum,$remarks) { //貸出申請受付時のサマリを書き出す。
//20150824 UPD END
	$smystr = <<< DOC_END
		<div class="summary">
			<table class="summary_tbl">
<!--20150824 DEL STA
				<tr>
					<th>申請番号(自動採番)</th>
					<td><h1>$lendmsgid</h1></td>
				</tr>
				<tr>
					<th>申請者</th>
					<td><h1>$lenduser</h1></td>
				</tr>
				<tr>
					<th>連絡先</th>
					<td><h1>$contact</h1></td>
				</tr>
				<tr>
					<th>関連帳票番号</th>
					<td><h1>$trbnum</h1></td>
				</tr>
				<tr>
					<th>返却予定日</th>
					<td><h1>$schedule</h1></td>
				</tr>
				<tr>
					<th>資材</th>
					<td><h1>$mtls</h1></td>
				</tr>
				<tr>
					<th>申請理由</th>
					<td><h1>$reason</h1></td>
				</tr>
				<tr>
					<th>redmine<br />チケット番号</th>
					<td><h1>$rdnum</h1></td>
				</tr>
20150824 DEL END-->
<!--20150824 ADD STA-->
				<tr>
					<th>申請番号(自動採番)</th>
					<td><h1>$lendmsgid</h1></td>
				</tr>
				<tr>
					<th>申請者</th>
					<td><h1>$lenduser</h1></td>
				</tr>
				<tr>
					<th>関連帳票番号</th>
					<td><h1>$trbnum</h1></td>
				</tr>
				<tr>
					<th>変更管理番号</th>
					<td><h1>$cngnum</h1></td>
				</tr>
				<tr>
					<th>件名</th>
					<td><h1>$subject</h1></td>
				</tr>
				<tr>
					<th>改修資材分類</th>
					<td><h1>$mtlstype</h1></td>
				</tr>
				<tr>
					<th>資材</th>
					<td><h1>$mtls</h1></td>
				</tr>
				<tr>
					<th>相乗対象番号</th>
					<td><h1>$sharingTrbnum</h1></td>
				</tr>
				<tr>
					<th>備考</th>
					<td><h1>$remarks</h1></td>
				</tr>
<!--20150824 ADD END-->
			</table>
		</div>
DOC_END;
return $smystr;
}
// 20150827 DEL STA
//function rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$reason,$contact,$schedule,$update,$rdnum) { //貸出申請書のフルサマリを書き出す。
//$smystr = <<< DOC_END
//		<div class="summary">
//			<table class="summary_tbl_full">
//				<tr>
//					<th>申請番号</th>
//					<td>$lendmsgid</td>
//				</tr>
//				<tr>
//					<th>最終更新日時</th>
//					<td>$update</td>
//				</tr>
//				<tr>
//					<th>申請者</th>
//					<td>$lenduser</td>
//				</tr>
//				<tr>
//					<th>連絡先</th>
//					<td>$contact</td>
//				</tr>
//				<tr>
//					<th>関連帳票番号</th>
//					<td>$trbnum</td>
//				</tr>
//				<tr>
//					<th>返却予定日</th>
//					<td>$schedule</td>
//				</tr>
//				<tr>
//					<th>資材</th>
//					<td>$mtls</td>
//				</tr>
//				<tr>
//					<th>申請理由</th>
//					<td>$reason</td>
//				</tr>
//				<tr>
//					<th>申請フェーズ</th>
//					<td>$phase</td>
//				</tr>
//				<tr>
//					<th>redmine<br />チケット番号</th>
//					<td><h1>$rdnum</h1></td>
//				</tr>
//			</table>
//		</div>
//DOC_END;
//return $smystr;
//}
// 20150827 DEL END
// 20150827 ADD STA
function rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$contact,$update,$cngnum,$subject,$mtlstype,$sharingMtls,$sharingTrbnum,
			  $itReleaseJudgmentPass,$itRepoCommit,$itMtls,$itRelease,$itPIC,$stReleaseJudgmentPass,$stRepoCommit,$stMtls,$stRelease1,$stRelease2,$stRelease3,$stRelease4,$stPIC,
			  $shipmentJudgmentPass,$rtRepoCommit,$rtRepoCommit,$rtMtls,$mePIC,$productionRepoCommit,$productionMtls,$productionRelease,$productionPIC,
			  $deliverySupport,$deliverySupportPIC,$scManager,$scManagerPIC,$remoteBkoutput,$remoteBkPIC,
			  $internalCorrespondenceFlag,$completionFlag,$remarks) { //貸出申請書のフルサマリを書き出す。左はlendmsg順。不要なのは書かない。
$smystr = <<< DOC_END
		<div class="summary">
			<table class="summary_tbl_full">
				<tr>
					<th>申請番号</th>
					<td>$lendmsgid</td>
				</tr>
				<tr>
					<th>申請者</th>
					<td>$lenduser</td>
				</tr>
				<tr>
					<th>連絡先</th>
					<td>$contact</td>
				</tr>
				<tr>
					<th>関連帳票番号</th>
					<td>$trbnum</td>
				</tr>
				<tr>
					<th>変更管理番号</th>
					<td>$cngnum</td>
				</tr>
				<tr>
					<th>件名</th>
					<td>$subject</td>
				</tr>
				<tr>
					<th>改修資材分類</th>
					<td>$mtlstype</td>
				</tr>
				<tr>
					<th>資材</th>
					<td>$mtls</td>
				</tr>
				<tr>
					<th>申請フェーズ</th>
					<td>$phase</td>
				</tr>
				<tr>
					<th>最終更新日時</th>
					<td>$update</td>
				</tr>
				<tr>
					<th>競合資材</th>
					<td>$sharingMtls</td>
				</tr>
				<tr>
					<th>相乗対象番号</th>
					<td>$sharingTrbnum</td>
				</tr>
				<tr>
					<th>ITリリース判定通過</th>
					<td>$itReleaseJudgmentPass</td>
				</tr>
				<tr>
					<th>ITリポコミット</th>
					<td>$itRepoCommit</td>
				</tr>
				<tr>
					<th>IT資材</th>
					<td>$itMtls</td>
				</tr>
				<tr>
					<th>ITリリース</th>
					<td>$itRelease</td>
				</tr>
				<tr>
					<th>IT担当者</th>
					<td>$itPIC</td>
				</tr>
				<tr>
					<th>STリリース判定通過</th>
					<td>$stReleaseJudgmentPass</td>
				</tr>
				<tr>
					<th>STリポコミット</th>
					<td>$stRepoCommit</td>
				</tr>
				<tr>
					<th>ST資材</th>
					<td>$stMtls</td>
				</tr>
				<tr>
					<th>STリリース１</th>
					<td>$stRelease1</td>
				</tr>
				<tr>
					<th>STリリース２</th>
					<td>$stRelease2</td>
				</tr>
				<tr>
					<th>STリリース３</th>
					<td>$stRelease3</td>
				</tr>
				<tr>
					<th>STリリース４</th>
					<td>$stRelease4</td>
				</tr>
				<tr>
					<th>ST担当者</th>
					<td>$stPIC</td>
				</tr>
				<tr>
					<th>出荷判定通過</th>
					<td>$shipmentJudgmentPass</td>
				</tr>
				<tr>
					<th>RTリポコミット</th>
					<td>$rtRepoCommit</td>
				</tr>
				<tr>
					<th>RT資材</th>
					<td>$rtMtls</td>
				</tr>
				<tr>
					<th>ME担当者</th>
					<td>$mePIC</td>
				</tr>
				<tr>
					<th>本番リポコミット</th>
					<td>$productionRepoCommit</td>
				</tr>
				<tr>
					<th>本番資材</th>
					<td>$productionMtls</td>
				</tr>
				<tr>
					<th>本番リリース</th>
					<td>$productionRelease</td>
				</tr>
				<tr>
					<th>本番担当者</th>
					<td>$productionPIC</td>
				</tr>
				<tr>
					<th>納品支援</th>
					<td>$deliverySupport</td>
				</tr>
				<tr>
					<th>納品支援担当者</th>
					<td>$deliverySupportPIC</td>
				</tr>
				<tr>
					<th>SC-Manager</th>
					<td>$scManager</td>
				</tr>
				<tr>
					<th>SC-Manager担当者</th>
					<td>$scManagerPIC</td>
				</tr>
				<tr>
					<th>遠隔地BK出力</th>
					<td>$remoteBkoutput</td>
				</tr>
				<tr>
					<th>遠隔地BK担当者</th>
					<td>$remoteBkPIC</td>
				</tr>
				<tr>
					<th>内部対応フラグ</th>
					<td>$internalCorrespondenceFlag</td>
				</tr>
				<tr>
					<th>終了フラグ</th>
					<td>$completionFlag</td>
				</tr>
				<tr>
					<th>備考</th>
					<td><h1>$remarks</h1></td>
				</tr>
			</table>
		</div>
DOC_END;
return $smystr;
}
// 20150827 ADD END
//20160218 ADD STA 新規貸出申請時の登録処理見直し
function new_lendmsg_inf($lendmsgid,$mtls) { //資材重複がない場合、資材名以外の入力を促す。
	//改修資材分類選択ドロップダウン
	$mtlstypeHtml = "";
	$mtlstype = array("PG", "設計書", "PG+設計書");
	foreach($mtlstype as $mtlstype){
		$mtlstypeHtml .= "<option value=\"" . $mtlstype . "\">" . $mtlstype . "</option>\n";
	}
	$smystr = <<< DOC_END
		★は入力必須項目
		<form class="form" action="newlendmsginf.php" method="post">
			<fieldset>
				<p class="first">
					<label for="name">申請番号</label>
					<input type="text" name="lendmsgid" class="trbnum" size="30" value=$lendmsgid readonly="readonly">
				</p>
				<p>
					<label for="name">★申請者</label>
					<input type="text" name="lenduser" class="lenduser" size="30">
				</p>
				<p>
					<label for="snum">★関連帳票番号<br />( / 区切り 複数指定可)<br /><br /></label>
					<input type="text" name="trbnum" class="trbnum" size="30">
				</p>
				<p>
					<label for="snum">変更管理番号</label>
					<input type="text" name="cngnum" class="trbnum" size="30">
				</p>
				<p>
					<label for="message">件名</label>
					<textarea name="subject" class="message" cols="30" rows="10"></textarea>
				</p>
				<p>
					<label for="message">★改修資材分類</label>
					<select name="mtlstype">
						<option value="">選択してください</option>
						$mtlstypeHtml
					</select>
				</p>
				<p>
					<label for="message">資材<br />( / 区切り 複数指定可)</label>
					<textarea name="mtls" class="message" cols="30" rows="10" readonly="readonly">$mtls</textarea>
				</p>
				<p>
					<label for="snum">相乗対象番号<br />(資材が競合しているPMPACK番号1件)<br /><br /></label>
					<input type="text" name="sharingTrbnum" class="trbnum" size="30">
				</p>
				<p>
					<label for="message">備考</label>
					<textarea name="remarks" class="message" cols="30" rows="10"></textarea>
				</p>
				<p class="submit"><button type="submit">新規申請</button></p>
			</fieldset>
		</form>
DOC_END;
return $smystr;
}
//20160218 ADD END 新規貸出申請時の登録処理見直し
function get_lendmsg($lendmsgid) { //対象貸出申請書のすべての情報を連想配列で取得する。
	$query = "SELECT * FROM lendmsg WHERE LENDMSGID='$lendmsgid'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=014");
		exit;
	}
	// 20150827 UPD STA
	//$row = mysql_fetch_assoc($result);
	//$args['lendmsgid'] = $row['LENDMSGID'];
	//$args['trbnum'] = $row['trbnum'];
	//$args['lenduser'] = $row['lenduser'];
	//$args['phase'] = $row['phase'];
	//$args['mtls'] = $row['mtls'];
	//$args['reason'] = $row['reason'];
	//$args['contact'] = $row['contact'];
	//$args['schedule'] = $row['schedule'];
	//$args['update'] = $row['update'];
	$row = mysql_fetch_assoc($result);
	$args["lendmsgid"] = $row['LENDMSGID'];
	$args["trbnum"] = $row['trbnum'];
	$args["lenduser"] = $row['lenduser'];
	$args["phase"] = $row['phase'];
	$args["mtls"] = $row['mtls'];
	$args["contact"] = $row['contact'];
	$args["update"] = $row['update'];
	$args["cngnum"] = $row['cngnum'];
	$args["subject"] = $row['subject'];
	$args["mtlstype"] = $row['mtlstype'];
	$args["sharingMtls"] = $row['sharingMtls'];
	$args["sharingTrbnum"] = $row['sharingTrbnum'];
	$args["itReleaseJudgmentPass"] = $row['itReleaseJudgmentPass'];
	$args["itRepoCommit"] = $row['itRepoCommit'];
	$args["itMtls"] = $row['itMtls'];
	$args["itRelease"] = $row['itRelease'];
	$args["itPIC"] = $row['itPIC'];
	$args["stReleaseJudgmentPass"] = $row['stReleaseJudgmentPass'];
	$args["stRepoCommit"] = $row['stRepoCommit'];
	$args["stMtls"] = $row['stMtls'];
	$args["stRelease1"] = $row['stRelease1'];
	$args["stRelease2"] = $row['stRelease2'];
	$args["stRelease3"] = $row['stRelease3'];
	$args["stRelease4"] = $row['stRelease4'];
	$args["stPIC"] = $row['stPIC'];
	$args["shipmentJudgmentPass"] = $row['shipmentJudgmentPass'];
	$args["rtRepoCommit"] = $row['rtRepoCommit'];
	$args["rtMtls"] = $row['rtMtls'];
	$args["mePIC"] = $row['mePIC'];
	$args["productionRepoCommit"] = $row['productionRepoCommit'];
	$args["productionMtls"] = $row['productionMtls'];
	$args["productionRelease"] = $row['productionRelease'];
	$args["productionPIC"] = $row['productionPIC'];
	$args["deliverySupport"] = $row['deliverySupport'];
	$args["deliverySupportPIC"] = $row['deliverySupportPIC'];
	$args["scManager"] = $row['scManager'];
	$args["scManagerPIC"] = $row['scManagerPIC'];
	$args["remoteBkoutput"] = $row['remoteBkoutput'];
	$args["remoteBkPIC"] = $row['remoteBkPIC'];
	$args["internalCorrespondenceFlag"] = $row['internalCorrespondenceFlag'];
	$args["completionFlag"] = $row['completionFlag'];
	$args["remarks"] = $row['remarks'];
	//20150827 UPD END
	return $args;
}
function rls_mtls($mtlstr) { //資材のロックを解除する。
	$mtls = split("/", "$mtlstr");
	foreach ($mtls as $mtl) {
		//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
		$mtl = str_replace("'","''",$mtl);
		//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
		$query = "UPDATE mtl SET `LENDMSGID`='0' WHERE `mtlname`='$mtl'";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=015");
			exit;
		}
	}
}
function rdr_tblmtl_admin($lim,$pagenum) { //admin用インデックスページの貸出資源テーブルを1ページ分書き出す。
	$query = "SELECT mtlname, LENDMSGID FROM mtl WHERE NOT LENDMSGID=0 ORDER BY LENDMSGID DESC";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=016");
		exit;
	}
	$pkey = 1;
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
		$mtlname = $row['mtlname'];
		$lendmsgid = $row['LENDMSGID'];
		$query_n = "SELECT trbnum, lenduser, schedule FROM lendmsg WHERE LENDMSGID='$lendmsgid'";
		$result_n = mysql_query($query_n);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=017");
			exit;
		}
		$row_n = mysql_fetch_assoc($result_n);
		$trbnum = $row_n['trbnum'];
		$lenduser = $row_n['lenduser'];
		$schedule = $row_n['schedule'];
		$tblmtls[$pkey] .= "\t\t\t<tr><th>$mtlname</th><th>$trbnum</th><th>$lendmsgid</th><th>$lenduser</th><th>$schedule</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg_admin.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		if($i == $lim){
			$pkey++;
			$i = 0;
		}
		$i++;
	}
	$tblmtl = $tblmtls[$pagenum];
	return $tblmtl;
}
function rdr_tbllendmsg_admin($lim,$pagenum) { //admin用インデックスページの貸出申請テーブルを1ページ分書き出す。
	$query = "SELECT * FROM lendmsg ORDER BY LENDMSGID DESC";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=018");
		exit;
	}
	$pkey = 1;
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
		$lendmsgid = $row['LENDMSGID'];
		$trbnum = $row['trbnum'];
		$lenduser = $row['lenduser'];
		$phase = $row['phase'];
		$update = $row['update'];
		$tbllendmsgs[$pkey] .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg_admin.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		if($i == $lim){
			$pkey++;
			$i = 0;
		}
		$i++;
	}
	$tbllendmsg = $tbllendmsgs[$pagenum];
	return $tbllendmsg;
}
function rdr_tblallmtl($lim,$pagenum,$srch) { //管理資材ページの管理資源テーブルを1ページ分書き出す。
	//20160212 ADD STA 検索ワードのスペース削除対応
	$srch = trim(mb_convert_kana($srch, "s"));
	//20160212 ADD END 検索ワードのスペース削除対応
	//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
	$srch = str_replace("'","''",$srch);
	//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加
	$query = "SELECT MTLID, mtlname, LENDMSGID, path FROM mtl WHERE mtlname LIKE '".$srch."%'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=019");
		exit;
	}
	$pkey = 1;
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
		$mtlid = $row['MTLID'];
		$mtlname = $row['mtlname'];
		$lendmsgid = $row['LENDMSGID'];
		$path = $row['path'];
		if($lendmsgid == 0){
			$tblmtls[$pkey] .= "\t\t\t<tr><th>$mtlid</th><th>$mtlname</th><th>$path</th><th> - </th></tr>\n";
		}else{
			$tblmtls[$pkey] .= "\t\t\t<tr><th>$mtlid</th><th>$mtlname</th><th>$path</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		}
		if($i == $lim){
			$pkey++;
			$i = 0;
		}
		$i++;
	}
	$tblmtl = $tblmtls[$pagenum];
	return $tblmtl;
}
function rdr_tblallmtlpage($lim,$pagenum) { //管理資材ページの貸出資源テーブル用ページリンク(pager)を書き出す。
	$query = "SELECT mtlname, LENDMSGID FROM mtl";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=020");
		exit;
	}
	$reccnt = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$reccnt++;
	}
	$lastpagenum = ceil($reccnt / $lim);
	$prevpagenum = $pagenum - 1;
	$nextpagenum = $pagenum + 1;
	$readendpnt = $pagenum * $lim;
	$readendpnt = $reccnt - $readendpnt;
	$readstartpnt = $readendpnt + $lim;
	$readendpnt = $readendpnt + 1;
	if(1 > $readendpnt){
		$readendpnt = 1;
	}
	$tbllendmsgpage .= "\t\t<div class=\"pager\">";
	if($prevpagenum != 0){
		$tbllendmsgpage .= "<div class=\"pager_prev\"><a href=\"allmtl.php?allmtl_p=$prevpagenum\">&laquo; Prev</a></div>";
	}else{
		$tbllendmsgpage .= "<div class=\"pager_prev\"></div>";
	}
	$tbllendmsgpage .= "<div class=\"pager_center\">$readstartpnt - $readendpnt of $reccnt</div>";
	if($pagenum != $lastpagenum){
		$tbllendmsgpage .= "<div class=\"pager_next\"><a href=\"allmtl.php?allmtl_p=$nextpagenum\">Next &raquo;</a></div>";
	}else{
		$tbllendmsgpage .= "<div class=\"pager_next\"></div>";
	}
	$tbllendmsgpage .= "</div>";
	if(!$reccnt){
		$tbllendmsgpage = "\t\t<div class=\"pager\">";
		$tbllendmsgpage .= "<div class=\"pager_prev\"></div>";
		$tbllendmsgpage .= "<div class=\"pager_center\">$readstartpnt - $readstartpnt of $reccnt</div>";
		$tbllendmsgpage .= "<div class=\"pager_next\"></div>";
		$tbllendmsgpage .= "</div>";
	}
	return $tbllendmsgpage;
}
function rdr_distinfo() { //資材配布情報を書き出す。
	$fp = fopen("export_and_ftp.log","r");
	while(!feof($fp)){
		$load[] = fgets($fp);
	}
	fclose($fp);
	$logs = array_reverse($load);
	$distinfostr = 0;
	foreach($logs as $log){
		$pos = strpos($log,'ftp');
		if($pos === false){
			$lct++;
		}else{
			if(!$distinfostr){
				$logdate = substr($log,0,8);
				$logtime = substr($log,8,8);
				$distinfostr = "$logdate $logtime 時点の最新資材が各サーバに転送されています。";
				return $distinfostr;
			}
		}
	}
}
function del_lendmsg($lendmsgid) { //貸出申請書を削除する。
	$update = date("Y/m/d H:i");
	$query = "UPDATE lendmsg
		  SET `trbnum`='MissingNum' ,`lenduser`='MissingNum' ,`phase`='MissingNum' ,`mtls`='MissingNum' ,`reason`='MissingNum' ,`contact`='MissingNum' ,
		      `schedule`='MissingNum' ,`update`='$update' ,`svninfo`='MissingNum' ,`cngnum`='MissingNum' ,`subject`='MissingNum' ,`mtlstype`='MissingNum' ,
		      `itReleaseJudgmentPass`='MissingNum' ,`itRepoCommit`='MissingNum' ,`itMtls`='MissingNum' ,`itRelease`='MissingNum' ,`itPIC`='MissingNum' ,
		      `stReleaseJudgmentPass`='MissingNum' ,`stRepoCommit`='MissingNum' ,`stMtls`='MissingNum' ,`stRelease1`='MissingNum' ,`stRelease2`='MissingNum' ,
		      `stRelease3`='MissingNum' ,`stRelease4`='MissingNum' ,`stPIC`='MissingNum' ,`shipmentJudgmentPass`='MissingNum' ,`rtRepoCommit`='MissingNum' ,
		      `rtMtls`='MissingNum' ,`mePIC`='MissingNum' ,`productionRepoCommit`='MissingNum' ,`productionMtls`='MissingNum' ,`productionRelease`='MissingNum' ,
		      `productionPIC`='MissingNum' ,`deliverySupport`='MissingNum' ,`deliverySupportPIC`='MissingNum' ,`scManager`='MissingNum' ,`scManagerPIC`='MissingNum' ,
		      `remoteBkoutput`='MissingNum' ,`remoteBkPIC`='MissingNum' ,`sharingMtls`='MissingNum' ,`sharingTrbnum`='MissingNum' ,`internalCorrespondenceFlag`='MissingNum' ,
		      `completionFlag`='MissingNum' ,`remarks`='MissingNum'
		  WHERE `lendmsgid`='$lendmsgid'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=021");
		exit;
	}
}
function chk_lendmsgexist($lendmsgid) { //貸出申請書の存在を確認する。
	$query = "SELECT LENDMSGID FROM lendmsg WHERE LENDMSGID='$lendmsgid'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=022");
		exit;
	}
	$row = mysql_fetch_assoc($result);
	$return = $row['LENDMSGID'];
	if($return){
		return $return;
	}else{
		return 0;
	}
}
function del_mtl($mtlname) { //管理資材を削除する。
	$query = "UPDATE mtl SET `mtlname`='MissingNum' ,`LENDMSGID`='0' ,`path`='MissingNum' WHERE `mtlname`='$mtlname'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=024");
		exit;
	}
}
function chk_mtlexist($mtlname) { //管理資材の存在を確認する。
	$query = "SELECT mtlname FROM mtl WHERE mtlname='$mtlname'";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=025");
		exit;
	}
	$row = mysql_fetch_assoc($result);
	$return = $row['mtlname'];
	if($return){
		return $return;
	}else{
		return 0;
	}
}
function rdr_errlog() { //エラーログを書き出す。
	$query = "SELECT * FROM err";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=026");
		exit;
	}
	while ($row = mysql_fetch_assoc($result)) {
		$errid = $row['ERRID'];
		$errnum = $row['errnum'];
		$errlog = $row['errlog'];
		$errdate = $row['errdate'];
		$str .= <<< DOC_END
		<div class="summary">
			<table class="summary_tbl_full">
				<tr>
					<th>ERRID</th>
					<td><h1>$errid</h1></td>
				</tr>
				<tr>
					<th>errnum</th>
					<td><h1>$errnum</h1></td>
				</tr>
				<tr>
					<th>errlog</th>
					<td><h1>$errlog</h1></td>
				</tr>
				<tr>
					<th>errdate</th>
					<td><h1>$errdate</h1></td>
				</tr>
			</table>
		</div>

DOC_END;
	}
	return $str;
}
//20150902 DEL STA
//function set_infomsg($infomsg) { //お知らせを設定する。
//	$infodate = date("Y/m/d H:i");
//	$query = "UPDATE infomsg SET `infomsg`='$infomsg', `infodate`='$infodate' WHERE INFOMSGID=1";
//	$result = mysql_query($query);
//	if(mysql_error()){
//		$errlog = mysql_error();
//		header("Location: err.php?errlog=$errlog&errnum=027");
//		exit;
//	}
//}
//function rdr_infomsg() { //お知らせを書き出す。
//	$query = "SELECT * FROM infomsg WHERE INFOMSGID=1";
//	$result = mysql_query($query);
//	if(mysql_error()){
//		$errlog = mysql_error();
//		header("Location: err.php?errlog=$errlog&errnum=028");
//		exit;
//	}
//	$row = mysql_fetch_assoc($result);
//	$infomsg = $row['infomsg'];
//	$infodate = $row['infodate'];
//	$str = "$infodate : $infomsg";
//	return $str;
//}
//20150902 DEL END
function new_mtl($mtlname,$path) { //新規に資材を登録する。
	$query = "INSERT INTO mtl (`mtlname` ,`LENDMSGID` ,`path`)VALUES ('$mtlname', '0', '$path')";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=029");
		exit;
	}
}
function rdr_dlfilelist() { //downloadsのファイルリストを書き出す。
	$res_dir = opendir('file');
	while( $file_name = readdir( $res_dir ) ){
		$i++;
		if($i > 2){
			$str .= "<a href=\"file/$file_name\"><img src=\"images/file.png\" align=\"middle\"> $file_name</a><br /><br />\n\t\t";
		}
	}
	closedir($res_dir);
	return $str;
}
//20151203 UPD STA
//function rdr_search($scstr) { //検索結果を書き出す。csvにファイル出力する。
function rdr_search($scstr,$val) { //検索結果を書き出す。csvにファイル出力する。
//20151203 UPD END
	//20150911 UPD STA
	//$targetrep = TARGETREP;
	$targetrep = MYSQL_DBNAME;
	//20150911 UPD END
	$csvdate = date("Y/m/d H:i");
	//20160128 UPD STA PH3からの申請書項目追加の対応、検索結果list.csvヘッダーの変更
	//$csv = "##### Materials Lending Management System : $csvdate generated. ############################################\n";
	//$csv .= "##### 貸出申請ID | 関連帳票番号 | 申請者 | 申請フェーズ | 資材 | 連絡先 | 返却予定日 | 最終更新日時 | 申請理由 #####\n";
	//$csv .= "##### targetrep = $targetrep #####\n\n";
	$csv = "検索結果,$csvdate generated.\n";
	$csv .= "貸出申請ID,関連帳票番号,[未使用]redmineチケットID,申請者,申請フェーズ,資材,連絡先,[未使用]返却予定日,最終更新日時,[未使用]申請理由,[未使用]svninfo,変更管理番号,件名,改修資材分類,競合資材,相乗対象番号,ITリリース判定通過,ITリポコミット,IT資材,ITリリース,IT担当者,STリリース判定通過,STリポコミット,ST資材,STリリース１,STリリース２,STリリース３,STリリース４,ST担当者,出荷判定通過,RTリポコミット,RT資材,ME担当者,本番リポコミット,本番資材,本番リリース,本番担当者,納品支援,納品支援担当者,SC-Manager,SC-Manager担当者,遠隔地BK出力,遠隔地BK担当者,内部対応フラグ,終了フラグ,備考\n";
	$csv .= "#####,targetrep = $targetrep\n\n";
	//20160128 UPD END PH3からの申請書項目追加の対応、検索結果list.csvヘッダーの変更
	//20160128 UPD STA 貸出申請検索の部分一致検索対応改修
	//$query_a = "SELECT * FROM lendmsg WHERE lenduser='$scstr'";
	$scstr = trim(mb_convert_kana($scstr, "s"));
	$query_a = "SELECT * FROM lendmsg WHERE lenduser LIKE '%".$scstr."%'";
	//20160128 UPD END 貸出申請検索の部分一致検索対応改修
	$result_a = mysql_query($query_a);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=030");
		exit;
	}
	while ($row_a= mysql_fetch_assoc($result_a)) {
		$flg_a++;
		$lendmsgid = $row_a['LENDMSGID'];
		$trbnum = $row_a['trbnum'];
		$lenduser = $row_a['lenduser'];
		$phase = $row_a['phase'];
		$mtls = $row_a['mtls'];
		$contact = $row_a['contact'];
		$schedule = $row_a['schedule'];
		$update = $row_a['update'];
		$reason = $row_a['reason'];
		//20160128 ADD STA PH3からの申請書項目追加の対応
		$cngnum = $row_a['cngnum'];
		$subject = $row_a['subject'];
		$mtlstype = $row_a['mtlstype'];
		$sharingMtls = $row_a['sharingMtls'];
		$sharingTrbnum = $rorow_aw['sharingTrbnum'];
		$itReleaseJudgmentPass = $row_a['itReleaseJudgmentPass'];
		$itRepoCommit = $row_a['itRepoCommit'];
		$itMtls = $row_a['itMtls'];
		$itRelease = $row_a['itRelease'];
		$itPIC = $row_a['itPIC'];
		$stReleaseJudgmentPass = $row_a['stReleaseJudgmentPass'];
		$stRepoCommit = $row_a['stRepoCommit'];
		$stMtls = $row_a['stMtls'];
		$stRelease1 = $row_a['stRelease1'];
		$stRelease2 = $row_a['stRelease2'];
		$stRelease3 = $row_a['stRelease3'];
		$stRelease4 = $row_a['stRelease4'];
		$stPIC = $row_a['stPIC'];
		$shipmentJudgmentPass = $row_a['shipmentJudgmentPass'];
		$rtRepoCommit = $row_a['rtRepoCommit'];
		$rtMtls = $row_a['rtMtls'];
		$mePIC = $row_a['mePIC'];
		$productionRepoCommit = $row_a['productionRepoCommit'];
		$productionMtls = $row_a['productionMtls'];
		$productionRelease = $row_a['productionRelease'];
		$productionPIC = $row_a['productionPIC'];
		$deliverySupport = $row_a['deliverySupport'];
		$deliverySupportPIC = $rorow_aw['deliverySupportPIC'];
		$scManager = $row_a['scManager'];
		$scManagerPIC = $row_a['scManagerPIC'];
		$remoteBkoutput = $row_a['remoteBkoutput'];
		$remoteBkPIC = $row_a['remoteBkPIC'];
		$internalCorrespondenceFlag = $row_a['internalCorrespondenceFlag'];
		$completionFlag = $row_a['completionFlag'];
		$remarks = $row_a['remarks'];
		//20160128 ADD END PH3からの申請書項目追加の対応
		//20151203 UPD STA
		//$tbllendmsgs .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg_admin.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		if($val === "usr"){
			$tbllendmsgs .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		}elseif($val === "adm"){
			$tbllendmsgs .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg_admin.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
		}
		//20151203 UPD END
		//20160128 UPD STA PH3からの申請書項目追加の対応
		//$csv .= "\"$lendmsgid\",\"$trbnum\",\"$lenduser\",\"$phase\",\"$mtls\",\"$contact\",\"$schedule\",\"$update\",\"$reason\"\n";
		$csv .= "\"$lendmsgid\",\"$trbnum\",\"$rd_rdnums\",\"$lenduser\",\"$phase\",\"$mtls\",\"$contact\",\"$schedule\",\"$update\",\"$reason\",\"$svninfo\",\"$cngnum\",\"$subject\",\"$mtlstype\",\"$sharingMtls\",\"$sharingTrbnum\",\"$itReleaseJudgmentPass\",\"$itRepoCommit\",\"$itMtls\",\"$itRelease\",\"$itPIC\",\"$stReleaseJudgmentPass\",\"$stRepoCommit\",\"$stMtls\",\"$stRelease1\",\"$stRelease2\",\"$stRelease3\",\"$stRelease4\",\"$stPIC\",\"$shipmentJudgmentPass\",\"$rtRepoCommit\",\"$rtMtls\",\"$mePIC\",\"$productionRepoCommit\",\"$productionMtls\",\"$productionRelease\",\"$productionPIC\",\"$deliverySupport\",\"$deliverySupportPIC\",\"$scManager\",\"$scManagerPIC\",\"$remoteBkoutput\",\"$remoteBkPIC\",\"$internalCorrespondenceFlag\",\"$completionFlag\",\"$remarks\"\n";
		//20160128 UPD END PH3からの申請書項目追加の対応
	}
	if(!$flg_a){
		//20160128 UPD STA 貸出申請検索の部分一致検索対応改修
		//$query_b = "SELECT * FROM lendmsg WHERE trbnum='$scstr'";
		$query_b = "SELECT * FROM lendmsg WHERE trbnum LIKE '%".$scstr."%'";
		//20160128 UPD END 貸出申請検索の部分一致検索対応改修
		$result_b = mysql_query($query_b);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=031");
			exit;
		}
		while ($row_b= mysql_fetch_assoc($result_b)) {
			$flg_b++;
			$lendmsgid = $row_b['LENDMSGID'];
			$trbnum = $row_b['trbnum'];
			$lenduser = $row_b['lenduser'];
			$phase = $row_b['phase'];
			$mtls = $row_b['mtls'];
			$contact = $row_b['contact'];
			$schedule = $row_b['schedule'];
			$update = $row_b['update'];
			$reason = $row_b['reason'];
			//20160128 ADD STA PH3からの申請書項目追加の対応
			$cngnum = $row_b['cngnum'];
			$subject = $row_b['subject'];
			$mtlstype = $row_b['mtlstype'];
			$sharingMtls = $row_b['sharingMtls'];
			$sharingTrbnum = $row_b['sharingTrbnum'];
			$itReleaseJudgmentPass = $row_b['itReleaseJudgmentPass'];
			$itRepoCommit = $row_b['itRepoCommit'];
			$itMtls = $row_b['itMtls'];
			$itRelease = $row_b['itRelease'];
			$itPIC = $row_b['itPIC'];
			$stReleaseJudgmentPass = $row_b['stReleaseJudgmentPass'];
			$stRepoCommit = $row_b['stRepoCommit'];
			$stMtls = $row_b['stMtls'];
			$stRelease1 = $row_b['stRelease1'];
			$stRelease2 = $row_b['stRelease2'];
			$stRelease3 = $row_b['stRelease3'];
			$stRelease4 = $row_b['stRelease4'];
			$stPIC = $row_b['stPIC'];
			$shipmentJudgmentPass = $row_b['shipmentJudgmentPass'];
			$rtRepoCommit = $row_b['rtRepoCommit'];
			$rtMtls = $row_b['rtMtls'];
			$mePIC = $row_b['mePIC'];
			$productionRepoCommit = $row_b['productionRepoCommit'];
			$productionMtls = $row_b['productionMtls'];
			$productionRelease = $row_b['productionRelease'];
			$productionPIC = $row_b['productionPIC'];
			$deliverySupport = $row_b['deliverySupport'];
			$deliverySupportPIC = $row_b['deliverySupportPIC'];
			$scManager = $row_b['scManager'];
			$scManagerPIC = $row_b['scManagerPIC'];
			$remoteBkoutput = $row_b['remoteBkoutput'];
			$remoteBkPIC = $row_b['remoteBkPIC'];
			$internalCorrespondenceFlag = $row_b['internalCorrespondenceFlag'];
			$completionFlag = $row_b['completionFlag'];
			$remarks = $row_b['remarks'];
			//20160128 ADD END PH3からの申請書項目追加の対応
			//20151203 UPD STA
			//$tbllendmsgs .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg_admin.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
			if($val === "usr"){
				$tbllendmsgs .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
			}elseif($val === "adm"){
				$tbllendmsgs .= "\t\t\t<tr><th>$lendmsgid</th><th>$trbnum</th><th>$lenduser</th><th>$phase</th><th>$update</th><th><a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"貸出申請書\" href=\"lendmsg_admin.php?lendmsgid=$lendmsgid\"><img src=\"images/form_message.gif\"></a></th></tr>\n";
			}
			//20160128 ADD STA PH3からの申請書項目追加の対応
			//$csv .= "\"$lendmsgid\",\"$trbnum\",\"$lenduser\",\"$phase\",\"$mtls\",\"$contact\",\"$schedule\",\"$update\",\"$reason\"\n";
			$csv .= "\"$lendmsgid\",\"$trbnum\",\"$rd_rdnums\",\"$lenduser\",\"$phase\",\"$mtls\",\"$contact\",\"$schedule\",\"$update\",\"$reason\",\"$svninfo\",\"$cngnum\",\"$subject\",\"$mtlstype\",\"$sharingMtls\",\"$sharingTrbnum\",\"$itReleaseJudgmentPass\",\"$itRepoCommit\",\"$itMtls\",\"$itRelease\",\"$itPIC\",\"$stReleaseJudgmentPass\",\"$stRepoCommit\",\"$stMtls\",\"$stRelease1\",\"$stRelease2\",\"$stRelease3\",\"$stRelease4\",\"$stPIC\",\"$shipmentJudgmentPass\",\"$rtRepoCommit\",\"$rtMtls\",\"$mePIC\",\"$productionRepoCommit\",\"$productionMtls\",\"$productionRelease\",\"$productionPIC\",\"$deliverySupport\",\"$deliverySupportPIC\",\"$scManager\",\"$scManagerPIC\",\"$remoteBkoutput\",\"$remoteBkPIC\",\"$internalCorrespondenceFlag\",\"$completionFlag\",\"$remarks\"\n";
			//20160128 ADD END PH3からの申請書項目追加の対応
		}
		if(!$flg_b){
			return false;
		}
	}
	$fp = fopen("list_search.csv","w");
	fwrite($fp,$csv);
	fclose($fp);
	//20160125 ADD STA CSV出力したファイルの文字コードをS-JISに変換する
	exec("cmd.exe /c nkf.exe -sxLwc list_search.csv > list_search_SJIS.csv");
	unlink("./list_search.csv");
	rename("list_search_SJIS.csv","./list_search.csv");
	//20160125 ADD END CSV出力したファイルの文字コードをS-JISに変換する
	return $tbllendmsgs;
}
function rdr_allcsv() { //貸出申請すべてのcsvファイルを出力する。
	$repurl = REPURL;
	$cmd = "sudo /usr/bin/svn info $repurl";
	exec($cmd,$rsl);
	$nowrev = $rsl[4];
	//20150911 UPD STA
	//$targetrep = TARGETREP;
	$targetrep = MYSQL_DBNAME;
	//20150911 UPD END
	$csvdate = date("Y/m/d H:i");
	//20151125 UPD STA list.csvヘッダーの変更
	//$csv = "##### Materials Lending Management System : $csvdate generated. ##########################################################################\n";
	//$csv .= "##### 貸出申請ID | 関連帳票番号 | redmineチケットID | 申請者 | 申請フェーズ | 資材 | 連絡先 | 返却予定日 | 最終更新日時 | 申請理由 | svninfo #####\n";
	//$csv .= "##### targetrep = $targetrep ($nowrev) #####\n\n";
	$csv = "全貸出申請,$csvdate generated.\n";
	$csv .= "貸出申請ID,関連帳票番号,[未使用]redmineチケットID,申請者,申請フェーズ,資材,連絡先,[未使用]返却予定日,最終更新日時,[未使用]申請理由,[未使用]svninfo,変更管理番号,件名,改修資材分類,競合資材,相乗対象番号,ITリリース判定通過,ITリポコミット,IT資材,ITリリース,IT担当者,STリリース判定通過,STリポコミット,ST資材,STリリース１,STリリース２,STリリース３,STリリース４,ST担当者,出荷判定通過,RTリポコミット,RT資材,ME担当者,本番リポコミット,本番資材,本番リリース,本番担当者,納品支援,納品支援担当者,SC-Manager,SC-Manager担当者,遠隔地BK出力,遠隔地BK担当者,内部対応フラグ,終了フラグ,備考\n";
	$csv .= "#####,targetrep = $targetrep\n\n";
	//20151125 UPD END list.csvヘッダーの変更
	$query = "SELECT * FROM lendmsg";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=032");
		exit;
	}
	while ($row = mysql_fetch_assoc($result)) {
		$lendmsgid = $row['LENDMSGID'];
		$trbnum = $row['trbnum'];
		$lenduser = $row['lenduser'];
		$phase = $row['phase'];
		$mtls = $row['mtls'];
		$contact = $row['contact'];
		$schedule = $row['schedule'];
		$update = $row['update'];
		$reason = $row['reason'];
		$svninfo = $row['svninfo'];
		//20151125 ADD STA
		$cngnum = $row['cngnum'];
		$subject = $row['subject'];
		$mtlstype = $row['mtlstype'];
		$sharingMtls = $row['sharingMtls'];
		$sharingTrbnum = $row['sharingTrbnum'];
		$itReleaseJudgmentPass = $row['itReleaseJudgmentPass'];
		$itRepoCommit = $row['itRepoCommit'];
		$itMtls = $row['itMtls'];
		$itRelease = $row['itRelease'];
		$itPIC = $row['itPIC'];
		$stReleaseJudgmentPass = $row['stReleaseJudgmentPass'];
		$stRepoCommit = $row['stRepoCommit'];
		$stMtls = $row['stMtls'];
		$stRelease1 = $row['stRelease1'];
		$stRelease2 = $row['stRelease2'];
		$stRelease3 = $row['stRelease3'];
		$stRelease4 = $row['stRelease4'];
		$stPIC = $row['stPIC'];
		$shipmentJudgmentPass = $row['shipmentJudgmentPass'];
		$rtRepoCommit = $row['rtRepoCommit'];
		$rtMtls = $row['rtMtls'];
		$mePIC = $row['mePIC'];
		$productionRepoCommit = $row['productionRepoCommit'];
		$productionMtls = $row['productionMtls'];
		$productionRelease = $row['productionRelease'];
		$productionPIC = $row['productionPIC'];
		$deliverySupport = $row['deliverySupport'];
		$deliverySupportPIC = $row['deliverySupportPIC'];
		$scManager = $row['scManager'];
		$scManagerPIC = $row['scManagerPIC'];
		$remoteBkoutput = $row['remoteBkoutput'];
		$remoteBkPIC = $row['remoteBkPIC'];
		$internalCorrespondenceFlag = $row['internalCorrespondenceFlag'];
		$completionFlag = $row['completionFlag'];
		$remarks = $row['remarks'];
		//20151125 ADD END
		$rd_link = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PW);
//		mysql_select_db('redmine',$rd_link);
//		if(mysql_error()){
//			$errlog = mysql_error();
//			header("Location: err.php?errlog=$errlog&errnum=035");
//			exit;
//		}
//		$rd_trbnums = split("/", "$trbnum");
//		foreach ($rd_trbnums as $rd_trbnum) {
//			$rd_query = "SELECT id FROM issues WHERE subject='$rd_trbnum'";
//			$rd_result = mysql_query($rd_query);
//			if(mysql_error()){
//				$errlog = mysql_error();
//				header("Location: err.php?errlog=$errlog&errnum=036");
//				exit;
//			}
//			$rd_row = mysql_fetch_assoc($rd_result);
//			$nowrd = $rd_row['id'];
//			$rd_rdnums .= $nowrd;
//			$rd_rdnums .= "/";
//		}
//20150902 UPD STA
//        $rd_rdnums .= "9999";
        $rd_rdnums .= "";
//20150902 UPD END
		$rd_rdnums = rtrim($rd_rdnums, "/");
//20151125 UPD STA
//		$csv .= "\"$lendmsgid\",\"$trbnum\",\"$rd_rdnums\",\"$lenduser\",\"$phase\",\"$mtls\",\"$contact\",\"$schedule\",\"$update\",\"$reason\",\"$svninfo\"\n";
		$csv .= "\"$lendmsgid\",\"$trbnum\",\"$rd_rdnums\",\"$lenduser\",\"$phase\",\"$mtls\",\"$contact\",\"$schedule\",\"$update\",\"$reason\",\"$svninfo\",\"$cngnum\",\"$subject\",\"$mtlstype\",\"$sharingMtls\",\"$sharingTrbnum\",\"$itReleaseJudgmentPass\",\"$itRepoCommit\",\"$itMtls\",\"$itRelease\",\"$itPIC\",\"$stReleaseJudgmentPass\",\"$stRepoCommit\",\"$stMtls\",\"$stRelease1\",\"$stRelease2\",\"$stRelease3\",\"$stRelease4\",\"$stPIC\",\"$shipmentJudgmentPass\",\"$rtRepoCommit\",\"$rtMtls\",\"$mePIC\",\"$productionRepoCommit\",\"$productionMtls\",\"$productionRelease\",\"$productionPIC\",\"$deliverySupport\",\"$deliverySupportPIC\",\"$scManager\",\"$scManagerPIC\",\"$remoteBkoutput\",\"$remoteBkPIC\",\"$internalCorrespondenceFlag\",\"$completionFlag\",\"$remarks\"\n";
//20151125 UPD END
		$rd_rdnums = "";
	}
	$fp = fopen("list.csv","w");
	fwrite($fp,$csv);
	fclose($fp);
//20160125 ADD STA CSV出力したファイルの文字コードをS-JISに変換する
	exec("cmd.exe /c nkf.exe -sxLwc list.csv > list_SJIS.csv");
	unlink("./list.csv");
	rename("list_SJIS.csv","./list.csv");
//20160125 ADD END CSV出力したファイルの文字コードをS-JISに変換する
	mysql_close($rd_link);
}
//20150828 DEL STA
//function get_rdnums($trbnum){ //関連帳票番号からredmineのチケット番号を取得する。
////	$link = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PW);
////	mysql_select_db('redmine',$link);
////	if(mysql_error()){
////		$errlog = mysql_error();
////		header("Location: err.php?errlog=$errlog&errnum=035");
////		exit;
////	}
////	$trbnums = split("/", "$trbnum");
////	foreach ($trbnums as $trbnum) {
////		$query = "SELECT id FROM issues WHERE subject='$trbnum'";
////		$result = mysql_query($query);
////		if(mysql_error()){
////			$errlog = mysql_error();
////			header("Location: err.php?errlog=$errlog&errnum=036");
////			exit;
////		}
////		$row = mysql_fetch_assoc($result);
//		$rdnums[0] = "9999";
////	}
////	mysql_close($link);
//	return $rdnums;
//}
//20150828 DEL END
function set_log($logmsg,$logtype){ //ログをmysqlに設定する。
	$logip = $_SERVER["REMOTE_ADDR"];
	$logdate = date("Y/m/d H:i");
	if($logtype == "access"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', 'access index.php')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=037");
			exit;
		}
	}else if($logtype == "phasechg"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', '$logmsg')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=038");
			exit;
		}
	}else if($logtype == "newlendmsg"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', '$logmsg')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=039");
			exit;
		}
	}else if($logtype == "csv"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', 'create new list.csv')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=040");
			exit;
		}
	}else if($logtype == "admin"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', '$logmsg')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=041");
			exit;
		}
	//20160218 ADD STA 新規貸出申請時の登録処理見直し
	}else if($logtype == "newlendmsginf"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', '$logmsg')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=042");
			exit;
		}
	//20160218 ADD END 新規貸出申請時の登録処理見直し
	//20160222 ADD STA 貸出申請更新時のログメッセージ表示
	}else if($logtype == "updlendmsg"){
		$query = "INSERT INTO log (`logip` ,`logtype` ,`logdate` ,`logmsg`)VALUES ('$logip', '$logtype', '$logdate', '$logmsg')";
		$result = mysql_query($query);
		if(mysql_error()){
			$errlog = mysql_error();
			header("Location: err.php?errlog=$errlog&errnum=043");
			exit;
		}
	//20160218 ADD END 貸出申請更新時のログメッセージ表示
	}
}
function rdr_log() { //ログを書き出す。
	$query = "SELECT * FROM log";
	$result = mysql_query($query);
	if(mysql_error()){
		$errlog = mysql_error();
		header("Location: err.php?errlog=$errlog&errnum=042");
		exit;
	}
	while ($row = mysql_fetch_assoc($result)) {
		$logid = $row['LOGID'];
		$logip = $row['logip'];
		$logtype = $row['logtype'];
		$logdate = $row['logdate'];
		$logmsg = $row['logmsg'];
		$log .= "\t\t\t<tr><th>$logid</th><th>$logip</th><th>$logtype</th><th>$logdate</th><th>$logmsg</th></tr>\n";
	}
	return $log;
}
//20150831 DEL STA
//function set_svninfo($lendmsgid,$repurl) {  //svninfoをmysqlに設定する。
//	$query = "SELECT mtls FROM lendmsg WHERE LENDMSGID='$lendmsgid'";
//	$result = mysql_query($query);
//	if(mysql_error()){
//		$errlog = mysql_error();
//		header("Location: err.php?errlog=$errlog&errnum=043");
//		exit;
//	}
//	$row = mysql_fetch_assoc($result);
//	$mtlstr = $row['mtls'];
//	$mtls = split("/", "$mtlstr");
//	foreach ($mtls as $mtlname) {
//		$query = "SELECT path FROM mtl WHERE mtlname='$mtlname'";
//		$result = mysql_query($query);
//		if(mysql_error()){
//			$errlog = mysql_error();
//			header("Location: err.php?errlog=$errlog&errnum=044");
//			exit;
//		}
//		$row = mysql_fetch_assoc($result);
//		$path = $row['path'];
//		$mtlname = urlencode($mtlname);
//		$fullurl = $repurl.$path.$mtlname;
//		$cmd = "sudo /usr/bin/svn info $fullurl";
//		exec($cmd,$rsl);
//		$nowrev = $rsl[5]; // no use.
//		$lastrev = $rsl[8];
//		$lastdate = $rsl[9];
//		$lastrev = substr($lastrev,18);
//		$lastdate = substr($lastdate,19,20);
//		$retstr .= "$lastdate"."(".$lastrev.")"."/";
//		$rsl = array();
//	}
//	$retstr = rtrim($retstr, "/");
//	$query = "UPDATE lendmsg SET `svninfo`='$retstr' WHERE `lendmsgid`='$lendmsgid'";
//	$result = mysql_query($query);
//	if(mysql_error()){
//		$errlog = mysql_error();
//		header("Location: err.php?errlog=$errlog&errnum=045");
//		exit;
//	}
//}
//20150831 DEL END
?>
