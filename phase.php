<?php
////////////
//phase.php
////////////

//includes.
include_once "conf.php";
include_once "func.php";

//connect mysql.
mysqlconnect();

//set mlmname.
$mlmname = MLMNAME;

//set repurl.
$repurl = REPURL;

//get phase.
$phase = $_GET["phase"];
$lendmsgid = $_GET["lendmsgid"];
$chk = $_GET["chk"];

if(!$chk){
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
<!-- load sortable -->
<script src="js/sorttable.js" type="text/javascript"></script>
</head>
<body>
<div class="item">
	<div class="title">[確認] フェーズの移行.</div>
	<div class="article">
		本当によろしいですか? <br /><br />
		<div class="button"><a href="/$mlmname/phase.php?phase=$phase&lendmsgid=$lendmsgid&chk=1">はい</a></div>
		<div class="button">いいえ</div>
	</div>
</div>
</body>
</html>
DOC_END;
}else if($chk == 1){
if($phase == 1){
	$phasestr = '返却可';
}else if($phase == 2){
	$phasestr = '反映完';
}

//logging.
$logmsg = "change lendmsg phase  phase = $phasestr lendmsgid = $lendmsgid";
set_log($logmsg,'phasechg');

//release mtl locks.
$args = get_lendmsg($lendmsgid);
$mtlstr = $args['mtls'];
set_phase($lendmsgid,$phasestr);
if($phasestr == '反映完'){
	//mtls release.
	rls_mtls($mtlstr);
	//capture svninfo to mysql.
	//20150831 DEL STA
	//set_svninfo($lendmsgid,$repurl);
	//20150831 DEL END
}

//make full summary
$args = get_lendmsg($lendmsgid);
$trbnum = $args['trbnum'];
$lenduser = $args['lenduser'];
$phase = $args['phase'];
$mtls = $args['mtls'];
$reason = $args['reason'];
$contact = $args['contact'];
$schedule = $args['schedule'];
$update = $args['update'];
$smystr = rdr_summary_full($lendmsgid,$trbnum,$lenduser,$phase,$mtls,$reason,$contact,$schedule,$update);

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
<!-- load sortable -->
<script src="js/sorttable.js" type="text/javascript"></script>
</head>
<body>
<div class="item">
	<div class="title">フェーズの移行.</div>
	<div class="article">
$smystr
		<div class="info_sc">フェーズを $phasestr に移行しました。</div>
	</div>
</div>
</body>
</html>
DOC_END;
}
?>
