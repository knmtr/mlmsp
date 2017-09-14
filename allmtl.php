<?php
/////////////
//allmtl.php
/////////////

//includes.
include_once "conf.php";
include_once "func.php";

//20151208 DEL STA index.phpに検索窓を追加。allmtlには検索結果のみ表示する改修。
//$srch = $_REQUEST['srch'];
//if($srch==""){
//	$srch="Zn";
//}
//20151208 DEL END index.phpに検索窓を追加。allmtlには検索結果のみ表示する改修。

//set mlmname and target svn rep.
$mlmname = MLMNAME;
//20150911 UPD STA
//$targetrep = TARGETREP;
$targetrep = MYSQL_DBNAME;
//20150911 UPD END

//connect mysql.
mysqlconnect();

//get pager property.
$allmtl_p = $_GET["allmtl_p"];
if(!$allmtl_p){
	$allmtl_p = 1;
}

// define limit for allmtl page.
$lim = 100000;

//20151208 ADD STA index.phpに資材名検索窓を追加。
//get search rsl and rdrstrs.
$srch = $_REQUEST['srch'];
$val = $_POST["srchgmn_chk"];
if(!$val){
	$val = "usr";
}
//20151208 ADD END index.phpに資材名検索窓を追加。
//20151204 ADD STA 前画面の情報を基に、ヘッダーのリンクを設定
if($val === "usr"){
	$header = "<div class=\"logo\"><a href = \"/$mlmname/\">mlm :: Rep = $targetrep</a></div>";
}elseif($val === "adm"){
	$header = "<div class=\"logo\"><a href = \"/$mlmname/admin.php\">admin login :: Rep = $targetrep</a></div>";
}
//20151204 ADD END 前画面の情報を基に、ヘッダーのリンクを設定


//get htmlcodes.
$tblallmtl = rdr_tblallmtl($lim,$allmtl_p,$srch);
$tblallmtlpage = rdr_tblallmtlpage($lim,$allmtl_p);

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

<style type="text/css">
	.title { background: url(images/bg.png) };
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

<form name="main" action="allmtl.php">
<!--20151210 UPD STA 属性の記載順変更-->
<!--<font color=white>資材名検索(基本的に前方一致)：</font><input type="text" name="srch" value=$srch style="background-color:#00FFFF"/>-->
<font color=white>資材名検索(基本的に前方一致)：</font><input type="text" name="srch" style="background-color:#00FFFF" value=$srch>
<!--20151210 UPD END 属性の記載順変更-->
<input type="submit" value="検索">
<br> <font color=white>※ _、%で部分一致検索できます。（普通のSQLみたいな感じで）</font>
</form>

<body>
<div class="header">
<!--20151204 UPD STA-->
<!--	<div class="logo"><a href = "/$mlmname/">mlm :: Rep = $targetrep</a></div>-->
$header
<!--20151204 UPD END-->
</div>
<div class="itemsrch">
	<div class="title">管理対象資材一覧.</div>
	<div class="article">
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="30px">資材ID</th><th width="50px">資材名</th><th width="50px">path</th><th width="30px">貸出申請書</th></tr>
		</thead>
		<tbody>
$tblallmtl
		</tbody>
		</table>
<!-- $tblallmtlpage -->
	</div>
	</div>
</div>
</body>
</html>
DOC_END;
?>
