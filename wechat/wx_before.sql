-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 11 月 18 日 10:15
-- 服务器版本: 5.6.21
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `wx`
--

-- --------------------------------------------------------

--
-- 表的结构 `we_check`
--

CREATE TABLE IF NOT EXISTS `we_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` varchar(64) NOT NULL,
  `lumps` int(2) NOT NULL COMMENT '肿块',
  `pain` int(2) NOT NULL COMMENT '疼痛',
  `over_flow` int(2) NOT NULL COMMENT '乳头溢液',
  `b_other` int(2) NOT NULL COMMENT '其他',
  `unusual` int(2) NOT NULL COMMENT '有没有异样',
  `create_time` int(11) NOT NULL COMMENT '自检时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_group`
--
CREATE TABLE IF NOT EXISTS `we_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth_id` int(10) unsigned NOT NULL,
  `id` varchar(10) NOT NULL,
  `name` varchar(256) DEFAULT NULL COMMENT '组名',
  `count` varchar(10) DEFAULT NULL COMMENT '组数量',
  PRIMARY KEY (`gid`),
  KEY `auth_id` (`auth_id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_harm`
--

CREATE TABLE IF NOT EXISTS `we_harm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age` int(3) NOT NULL,
  `heights` double NOT NULL,
  `weights` double NOT NULL,
  `if_harm` int(2) NOT NULL,
  `bear_age` int(3) NOT NULL,
  `habits` varchar(64) NOT NULL,
  `problem` varchar(64) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_info`
--

CREATE TABLE IF NOT EXISTS `we_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` varchar(64) NOT NULL,
  `come_date` int(11) NOT NULL COMMENT '大姨妈时间',
  `cycle` int(11) NOT NULL COMMENT '周期',
  `stay_date` int(11) NOT NULL COMMENT '停留时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `we_info`
--

INSERT INTO `we_info` (`id`, `open_id`, `come_date`, `cycle`, `stay_date`) VALUES
(5, '123', 1446998400, 26, 6),
(6, '123', 1446998400, 28, 8),
(7, '123', 1446998400, 0, 0),
(8, '123', 1446998400, 27, 7),
(9, '123', 1446998400, 26, 8),
(10, '123', 1446998400, 27, 7),
(11, '123', 1446998400, 28, 7),
(12, '123', 1446998400, 27, 7),
(13, '123', 1446998400, 26, 8),
(14, '123', 1446998400, 26, 7),
(15, '123', 1446998400, 26, 7),
(16, '123', 1446998400, 26, 8),
(17, '123', 1446998400, 27, 7),
(18, '123', 1446998400, 0, 0),
(19, '123', 1446998400, 0, 0),
(20, '123', 1446998400, 0, 0),
(21, '123', 1446998400, 0, 0),
(22, '123', 1446998400, 0, 0),
(23, '123', 1446998400, 0, 0),
(24, '123', 1446998400, 0, 0),
(25, '123', 1446998400, 0, 0),
(26, '123', 1446998400, 0, 0),
(27, '123', 1446998400, 0, 0),
(28, '123', 1446998400, 25, 0),
(29, '123', 1446998400, 0, 0),
(30, '123', 1446998400, 0, 0),
(31, '123', 1446998400, 0, 0),
(32, '123', 1446998400, 0, 0),
(33, '123', 1446998400, 25, 5),
(34, '123', 1446998400, 26, 7),
(35, '123', 1446998400, 0, 0),
(36, '123', 1446998400, 26, 7),
(37, '123', 1446998400, 27, 8),
(38, '123', 1446998400, 26, 6);

-- --------------------------------------------------------

--
-- 表的结构 `we_manager`
--

CREATE TABLE IF NOT EXISTS `we_manager` (
  `manager_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `if_show` int(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`manager_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `we_manager`
--

INSERT INTO `we_manager` (`manager_id`, `name`, `email`, `password`, `if_show`, `create_time`) VALUES
(1, 'root', 'root', '63a9f0ea7bb98050796b649e85481845', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `we_mass`
--

CREATE TABLE IF NOT EXISTS `we_mass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `auth_id` int(10) unsigned DEFAULT NULL,
  `seltype` varchar(255) DEFAULT NULL,
  `tousername` varchar(256) DEFAULT NULL COMMENT '微信公共号',
  `fromusername` varchar(256) DEFAULT NULL COMMENT '群发未知字段',
  `msgid` bigint(20) unsigned DEFAULT NULL COMMENT '消息ID',
  `msgtype` varchar(256) DEFAULT NULL COMMENT '消息类型',
  `event` varchar(256) DEFAULT NULL COMMENT '事件',
  `status` varchar(256) DEFAULT NULL COMMENT '群发状态',
  `totalcount` int(10) DEFAULT '0' COMMENT '群发总数',
  `filtercount` int(10) DEFAULT '0' COMMENT '过滤数',
  `sentcount` int(10) DEFAULT '0' COMMENT '成功接收数',
  `errorcount` int(10) DEFAULT '0' COMMENT '失败数',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '日志创建时间',
  PRIMARY KEY (`id`),
  KEY `mass_idx_authid` (`auth_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_material`
--

CREATE TABLE IF NOT EXISTS `we_material` (
  `title` varchar(512) NOT NULL,
  `thumb_media_id` varchar(512) NOT NULL,
  `author` varchar(16) NOT NULL,
  `digest` varchar(512) NOT NULL,
  `show_cover_pic` int(2) NOT NULL DEFAULT '0',
  `content` varchar(1024) NOT NULL,
  `content_source_url` varchar(512) NOT NULL,
  `media_id` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `we_menu`
--

CREATE TABLE IF NOT EXISTS `we_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `manager_id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `content` varchar(128) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `create_time` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `we_menu`
--

INSERT INTO `we_menu` (`menu_id`, `manager_id`, `name`, `content`, `parent_id`, `create_time`, `sort`) VALUES
(1, 1, 'test', 'cefhost.cn/', 0, 1444444743, 1);

-- --------------------------------------------------------

--
-- 表的结构 `we_options`
--

CREATE TABLE IF NOT EXISTS `we_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(32) NOT NULL,
  `meta_name` text NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_temp`
--

CREATE TABLE IF NOT EXISTS `we_temp` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `temp_key` varchar(64) NOT NULL,
  `temp_title` varchar(64) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`temp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `we_temp`
--

INSERT INTO `we_temp` (`temp_id`, `temp_key`, `temp_title`, `create_time`) VALUES
(4, 'gDkEZqI31rxLggKU7vZSDXzISJyVMQDLqC5wgDea72w', 'test', 1444525146);

-- --------------------------------------------------------

--
-- 表的结构 `we_temp_log`
--

CREATE TABLE IF NOT EXISTS `we_temp_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_name` varchar(64) NOT NULL,
  `from_name` varchar(128) NOT NULL,
  `create_time` int(11) NOT NULL,
  `msg_type` varchar(32) NOT NULL,
  `event` varchar(32) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `status` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_user`
--

CREATE TABLE IF NOT EXISTS `we_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `openid` varchar(200) DEFAULT NULL COMMENT '微信用户唯一标识',
  `nickname` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(32) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `we_user`
--

INSERT INTO `we_user` (`user_id`, `openid`, `nickname`, `password`, `email`, `create_time`) VALUES
(21, 'oTOuEuBR4TOKQ-gXpUNAHZ_N70eI', 'Zrq', '', '', 0),
(22, 'oTOuEuH73p8SMPfmz5GGLJUBxLLM', 'Lin', '', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
