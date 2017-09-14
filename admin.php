<?php
////////////
//admin.php
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
//20150828 DEL STA
//$redmineurl = REDMINEURL;
//20150828 DEL END

//connect mysql.
mysqlconnect();

//still login check.
$cookie = $_COOKIE["$mlmname"];

if($cookie != 'logined'){
	header("Location: /$mlmname/");
	exit;
}

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

//define limit for admin page.
$lim = 100;

//get htmlcodes.
$tbllendmsg = rdr_tbllendmsg_admin($lim,$msg_p);
$tbllendmsgpage = rdr_tbllendmsgpage($lim,$msg_p);
//20150828 DEL STA
//$tblmtl = rdr_tblmtl_admin($lim,$mtl_p);
//$tblmtlpage = rdr_tblmtlpage($lim,$mtl_p);
//20150828 DEL END

//mlm link.
$mlmlink = "通常のmlmへのリンクは、<a href=\"/$mlmname/\" target=\"_blank\">こちら</a><br /><br />";
//20160210 UPD STA phpMyAdminへのリンク変更
//$mlmlink .= "phpMyAdminへのリンクは、<a href=\"../phpMyAdmin/\" target=\"_blank\">こちら</a><br /><br />";
$mlmlink .= "phpMyAdminへのリンクは、<a href=\"../phpmyadmin/\" target=\"_blank\">こちら</a><br /><br />";
//20160210 UPD END phpMyAdminへのリンク変更
$mlmlink .= "errlogへのリンクは、<a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"errlog.\" href=\"errlog.php\">こちら</a><br /><br />";
//20150828 DEL STA
//$mlmlink .= "helpは、<a rel=\"shadowbox;width=500;height=700\" class=\"option\" title=\"downloads.\" href=\"dl.php\">こちら</a><br /><br />";
//$mlmlink .= "redmineへのリンクは、<a href=\"$redmineurl\">こちら</a><br /><br />";
//20150828 DEL END
$mlmlink .= "作業証跡logへのリンクは、<a href=\"log.php\">こちら</a><br /><br />";
//20150828 DEL STA
//$mlmlink .= "pjwebのSVN掲示板へは<a href=\"https://pjshr136.soln.jp/ai2544QG/pjwebroot/index.jsp\">こちら</a><br />(BBS -> SVN構成管理関連 総合Q&A)<br />\t\tbbsが見れない方は<a href=\"bbs.php\">こちら</a>";
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
	body, .title { background: url(images/bg_admin.png) };
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
	<!--20150911 課題21 UPD STA-->
	<!--<div class="logo"><a href = "/$mlmname/admin.php">admin login :: Rep = $targetrep</a></div>-->
	<div class="logo"><a>admin login :: Rep = $targetrep</a></div>
	<!--20150911 課題21 UPD END-->
</div>
<div class="item">
	<div class="title">admin info.</div>
	<div class="article">
		$mlmlink<br /><br />
	</div>
</div>
<!--20150909 ADD STA-->
<div class="item">
	<div class="title">貸出申請検索.</div>
	<div class="article">
		<form action="search.php" method="post">
			検索したい貸出申請のキーワード(申請者 or 関連帳票番号)を入力してください。
			<input class="login" type="text" name="scstr" size="30">
			<input class="login_hdn" type="hidden" name="gmn_chk" value="adm"><br /><br />
		</form>
	</div>
</div>
<!--20150909 ADD END-->
<!--20151214 ADD 管理対象資材名検索窓をadmin.phpに追加-->
<div class="item">
	<div class="title">貸出資材情報.</div>
	<div class="article">
		<form action="allmtl.php" method="post">
			検索したい管理対象資材名を入力してください。
			<input class="login" type="text" name="srch" size="30">
			<input class="login_hdn" type="hidden" name="srchgmn_chk" value="adm"><br /><br />
		</form>
	</div>
</div>
<!--201501214 ADD 管理対象資材名検索窓をadmin.phpに追加-->
<div class="item">
	<div class="title">貸出申請情報.</div>
	<div class="article">
<!--20151214 ADD STA 前画面の情報を基に、ヘッダー、戻るボタンのリンクを設定-->
<!--		貸出申請すべての.csv出力については、<a href = "allcsv.php target="_blank">こちら</a><br /><br />-->
		貸出申請すべての.csv出力については、<a href = "allcsv.php?csvgmn_chk=adm">こちら</a><br /><br />
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
<!--20150828 DEL STA
<div class="item">
	<div class="title">貸出資材情報.</div>
	<div class="article">
		<table class="sortable" cellspacing="0;">
		<thead>
		<tr><th width="200px">資材名</th><th width="100px">関連帳票番号</th><th width="70px">申請番号</th><th width="100px">申請者</th><th width="100px">返却予定日</th><th width="70px">貸出申請書</th></tr>
		</thead>
		<tbody>
$tblmtl
		</tbody>
		</table>
$tblmtlpage
	</div>
</div>
20150828 DEL END-->
<!--20150828 DEL STA
<div class="item">
	<div class="title">貸出申請書の削除.</div>
	<div class="article">
		<form action="dellendmsg.php" method="post">
			削除したい貸出申請書の貸出申請番号を入力してください。
			<input class="login" type="text" name="lendmsgid" size="5">
			<input class="login_hdn" type="submit" value=" ">
		</form>
	</div>
</div>
20150828 DEL END-->
<div class="item">
	<div class="title">新規管理資材登録.</div>
	<div class="article">
		新規に貸出システムDBに登録したい資材情報を入力してください。（★は入力必須項目）
		<form class="form" action="newmtl.php" method="post">
			<fieldset>
				<p class="first">
					<label for="name">★申請者</label>
					<input type="text" name="lenduser" class="lenduser" size="30">
				</p>
				<p>
					<label for="contact">連絡先</label>
					<input type="text" name="contact" class="contact" size="30">
				</p>
				<p>
					<label for="snum">★関連帳票番号<br />( / 区切り 複数指定可)<br /><br /></label>
					<input type="text" name="trbnum" class="trbnum" size="30">
				</p>
				<p>
					<label for="message">★新規資材名<br />( / 区切り 複数指定可)<br /><br /></label>
					<textarea name="mtls" class="message" cols="30" rows="10"></textarea>
				</p>
				<!--20160126 ADD STA 新規資材格納パスを「/」区切りで登録できるよう処理を追加-->
				<p>
					<label for="message">★資材格納パス<br />( / 区切り 複数指定可)<br /><br /></label>
					<textarea name="path" class="message" cols="30" rows="10"></textarea>
				</p>
				<!--20160126 ADD END 新規資材格納パスを「/」区切りで登録できるよう処理を追加-->
				<p>
					<!--20160229 UPD STA 項目名変更-->
					<!--<label for="message">申請理由<br />(省略可)</label>-->
					<label for="message">備考<br />(省略可)</label>
					<!--20160229 UPD END 項目名変更-->
					<textarea name="reason" class="message" cols="30" rows="10"></textarea>
				</p>
				<p class="submit"><button type="submit">実行</button></p>
			</fieldset>
		</form>
		<br />
	</div>
</div>
<div class="itemdel">
	<div class="title">管理資材の削除.</div>
	<div class="article">
		貸出システムDBから削除したい資材を入力してください。（★は入力必須項目）
		<form class="form" action="delmtl.php" method="post">
			<fieldset>
				<p class="first">
					<label for="name">★申請者</label>
					<input type="text" name="lenduser" class="lenduser" size="30">
				</p>
				<p>
					<label for="contact">連絡先</label>
					<input type="text" name="contact" class="contact" size="30">
				</p>
				<p>
					<label for="snum">★関連帳票番号<br />( / 区切り 複数指定可)<br /><br /></label>
					<input type="text" name="trbnum" class="trbnum" size="30">
				</p>
				<p>
					<label for="message">★削除資材名<br />( / 区切り 複数指定可)<br /><br /></label>
					<textarea name="mtls" class="message" cols="30" rows="10"></textarea>
				</p>
				<p>
					<!--20160229 UPD STA 項目名変更-->
					<!--<label for="message">申請理由<br />(省略可)</label>-->
					<label for="message">備考<br />(省略可)</label>
					<!--20160229 UPD END 項目名変更-->
					<textarea name="reason" class="message" cols="30" rows="10"></textarea>
				</p>
				<p class="submit"><button type="submit">実行</button></p>
			</fieldset>
		</form>
		<br />
	</div>
</div>
<!--20150828 DEL STA
<div class="item">
	<div class="title">お知らせ情報登録.</div>
	<div class="article">
		<form action="newinfomsg.php" method="post">
			表示させたいお知らせ情報を入力してください。<br /><br />
			<textarea class="infomsg" name="infomsg"></textarea>
			<input type="submit" value="submit"><br /><br />
		</form>
	</div>
</div>
<div class="item">
	<div class="title">データベースの初期化.</div>
	<div class="article">
		貸出システムDBを初期化し、新規にシステムを構成したい場合は、<a rel="shadowbox;width=500;height=700" class="option" title="dbinit." href="dbinit.php">こちら</a>をクリックしてください。
	</div>
</div>
<div class="item">
	<div class="title">貸出申請書の削除.</div>
	<div class="article">
		<form action="dellendmsg.php" method="post">
			削除したい貸出申請書の貸出申請番号を入力してください。
			<input class="login" type="text" name="lendmsgid" size="5">
			<input class="login_hdn" type="submit" value=" ">
		</form>
	</div>
</div>
20150828 DEL END-->
</body>
</html>
DOC_END;
?>
