<?php
//////////
//help.php
//////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;
$targetrep = TARGETREP;

//connect mysql.
mysqlconnect();

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
	<div class="logo"><a href = "/$mlmname/mlm :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">help.</div>
	<div class="article">
		マニュアルのダウンロード<br /><br />
		<a href="file/mlm_outline_100122.pdf"><img src="images/pdf.png" align="middle">資材貸出管理システム 概要 (2010.01.22)</a><br /><br />
		<a href="file/mlm_manual_100122.pdf"><img src="images/pdf.png" align="middle">資材貸出管理システム マニュアル(ユーザ偏) (2010.01.22)</a><br /><br />
		<a href="file/mlm_manual_admin_100123.pdf"><img src="images/pdf.png" align="middle">資材貸出管理システム マニュアル(管理者偏) (2010.01.22)</a><br /><br />
		<a href="file/mlm_specs_100123.pdf"><img src="images/pdf.png" align="middle">資材貸出管理システム システム設計 (2010.01.22)</a><br /><br />
		<a href="file/mlm_src_100122.zip"><img src="images/zip.png" align="middle">資材貸出管理システム source archive (2010.01.22)</a><br /><br />
	</div>
</div>
</body>
</html>
DOC_END;
?>
