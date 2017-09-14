<?php
//////////////
//bglogin.php
//////////////

//includes.
include_once "conf.php";

//set mlmname.
$mlmname = MLMNAME;

//password check.
$password = $_POST["password"];

if($password == ADMINPW){

	// set cookie.
	$timeout = time() + 30 * 86400;
	setcookie($mlmname,'logined',$timeout,"/$mlmname/",DOMAIN);
print <<< DOC_END
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="0;URL=/$mlmname/admin.php">
</head>
DOC_END;
}else{
print <<< DOC_END
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="0;URL=/$mlmname/index.php">
</head>
DOC_END;
}
?>