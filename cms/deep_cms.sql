-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 04 日 06:50
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `deep_cms`
--

-- --------------------------------------------------------

--
-- 表的结构 `de_article`
--

CREATE TABLE IF NOT EXISTS `de_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `summary` text NOT NULL,
  `if_show` int(2) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `default_image` varchar(64) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `de_article`
--

INSERT INTO `de_article` (`article_id`, `category_id`, `title`, `summary`, `if_show`, `content`, `create_time`, `default_image`) VALUES
(1, 4, '测试文章', '测试文章测试文章测试文章测试文章测试文章测试文章测试文章', 1, '测试文章测试文章测试文章测试文章测试文章测试文章', 1449484044, '1449484044_974217309.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `de_category`
--

CREATE TABLE IF NOT EXISTS `de_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(64) NOT NULL COMMENT '分类名字',
  `if_show` int(2) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `sort` int(11) NOT NULL COMMENT '排序',
  `num` int(11) NOT NULL COMMENT '文章数',
  `parent` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `de_category`
--

INSERT INTO `de_category` (`category_id`, `name`, `if_show`, `slug`, `sort`, `num`, `parent`, `create_time`) VALUES
(4, '新闻动态', 1, 'news', 255, 0, 0, 1449367830),
(5, '行业动态', 1, 'in ', 88, 0, 4, 1451881176);

-- --------------------------------------------------------

--
-- 表的结构 `de_column`
--

CREATE TABLE IF NOT EXISTS `de_column` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目id',
  `name` varchar(32) NOT NULL COMMENT '栏目名字',
  `slug` varchar(16) NOT NULL,
  `if_show` int(2) NOT NULL COMMENT '是否导航显示',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `de_column`
--

INSERT INTO `de_column` (`column_id`, `name`, `slug`, `if_show`, `create_time`) VALUES
(5, '关于我们', '', 1, 1449369190),
(6, '加入我们', '', 1, 1449369197),
(7, '公司简介', 'introduce', 1, 1451875797);

-- --------------------------------------------------------

--
-- 表的结构 `de_column_content`
--

CREATE TABLE IF NOT EXISTS `de_column_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `de_column_content`
--

INSERT INTO `de_column_content` (`id`, `column_id`, `title`, `summary`, `content`) VALUES
(3, 3, '天天天天天天444444444', '天天天天天天天天天天', '天天天天天天天天天天肉肉肉肉肉肉'),
(4, 5, '4444444444444444444444', 'admin@admin.comadmin@admin.comadmin@admin.comadmin@admin.coma', '的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒的范德萨范德萨发的撒旦撒'),
(5, 6, 'admin@admin.comadmin@admin.comadmin@admin.com', 'admin@admin.comadmin@admin.comadmin@admin.comadmin@admin.comadmin@admin.com', '聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜聚光科技国际公法毒黄瓜');

-- --------------------------------------------------------

--
-- 表的结构 `de_links`
--

CREATE TABLE IF NOT EXISTS `de_links` (
  `links_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`links_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `de_links`
--

INSERT INTO `de_links` (`links_id`, `name`, `url`, `create_time`) VALUES
(1, 'test', 'http://www.kancloud.cn/', 1451881528);

-- --------------------------------------------------------

--
-- 表的结构 `de_manager`
--

CREATE TABLE IF NOT EXISTS `de_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `de_manager`
--

INSERT INTO `de_manager` (`id`, `email`, `password`, `create_time`) VALUES
(1, 'admin@admin.com', 'e10adc3949ba59abbe56e057f20f883e', 140655642);

-- --------------------------------------------------------

--
-- 表的结构 `de_message`
--

CREATE TABLE IF NOT EXISTS `de_message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `de_message`
--

INSERT INTO `de_message` (`message_id`, `email`, `mobile`, `content`, `create_time`) VALUES
(1, '444', '44444444444444', ' 444444', 1449485932);

-- --------------------------------------------------------

--
-- 表的结构 `de_option`
--

CREATE TABLE IF NOT EXISTS `de_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_key` varchar(64) NOT NULL,
  `option_value` varchar(1024) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `de_option`
--

INSERT INTO `de_option` (`option_id`, `option_key`, `option_value`, `create_time`) VALUES
(1, 'deep_title', '汉凯建材', 1449359642),
(2, 'deep_keyword', '汉凯建材', 1449359642),
(3, 'deep_seo', '汉凯建材', 1449359642),
(4, 'deep_name', '汉凯建材', 1449350567),
(5, 'deep_phone', '123456789', 1449350567),
(6, 'deep_fax', '123456789', 1449350567),
(7, 'deep_email', '123456789', 1449350567),
(8, 'deep_address', '123456789', 1449350567),
(9, 'deep_logo', '1449369863_1772375271.jpg', 1449369864),
(10, 'deep_mark', '45656565', 1449351772),
(11, 'deep_powered', '广州蜜桔信息科技有限公司', 1449351772);

-- --------------------------------------------------------

--
-- 表的结构 `de_product`
--

CREATE TABLE IF NOT EXISTS `de_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `terms_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `if_show` int(2) NOT NULL,
  `default_image` int(128) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `de_product`
--

INSERT INTO `de_product` (`product_id`, `terms_id`, `title`, `summary`, `content`, `if_show`, `default_image`, `create_time`) VALUES
(1, 1, 'test55555555555', 'test55555555555', 'test55555555555', 1, 1451879542, 1451879542);

-- --------------------------------------------------------

--
-- 表的结构 `de_terms`
--

CREATE TABLE IF NOT EXISTS `de_terms` (
  `terms_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `slug` varchar(32) NOT NULL,
  `sort` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `if_show` int(2) NOT NULL,
  `parent` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`terms_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `de_terms`
--

INSERT INTO `de_terms` (`terms_id`, `name`, `slug`, `sort`, `num`, `if_show`, `parent`, `create_time`) VALUES
(1, 'test', 'test', 66, 0, 1, 0, 1451878037),
(2, 'test2', 'test2', 99, 0, 1, 0, 1451878670);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
