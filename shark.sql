-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: 2015 年 3 月 01 日 22:25
-- サーバのバージョン： 5.5.34-log
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shark`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `conversation`
--

CREATE TABLE `conversation` (
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `fav_material`
--

CREATE TABLE `fav_material` (
  `user_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `fav_mate_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `fav_record`
--

CREATE TABLE `fav_record` (
  `user_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `fav_reco_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `follow`
--

CREATE TABLE `follow` (
  `user_id` int(11) NOT NULL,
  `follow_id` int(11) NOT NULL,
  `follow_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `information`
--

CREATE TABLE `information` (
  `user_id` int(11) NOT NULL,
  `other_id` int(11) NOT NULL,
  `read_check` int(1) NOT NULL,
  `info_status` int(2) NOT NULL,
  `info_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `instrument`
--

CREATE TABLE `instrument` (
  `instrument_id` int(11) NOT NULL,
  `instrument_name` varchar(15) NOT NULL,
  `instrument_image` varchar(256)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `material`
--

CREATE TABLE `material` (
  `material_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `instrument_id` int(11) NOT NULL,
  `submit_date` datetime DEFAULT NULL,
  `material_comment` varchar(100) DEFAULT NULL,
  `material_path` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` varchar(200) NOT NULL,
  `is_read` int(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `profile`
--

CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `sex` int(1) DEFAULT NULL,
  `introduction` varchar(100) DEFAULT NULL,
  `follow_count` int(3) NOT NULL,
  `follower_count` int(3) NOT NULL,
  `pro_image` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `profile`
--

INSERT INTO `profile` (`user_id`, `sex`, `introduction`, `follow_count`, `follower_count`) VALUES
(2, 0, '管理者です。', 0, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `record`
--

CREATE TABLE `record` (
  `record_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `record_data`
--

CREATE TABLE `record_data` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `record_name` varchar(50) NOT NULL,
  `record_date` datetime DEFAULT NULL,
  `record_comment` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(16) NOT NULL,
  `password` char(64) NOT NULL,
  `name` varchar(16) NOT NULL,
  `mail_address` varchar(256) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `password`, `name`, `mail_address`, `created`) VALUES
(2, 'root', 'e191c9cbe15e3fce5a0f3cb0a6f1041a1204888a6908ae86897efb3070519814', 'root', 'root@example.com', '2015-03-01 22:24:21');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
