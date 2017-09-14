<?php
/////////////
//allcsv.php
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

//ganerate all csv file.
rdr_allcsv();
$csvstr = "\t\t<div class=\"download\"><a href=\"list.csv\"><img src=\"images/csv.png\" align=\"middle\"> すべての貸出申請を.csvとしてダウンロードする.</a></div>\n";

//20151214 ADD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定
$val = $_GET["csvgmn_chk"];
if($val === "usr"){
	$header = "<div class=\"logo\"><a href = \"/$mlmname/\">mlm :: Rep = $targetrep</a></div>";
	$back = "<div class=\"button\"><a href=\"/$mlmname/\">戻る</a></div>";
}elseif($val === "adm"){
	$header = "<div class=\"logo\"><a href = \"/$mlmname/admin.php\">admin login :: Rep = $targetrep</a></div>";
	$back = "<div class=\"button\"><a href=\"/$mlmname/admin.php\">戻る</a></div>";
}
//20151214 ADD END 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定

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
<!--20151202 UPD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
<!--	<div class="logo"><a href = "/$mlmname/">mlm :: Rep = $targetrep</a></div>-->
$header
<!--20151202 UPD END 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
</div>
<div class="item">
	<div class="title">貸出申請.csv出力.</div>
	<div class="article">
$csvstr
<!--20151202 UPD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
<!--		<div class="button"><a href="/$mlmname/">戻る</a></div>-->
$back
<!--20151202 UPD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
	</div>
</div>
</body>
</html>
DOC_END;
?>
