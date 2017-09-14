<?php
/////////////
//search.php
/////////////

//includes.
include_once "conf.php";
include_once "func.php";

//connect mysql.
mysqlconnect();

//set mlmname and target svn rep.
$mlmname = MLMNAME;
//20150911 UPD STA
//$targetrep = TARGETREP;
$targetrep = MYSQL_DBNAME;
//20150911 UPD END

//get search rsl and rdrstrs.
$scstr = $_POST["scstr"];
//20151221 ADD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定
$val = $_POST["gmn_chk"];
//20151221 ADD END 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定
$scrsl = rdr_search($scstr,$val);
if($scrsl == false){
	$csvstr = "\t\t<br /><br />検索条件を満たす貸出申請を見つけることが出来ませんでした。 <br /><br />\n";
}else{
	//20160208 UPD STA 検索結果のcsv出力メッセージパターン変更
	//$csvstr = "\t\t<div class=\"download\"><a href=\"list.csv\"><img src=\"images/csv.png\" align=\"middle\"> 検索結果を.csvとしてダウンロードする.</a></div>\n";
	$csvstr = "\t\t<br /><br />検索結果を.csvとして<a href = \"list_search.csv\">ダウンロードする.</a> <br /><br />\n";	
	//20160208 UPD END 検索結果のcsv出力メッセージパターン変更
}
//20151204 ADD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定
if($val === "usr"){
	$header = "<div class=\"logo\"><a href = \"/$mlmname/\">mlm :: Rep = $targetrep</a></div>";
	$back = "<div class=\"button\"><a href=\"/$mlmname/\">戻る</a></div>";
}elseif($val === "adm"){
	$header = "<div class=\"logo\"><a href = \"/$mlmname/admin.php\">admin login :: Rep = $targetrep</a></div>";
	$back = "<div class=\"button\"><a href=\"/$mlmname/admin.php\">戻る</a></div>";
}
//20151204 ADD END 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定

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
<!--20151221 UPD STA-->
<!--	<div class="logo"><a href = "/$mlmname/">admin login :: Rep = $targetrep</a></div>-->
$header
<!--20151221 UPD STA-->
</div>
<div class="item">
	<div class="title">貸出申請検索.</div>
	<div class="article">
		<!--20160208 UPD STA 改行削除-->
		<!--キーワード: $scstr の検索結果. <br /><br />-->
		キーワード: $scstr の検索結果.
		<!--20160208 UPD END 改行削除-->
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="70px">申請番号</th><th width="100px">関連帳票番号</th><th width="100px">申請者</th><th width="80px">申請フェーズ</th><th width="150px">最終更新日時</th><th width="70px">貸出申請書</th></tr>
		</thead>
		<tbody>
$scrsl
<!--20160208 ADD STA 検索結果による文言出力制御-->
$csvstr
<!--20160208 ADD END 検索結果による文言出力制御-->
		</tbody>
		</table>
<!--20151221 UPD STA-->
<!--		<div class="button"><a href="/$mlmname/">戻る</a></div>-->
$back
<!--20151221 UPD STA-->
	</div>
</div>
</body>
</html>
DOC_END;
?>
