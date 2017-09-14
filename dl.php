<?php
////////
//dl.php
////////

//includes.
include_once "conf.php";
include_once "func.php";

//connect mysql.
mysqlconnect();

//get htmlcodes.
$str = rdr_dlfilelist();

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
	body, .title { background: url(images/bg.png) };
</style>
</script>
</head>
<body>
<div class="item">
	<div class="title">downloads.</div>
	<div class="article">
		$str
	</div>
</div>
</body>
</html>
DOC_END;
?>
