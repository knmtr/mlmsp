<?php
/////////////
//newmtl.php
/////////////

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

//get mtlname.
//20120403 「[」,「]」,「.」への対応　tanaka
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
//20160126 ADD STA 新規資材格納パスを「/」区切りで登録できるよう処理を追加
$path = $_POST["path"];
//20160126 ADD END 新規資材格納パスを「/」区切りで登録できるよう処理を追加
//20160224 ADD STA '（シングルクォート）を含む資材名に対応できるよう処理を追加
$path = str_replace("'","''",$path);
//20160224 ADD END '（シングルクォート）を含む資材名に対応できるよう処理を追加

//20160217 ADD STA 新規管理資材登録必須項目入力チェック
//新規管理資材登録必須項目入力チェック
$chk_newmtl = chk_newmtl($lenduser,$trbnum,$mtls,$path);
//20160217 ADD END 新規管理資材登録必須項目入力チェック
//20160126 ADD STA 新規資材格納パスを「/」区切りで登録できるよう処理を追加
if($chk_newmtl){//新規管理資材登録必須項目入力チェックがエラーの場合、その内容を表示する。
	$str .= $chk_newmtl;
	$errflg ++;
}else{
	$mtlnames = split("/", "$mtls");
	foreach ($mtlnames as $mtlname) {
		$mtlname = trim($mtlname);
		if(!chk_mtlexist($mtlname)){
			$mtlname=str_replace("[","!",$mtlname);
			$mtlname=str_replace("]","?",$mtlname);
			$mtlname=str_replace(".","@",$mtlname);
			//20160219 ADD STA 半角スペースを含むユニークファイル名の登録処理追加
			$mtlname=preg_replace("/\s/","~",$mtlname);
			//20160219 ADD END 半角スペースを含むユニークファイル名の登録処理追加
			$chk_mtls[] = $mtlname;
		}else{
			$str .= "\t\t資材名=$mtlname は既に存在しています。<br />\n";
			$errflg ++;
		}
	}
	if(!$errflg){
		$mtlpaths = split("/", "$path");
		foreach ($mtlpaths as $mtlpath) {
			$mtlpath = trim($mtlpath);
			$chk_path[] = $mtlpath;
		}
		//新規資材名数と資材格納パス数が一致しない場合、エラーを返す。
		$mtlsNum = count($chk_mtls);
		$pathNum = count($chk_path);
		if($mtlsNum > $pathNum){
			$str .= "\t\t新規資材名数に対して、資材格納パス数が少ないです。<br />\n";
			$errflg ++;		
			
		}elseif($mtlsNum < $pathNum){
			$str .= "\t\t資材格納パス数に対して、新規資材名数が少ないです。<br />\n";
			$errflg ++;		
		}
	}
}
//20160126 ADD END 新規資材格納パスを「/」区切りで登録できるよう処理を追加

if($errflg){
	$str .= "\t\t<div class=\"info_err\">上記理由により、新規管理資材申請を棄却します。再度、入力項目をご確認ください。</div>";
}else{
	$str .= "\t\t新規管理資材名の「@」は「.」、「!」は「[」、「?」は「]」、「~」は「 （半角空白）」、「''」は「'」を意味します。<br />\n";
	//20160126 UPD STA 新規資材格納パスを「/」区切りで登録できるよう処理を追加
	//$str .= "\t\t新規管理資材のSVN上での格納パスを入力してください。<br />\n";
	$str .= "\t\t新規管理資材のSVN上での格納パスが表示されていることを確認してください。<br />\n";
	//20160126 UPD END 新規資材格納パスを「/」区切りで登録できるよう処理を追加
	$str .= "\t\t<form class=\"form\" action=\"newmtlreg.php\" method=\"post\">\n";
	$str .= "\t\t\t<table class=\"newmtl_tbl\">\n";
	$str .= "\t\t\t\t<tr>\n";
	$str .= "\t\t\t\t\t<th width=\"180px\">新規資材名</th>\n";
	$str .= "\t\t\t\t\t<th width=\"300px\">資材格納パス</th>\n";
	$str .= "\t\t\t\t</tr>\n";
	//20160126 UPD STA 新規資材格納パスを「/」区切りで登録できるよう処理を修正
	//foreach ($chk_mtls as $chk_mtlname) {
	//	$str .= "\t\t\t\t<tr>\n";
	//	$str .= "\t\t\t\t\t<td width=\"180px\">$chk_mtlname</td>\n";
	//	$str .= "\t\t\t\t\t<td><input class=\"trbnum\" type=\"text\" name=\"$chk_mtlname\" size=\"70\"></td>\n";
	//	$str .= "\t\t\t\t</tr>\n";
	//}
	while ((list(, $chk_mtlname) = each($chk_mtls)) && (list(, $chk_mtlpath) = each($chk_path))){
		$str .= "\t\t\t\t<tr>\n";
		$str .= "\t\t\t\t\t<td width=\"180px\">$chk_mtlname</td>\n";
		$str .= "\t\t\t\t\t<td><input class=\"trbnum\" type=\"text\" name=\"$chk_mtlname\" value=\"$chk_mtlpath\" size=\"70\" readonly=\"readonly\"></td>\n";
		$str .= "\t\t\t\t</tr>\n";
	}
	//20160126 UPD END 新規資材格納パスを「/」区切りで登録できるよう処理を追加
	$str .= "\t\t\t</table>\n";
	$str .= "\t\t\t<input type=\"hidden\" name=\"hdndata[lenduser]\" value=\"$lenduser\">\n";
	$str .= "\t\t\t<input type=\"hidden\" name=\"hdndata[reason]\" value=\"$reason\">\n";
	$str .= "\t\t\t<input type=\"hidden\" name=\"hdndata[contact]\" value=\"$contact\">\n";
	$str .= "\t\t\t<input type=\"hidden\" name=\"hdndata[trbnum]\" value=\"$trbnum\">\n";
	$str .= "\t\t\t<input type=\"hidden\" name=\"hdndata[mtls]\" value=\"$mtls\">\n";
	$str .= "\t\t\t<button type=\"submit\" style=\"margin-left: 100px; margin-top: 10px;\">実行</button>\n";
	$str .= "\t\t</form>\n";
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
