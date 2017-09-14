<?php
////////////
//index.php
////////////

///////////////////////////////////////////////////////
//Materials Lending Management System v0.1 01.05.2010//
///////////////////////////////////////////////////////

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

//get pager property.
$msg_p = $_GET["msg_p"];
if(!$msg_p){
	$msg_p = 1;
}
//20150828 DEL STA
//$mtl_p = $_GET["mtl_p"];
//if(!$mtl_p){
//	$mtl_p = 1;
//}
//20150828 DEL END

//copy now ftp log.
// `cplog.sh`;

//20150828 DEL STA		
////get redmine url.
//$redmineurl = REDMINEURL;
//20150828 DEL END

//get htmlcodes.
//20150828 DEL STA
//$dllink = "helpは、<a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"downloads.\" href=\"/$mlmname/dl.php\">こちら</a>";
//$redmineatom = "redmineへのリンクは、<a href=\"$redmineurl\">こちら</a>";
//$infomsg = rdr_infomsg();
//20150828 DEL END
$tbllendmsg = rdr_tbllendmsg(COLLIM,$msg_p);
$tbllendmsgpage = rdr_tbllendmsgpage(COLLIM,$msg_p);
//20150828 DEL STA
//$tblmtl = rdr_tblmtl(COLLIM,$mtl_p);
//$tblmtlpage = rdr_tblmtlpage(COLLIM,$mtl_p);
//$loglink = "作業証跡logへのリンクは、<a href=\"log.php\">こちら</a>";
//$pjweblink = "pjwebのSVN掲示板へは<a href=\"https://pjshr136.soln.jp/ai2544QG/pjwebroot/index.jsp\">こちら</a><br />(BBS -> SVN構成管理関連 総合Q&A)<br />\t\tbbsが見れない方は<a href=\"bbs.php\">こちら</a>";
//20150828 DEL END

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
<!-- load jquery and ui -->
<link rel="stylesheet" type="text/css" href="js/jquery/themes/base/ui.all.css">
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery/ui.datepicker-ja.js"></script>
<script type="text/javascript">
$(function() {
	$("#datepicker").datepicker();
});
</script>
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
	<!--20150911 課題21 UPD STA-->
	<!--<div class="logo"><a href = "/$mlmname/">mlm :: Rep = $targetrep</a></div>-->
	<div class="logo"><a>Materials Lending Management System :: 　targetrep⇒$targetrep</a></div>
	<!--20150911 課題21 UPD END-->
</div>
<!--20150824 DEL STA
<div class="item">
	<div class="title">お知らせ.</div>
	<div class="article">
		$infomsg<br /><br /><br />
		$dllink<br /><br />
		$redmineatom<br /><br />
		$loglink<br /><br />
		$pjweblink<br /><br />
	</div>
</div>
20150824 DEL END-->
<div class="item">
	<div class="title">貸出申請検索.</div>
	<div class="article">
		<form action="search.php" method="post">
			検索したい貸出申請のキーワード(申請者 or 関連帳票番号)を入力してください。
			<input class="login" type="text" name="scstr" size="30">
<!--20151221 UPD STA-->
			<!--<input class="login_hdn" type="submit" value=" "><br /><br />-->
			<input class="login_hdn" type="hidden" name="gmn_chk" value="usr"><br /><br />
<!--20151221 UPD END-->

		</form>
	</div>
</div>
<!--20151218 DEL STA レイアウト変更に伴う削除
<div class="item">
	<div class="title">貸出申請情報.</div>
	<div class="article">
		貸出申請すべての.csv出力については、<a href = "allcsv.php" target="_blank">こちら</a><br /><br />
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="70px">申請番号</th><th width="100px">関連帳票番号</th><th width="100px">申請者</th><th width="80px">申請フェーズ</th><th width="150px">最終更新日時</th><th width="70px">貸出申請書</th></tr>
		</thead>
		<tbody>
$tbllendmsg
		</tbody>
		</table>
$tbllendmsgpage
	</div>
</div>
20151218 DEL END レイアウト変更に伴う削除-->
<div class="item">
	<div class="title">管理対象資材名検索.</div>
	<div class="article">
<!--20151208 UPD STA 管理対象資材名検索窓をindex.phpに追加-->
		管理対象資材一覧については、<a href = "allmtl.php" target="_blank">こちら。</a><br /><br />
		<form action="allmtl.php" method="post">
			または、検索したい管理対象資材名を入力してください。
			<input class="login" type="text" name="srch" size="30">
			<input class="login_hdn" type="hidden" name="srchgmn_chk" value="usr"><br /><br />
		</form>
<!--20151208 UPD END 管理対象資材名検索窓をindex.phpに追加-->
<!--20150824 DEL STA
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="200px">資材名</th><th width="100px">関連帳票番号</th><th width="70px">申請番号</th><th width="100px">申請者</th><th width="100px">返却予定日</th><th width="70px">貸出申請書</th></tr>
		</thead>
		<tbody>
$tblmtl
		</tbody>
		</table>
$tblmtlpage
20150824 DEL END -->

	</div>
</div>
<div class="item">
	<div class="title">新規貸出申請.</div>
	<div class="article">
		<!--20160217 ADD STR 新規貸出申請時の入力必須項目明確化-->
		★は入力必須項目
		<!--20160217 ADD END 新規貸出申請時の入力必須項目明確化-->
		<form class="form" action="newlendmsg.php" method="post">
			<fieldset>
<!-- 20150819 DEL STR
				<p class="first">
					<label for="name">申請者</label>
					<input type="text" name="lenduser" class="lenduser" size="30">
				</p>
				<p>
					<label for="contact">連絡先</label>
					<input type="text" name="contact" class="contact" size="30">
				</p>
				<p>
					<label for="time">返却予定日</label>
					<input type="text" id="datepicker" class="schedule" name="schedule" size="30">
				</p>
				<p>
					<label for="snum">関連帳票番号<br />( / 区切り 複数指定可)<br /><br /></label>
					<input type="text" name="trbnum" class="trbnum" size="30">
				</p>
				<p>
					<label for="message">資材<br />( / 区切り 複数指定可)</label>
					<textarea name="mtls" class="message" cols="30" rows="10"></textarea>
				</p>
				<p>
					<label for="message">申請理由<br />(省略可)</label>
					<textarea name="reason" class="message" cols="30" rows="10"></textarea>
				</p>
20150819 DEL END-->
<!--20160218 ADD STR 新規貸出申請時の登録処理見直し-->
				<p class="first">
					<label for="message">★資材<br />( / 区切り 複数指定可)</label>
					<textarea name="mtls" class="message" cols="30" rows="10"></textarea>
				</p>
<!--20160218 ADD END 新規貸出申請時の登録処理見直し-->
				<p class="submit"><button type="submit">新規申請</button></p>
			</fieldset>
		</form>
		<br />
	</div>
</div>
<div class="item">
	<div class="title">貸出申請情報.</div>
	<div class="article">
<!--20151214 ADD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
<!--		貸出申請すべての.csv出力については、<a href = "allcsv.php" target="_blank">こちら</a><br /><br />-->
		貸出申請すべての.csv出力については、<a href = "allcsv.php?csvgmn_chk=usr">こちら</a><br /><br />
<!--20151214 ADD END 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="70px">申請番号</th><th width="100px">関連帳票番号</th><th width="100px">申請者</th><th width="80px">申請フェーズ</th><th width="150px">最終更新日時</th><th width="70px">貸出申請書</th></tr>
		</thead>
		<tbody>
$tbllendmsg
		</tbody>
		</table>
$tbllendmsgpage
	</div>
</div>
<!-- 20150824 DEL STA
<div class="item">
	<div class="title">SVN構成管理窓口について.</div>
	<div class="article">
		連絡先: localhost@localhost.jp (内線: XXX)<br /><br />
		<a href = "login.php">:: admin login ::</a>
	</div>
</div>
20150824 DEL END -->
<!-- 20150824 ADD STA -->
<div class="item">
	<div class="title">管理画面.</div>
	<div class="article">
		<a href = "login.php">:: admin login ::</a>
	</div>
</div>
<!-- 20150824 ADD END -->
</body>
</html>
DOC_END;
?>
