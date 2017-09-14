<?php
//////////
//log.php
//////////

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

//get htmlcodes.
$log = rdr_log();

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
</script>
</head>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/">mlm :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">作業証跡log.</div>
	<div class="article">
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="50px">logid</th><th width="100px">IP</th><th width="100px">logtype</th><th width="120px">date</th><th width="200px">logmsg</th></tr>
		</thead>
		<tbody>
$log
		</tbody>
		</table>
	</div>
</div>
</div>
</body>
</html>
DOC_END;
?>
