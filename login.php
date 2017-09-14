<?php
////////////
//login.php
////////////

//includes.
include_once "conf.php";
include_once "func.php";

//set mlmname and target svn rep.
$mlmname = MLMNAME;

//20150911 UPD STA
//$targetrep = TARGETREP;
$targetrep = MYSQL_DBNAME;
//20150911 UPD END

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
</head>
<body>
<div class="header">
	<div class="logo"><a href = "/$mlmname/">admin login :: Rep = $targetrep</a></div>
</div>
<div class="item">
	<div class="title">admin login.</div>
	<div class="article">
		<form action="bglogin.php" method="post">
			Please add a admin password. 
			<input class="login" type="password" name="password" size="30">
			<input class="login_hdn" type="submit" value=" ">
		</form>
	</div>
</div>
</body>
</html>
DOC_END;
?>
