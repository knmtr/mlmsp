-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2010 年 3 月 30 日 10:36
-- サーバのバージョン: 5.0.22
-- PHP のバージョン: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `mlm`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `err`
--

CREATE TABLE IF NOT EXISTS `err` (
  `ERRID` int(11) NOT NULL auto_increment,
  `errnum` text collate utf8_unicode_ci NOT NULL,
  `errlog` text collate utf8_unicode_ci NOT NULL,
  `errdate` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ERRID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `err`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `infomsg`
--

CREATE TABLE IF NOT EXISTS `infomsg` (
  `INFOMSGID` int(11) NOT NULL auto_increment,
  `infomsg` text collate utf8_unicode_ci NOT NULL,
  `infodate` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`INFOMSGID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `infomsg`
--

INSERT INTO `infomsg` (`INFOMSGID`, `infomsg`, `infodate`) VALUES
(1, 'mlm system init.', '');

-- --------------------------------------------------------

--
-- テーブルの構造 `lendmsg`
--

CREATE TABLE IF NOT EXISTS `lendmsg` (
  `LENDMSGID` int(11) NOT NULL auto_increment,
  `trbnum` text collate utf8_unicode_ci NOT NULL,
  `lenduser` text collate utf8_unicode_ci NOT NULL,
  `phase` text collate utf8_unicode_ci NOT NULL,
  `mtls` text collate utf8_unicode_ci NOT NULL,
  `reason` text collate utf8_unicode_ci NOT NULL,
  `contact` text collate utf8_unicode_ci NOT NULL,
  `schedule` text collate utf8_unicode_ci NOT NULL,
  `update` text collate utf8_unicode_ci NOT NULL,
  `svninfo` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`LENDMSGID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `lendmsg`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `LOGID` int(11) NOT NULL auto_increment,
  `logip` text collate utf8_unicode_ci NOT NULL,
  `logtype` text collate utf8_unicode_ci NOT NULL,
  `logdate` text collate utf8_unicode_ci NOT NULL,
  `logmsg` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`LOGID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `log`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `mtl`
--

CREATE TABLE IF NOT EXISTS `mtl` (
  `MTLID` int(11) NOT NULL auto_increment,
  `mtlname` text collate utf8_unicode_ci NOT NULL,
  `LENDMSGID` int(11) NOT NULL,
  `path` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`MTLID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `mtl`
--

