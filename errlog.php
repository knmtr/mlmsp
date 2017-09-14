<?php
/////////////
//errlog.php
/////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname.
$mlmname = MLMNAME;

//connect mysql.
mysqlconnect();

//get errlog.
$str = rdr_errlog();

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
	<div class="title">errlog.</div>
	<div class="article">
$str
	</div>
</div>
</body>
</html>
DOC_END;
?>
