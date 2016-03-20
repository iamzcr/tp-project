-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 22 日 10:01
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

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
-- 表的结构 `we_group`
--

CREATE TABLE IF NOT EXISTS `we_group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(10) NOT NULL,
  `name` varchar(256) DEFAULT NULL COMMENT '组名',
  `count` varchar(10) DEFAULT NULL COMMENT '组数量',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- 转存表中的数据 `we_group`
--

INSERT INTO `we_group` (`group_id`, `id`, `name`, `count`) VALUES
(51, '2', '星标组', '0'),
(50, '1', '黑名单', '0'),
(49, '0', '未分组', '4');

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL COMMENT '群发类型',
  `errcode` varchar(16) NOT NULL,
  `errmsg` varchar(64) NOT NULL COMMENT '返回信息',
  `msg_id` int(11) NOT NULL COMMENT '群发id',
  `msg_data_id` int(11) DEFAULT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `we_material`
--

CREATE TABLE IF NOT EXISTS `we_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `confine` varchar(116) NOT NULL,
  `type` varchar(32) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `url` text,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

--
-- 转存表中的数据 `we_material`
--

INSERT INTO `we_material` (`id`, `confine`, `type`, `media_id`, `url`, `create_time`) VALUES
(74, 'permanent', 'image', 'I7Rwx78Syoz9OQZU_mBZuu-o-EpeWngws0ytzCRrxzc', 'https://mmbiz.qlogo.cn/mmbiz/rkF4hoBcgAVXgcHcibIuEgmDhI5jFoiaSOBE7YjTIxkLns0uSjWqfAdZzJ4O8fYtdUmic7O4qLiaZsVTmYIK8QPzRg/0?wx_fmt=jpeg', 1445601229),
(75, 'permanent', 'image', 'Zp1RRYSRxvP8zvU8PVOrUXEPJlUc-sVjfrB2FO8vWRo', 'https://mmbiz.qlogo.cn/mmbiz/rkF4hoBcgAUxScTloYCVibrpqyoZF1PhsIPvibicpDxafzJamj64gicNZiafKcj1YzDpibAoWxbpFKXAYgsF5hsZr37A/0?wx_fmt=jpeg', 1443612510),
(76, 'permanent', 'image', 'Zp1RRYSRxvP8zvU8PVOrUapX6MVieLrrCy-uuk5FalY', 'https://mmbiz.qlogo.cn/mmbiz/rkF4hoBcgAUxScTloYCVibrpqyoZF1Phsl11s3lsRiacK7v0OCMBHIQcpgiaicWwCBWicbn7jDpgr38TDSn2qPGyckQ/0?wx_fmt=jpeg', 1443612425),
(77, 'permanent', 'image', 'Zp1RRYSRxvP8zvU8PVOrUbmFTMgrYsjNvudeUO1A2rk', 'https://mmbiz.qlogo.cn/mmbiz/rkF4hoBcgAUxScTloYCVibrpqyoZF1PhsAwDIjDRWOfLWGooian4qI37v0C5l48DibTzibxicX2YIZ0OECrrwKXW6ag/0?wx_fmt=jpeg', 1443612295),
(78, 'permanent', 'image', 'wT1McLH6WKndW-F1kAJeiyOTEFo2dCeYoG_FnXIlu8o', 'https://mmbiz.qlogo.cn/mmbiz/rkF4hoBcgAVWiaPsptmHkibpzGiaUAJPs0gfAziaPTePSlicJ9FUnOSoibicXTqDpOIiaXKhyVnhic620k4bB8EwBvZ52XA/0', 1419931652),
(79, 'permanent', 'image', 'VN3d5q7kJ7vddNMMELNEXnkHi1C09oRMwNNVCsB-8mM', 'https://mmbiz.qlogo.cn/mmbiz/rkF4hoBcgAWnSV4HKXkIrDaYh8Esmx5gpEGEuQS8dTo0BpquKibeeLn4wootdpFPMSias9MzvYOPBWXEYs5fyESw/0', 1419599118),
(80, 'permanent', 'news', 'Zp1RRYSRxvP8zvU8PVOrUTV105N0tMy9Wzo0YF7lWFQ', '{"news_item":[{"title":"\\u3010\\u98de\\u901f\\u4ee3\\u53d1\\u3011\\u5927\\u653e\\u4ef7\\uff01\\u5feb\\u9012\\u8d39\\u901a\\u7968\\u4f4e\\u81f30.99\\u5143 \\u6700\\u9ad84\\u5143\\u5c01\\u9876 \\u4ee3\\u53d1\\u514d\\u8d39","author":"\\u98de\\u901f\\u4ee3\\u53d1","digest":"\\u3010\\u98de\\u901f\\u4ee3\\u53d1\\u3011\\u5927\\u653e\\u4ef7\\uff01\\u5feb\\u9012\\u8d39\\u901a\\u7968\\u4f4e\\u81f30.99\\u5143 \\u6700\\u9ad84\\u5143\\u5c01\\u9876 \\u4ee3\\u53d1\\u514d\\u8d39 \\uff01www.flysoo.cn","content":"<p style=\\"line-height: 1.5em; text-align: center;\\"><img data-s=\\"300,640\\" data-type=\\"jpeg\\" data-src=\\"http:\\/\\/mmbiz.qpic.cn\\/mmbiz\\/rkF4hoBcgAVXgcHcibIuEgmDhI5jFoiaSOBE7YjTIxkLns0uSjWqfAdZzJ4O8fYtdUmic7O4qLiaZsVTmYIK8QPzRg\\/0?wx_fmt=jpeg\\" data-ratio=\\"0.7509881422924901\\" data-w=\\"\\"  \\/><br  \\/><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\"><strong><span style=\\"color: rgb(0, 0, 0); font-size: 20px;\\"><\\/span><\\/strong><\\/span><\\/p><hr  \\/><p><span style=\\"color: rgb(0, 112, 192);\\"><strong><span style=\\"color: rgb(0, 0, 0); font-size: 20px;\\"><\\/span><\\/strong><br  \\/><\\/span><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong><span style=\\"font-size: 20px;\\">\\u98de\\u901f\\u4ee3\\u53d1\\u5927\\u653e\\u4ef7 \\u95ee\\u9898\\u89e3\\u7b54<\\/span><\\/strong><\\/span><\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u4e3a\\u4ec0\\u4e48\\u98de\\u901f\\u4ee3\\u53d1\\u8981\\u5927\\u653e\\u4ef7\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a1.\\u4e3a\\u4e86\\u56de\\u9988\\u5bf9\\u98de\\u901f\\u4ee3\\u53d1\\u652f\\u6301\\u591a\\u5e74\\u7684\\u5ba2\\u6237\\uff0c\\u5e76\\u4e14\\u98de\\u901f\\u4ee3\\u53d1\\u5e0c\\u671b\\u8ddf\\u5927\\u5bb6\\u4e00\\u76f4\\u8d70\\u4e0b\\u53bb<\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">2.\\u4e3a\\u4e86\\u5e2e\\u52a9\\u98de\\u901f\\u4ee3\\u53d1\\u5ba2\\u6237\\u5728\\u53cc11\\u65fa\\u5b63\\u6765\\u4e34\\u524d\\uff0c\\u63d0\\u5347\\u7ade\\u4e89\\u529b\\uff0c\\u51b2\\u523a\\u57fa\\u7840\\u9500\\u91cf\\uff0c\\u4fdd\\u969c\\u53cc11\\u4ea7\\u51fa<\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">3.\\u4e3a\\u4e86\\u68c0\\u9a8c\\u98de\\u901f\\u4ee3\\u53d1\\u56e2\\u961f\\u7684\\u6297\\u538b\\u80fd\\u529b\\uff0c\\u4e3a\\u53cc11\\u63d0\\u524d\\u505a\\u597d\\u51c6\\u5907<\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">4.\\u4e3a\\u4e86\\u8ba9\\u98de\\u901f\\u4ee3\\u53d1\\u80fd\\u591f\\u4e3a\\u66f4\\u591a\\u7684\\u521b\\u4e1a\\u8005\\u63d0\\u4f9b\\u670d\\u52a1<\\/span><\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u6d3b\\u52a8\\u4f18\\u60e0\\u662f\\u4ec0\\u4e48\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a<\\/span>\\u6d3b\\u52a8\\u65f6\\u95f4\\u4ece2015\\u5e7411\\u67081\\u65e5\\u8d77<\\/p><p>\\u4ee3\\u53d1\\u8d39\\uff1a1\\u5143\\/\\u4ef6<\\/p><p>\\u5408\\u4f5c\\u6863\\u53e3\\uff08A206 A325 918\\uff09\\u5feb\\u9012\\u8d39\\uff1a\\u5168\\u56fd\\u901a\\u79680.99\\u5143 \\u9650\\u5927\\u9646\\u5730\\u533a\\u5706\\u901a\\u3001\\u7533\\u901a\\u9996\\u91cd\\u8d39\\u7528\\uff0c\\u7eed\\u91cd\\u6309\\u6b63\\u5e38\\u6807\\u51c6\\u6536\\u53d6<\\/p><p>\\u4e0b\\u5355\\u65f68\\u5143\\uff0c\\u98de\\u901f\\u4ee3\\u53d1\\u5c06\\u4e8e\\u6bcf\\u670825-30\\u65e5\\u8fd4\\u73b0\\u4e0a\\u6708\\u6210\\u529f\\u8ba2\\u5355\\u8fd0\\u8d39\\u5dee\\u4ef77.01\\u5143\\/\\u5355      <\\/p><p>\\u5176\\u4ed6\\u6863\\u53e3\\u5feb\\u9012\\u8d39\\uff1a\\u5168\\u56fd\\u901a\\u79684\\u5143 \\u9650\\u5927\\u9646\\u5730\\u533a\\u5706\\u901a\\u3001\\u7533\\u901a\\u9996\\u91cd\\u8d39\\u7528\\uff0c\\u7eed\\u91cd\\u6309\\u6b63\\u5e38\\u6807\\u51c6\\u6536\\u53d6<\\/p><p>\\u4e0b\\u5355\\u65f68\\u5143\\uff0c\\u98de\\u901f\\u4ee3\\u53d1\\u5c06\\u4e8e\\u6bcf\\u670825-30\\u65e5\\u8fd4\\u73b0\\u4e0a\\u6708\\u6210\\u529f\\u8ba2\\u5355\\u8fd0\\u8d39\\u5dee\\u4ef74\\u5143\\/\\u5355<\\/p><p>\\u987a\\u4e30\\u3001EMS\\u3001\\u5706\\u901a\\u8d27\\u5230\\u4ed8\\u6b3e\\u53ca\\u5176\\u4ed6\\u5feb\\u9012\\u6682\\u4e0d\\u53c2\\u4e0e<\\/p><p><span style=\\"color: rgb(0, 0, 0);\\"><\\/span><br  \\/><\\/p><p><strong><span style=\\"color: rgb(0, 176, 240);\\">\\u95ee\\uff1a\\u4e3a\\u4ec0\\u4e48\\u4e0d\\u76f4\\u63a5\\u4e0b\\u53554\\u5143\\uff0c\\u800c\\u9700\\u8981\\u8fd4\\u73b0\\uff1f<\\/span><\\/strong><\\/p><p>\\u7b54\\uff1a\\u65f6\\u81f3\\u5e74\\u5e95\\uff0c\\u5404\\u5feb\\u9012\\u516c\\u53f8\\u5747\\u5df2\\u7ecf\\u4e0a\\u8c03\\u5feb\\u9012\\u8fd0\\u8d39\\uff08\\u6bcf\\u5e74\\u5982\\u6b64\\uff09\\uff0c\\u4e3a\\u4e86\\u5e02\\u573a\\u548c\\u8c10\\uff0c\\u6211\\u4eec\\u6ca1\\u529e\\u6cd5\\u76f4\\u63a5\\u6536\\u53d64\\u5143\\u8fd0\\u8d39<\\/p><p>\\u4e3a\\u4e86\\u8ba9\\u5927\\u5bb6\\u7ee7\\u7eed\\u4eab\\u53d7\\u4f4e\\u4ef7\\u4f18\\u60e0\\uff0c\\u98de\\u901f\\u4ee3\\u53d1\\u53ea\\u80fd\\u4ee5\\u8865\\u8d34\\u7684\\u5f62\\u5f0f\\u8fd4\\u73b0\\u8fd0\\u8d39\\u5dee\\u4ef74\\u5143\\/\\u5355\\u81f3\\u5927\\u5bb6\\u7684\\u5e10\\u6237\\u5185<\\/p><p>\\u6bcf\\u670825-30\\u65e5\\u8fd4\\u73b0\\u4e0a\\u6708\\u4ea4\\u6613\\u6210\\u529f\\uff08\\u65e0\\u552e\\u540e\\uff09\\u8ba2\\u5355\\u8fd0\\u8d39\\u5dee\\u4ef74\\u5143\\/\\u5355\\uff0c\\u8054\\u7cfb\\u6211\\u4eec\\u5ba2\\u670dQQ800057144\\u5373\\u53ef<\\/p><p>\\u4f8b\\uff1a11\\u6708\\u4ea4\\u6613\\u6210\\u529f\\u8ba2\\u53551000\\u5355*4\\u5143\\/\\u5355=4000\\u5143 \\u5c06\\u4e8e12\\u670825-30\\u65e5\\u8fd4\\u73b0\\u81f3\\u60a8\\u7684\\u5e10\\u6237\\u5185<\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u4e3a\\u4ec0\\u4e48\\u98de\\u901f\\u4ee3\\u53d1\\u8fd9\\u4e48\\u4fbf\\u5b9c\\uff0c\\u4f1a\\u4e0d\\u4f1a\\u662f\\u9a97\\u4eba\\u7684\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a\\u98de\\u901f\\u4ee3\\u53d1\\u662f\\u4e0e\\u7f51\\u5546\\u56edwsy.com\\u5408\\u4f5c\\uff0c\\u9996\\u5bb6\\u4ea4\\u7eb350000\\u5143\\u4eba\\u6c11\\u5e01\\u4fdd\\u8bc1\\u91d1\\u7684\\u7b2c\\u4e09\\u65b9\\u4ee3\\u53d1\\u56e2\\u961f\\uff0c\\u4fdd\\u969c\\u60a8\\u7684\\u5229\\u76ca<\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u98de\\u901f\\u4ee3\\u53d1\\u4e0e\\u8427\\u5c71\\u9510\\u9e70\\u7535\\u5546\\u56ed\\u5408\\u4f5c\\uff0c\\u6b63\\u89c4\\u516c\\u53f8\\u8fd0\\u4f5c\\uff0c\\u62e5\\u67091800\\u5e73\\u65b9\\u6574\\u5c42\\u529e\\u516c\\u573a\\u5730\\uff0c\\u6b22\\u8fce\\u5927\\u5bb6\\u4e0a\\u95e8\\u5b9e\\u5730\\u8003\\u5bdf<\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u98de\\u901f\\u4ee3\\u53d1\\u4e3a\\u676d\\u5dde\\u8001\\u724c\\u4ee3\\u53d1\\uff0c\\u5b9e\\u529b\\u4e0e\\u53e3\\u7891\\u6df1\\u53d7\\u4ee3\\u53d1\\u5ba2\\u6237\\u3001\\u4f9b\\u5e94\\u5546\\u53ca\\u5feb\\u9012\\u516c\\u53f8\\u8ba4\\u53ef\\uff0c\\u8bf7\\u60a8\\u653e\\u5fc3\\u4e0b\\u5355<\\/span><\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u600e\\u6837\\u624d\\u80fd\\u6ee1\\u8db3\\u6d3b\\u52a8\\u8981\\u6c42\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a\\u5728\\u98de\\u901f\\u4ee3\\u53d1\\u7f51\\u7ad9flysoo.cn\\u4e0b\\u5355\\uff0c\\u65e0\\u8bba\\u60a8\\u662f1\\u5355\\/\\u5929\\u8fd8\\u662f1000\\u5355\\/\\u5929\\uff0c\\u5747\\u53ef\\u4eab\\u53d7\\u6d3b\\u52a8\\u4f18\\u60e0<\\/span><\\/p><p><br  \\/><\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u6d3b\\u52a8\\u4ec0\\u4e48\\u65f6\\u5019\\u7ed3\\u675f\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a\\u6d3b\\u52a8\\u7ed3\\u675f\\u65e5\\u671f\\uff1a\\u6682\\u65f6\\u672a\\u5b9a\\uff0c\\u98de\\u901f\\u4ee3\\u53d1\\u4e0d\\u6392\\u9664\\u957f\\u671f\\u6d3b\\u52a8\\u7684\\u53ef\\u80fd<\\/span><\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u98de\\u901f\\u4ee3\\u53d1\\u5927\\u653e\\u4ef7\\u4f1a\\u5f71\\u54cd\\u5230\\u4ee3\\u53d1\\u670d\\u52a1\\u54c1\\u8d28\\u5417\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a\\u4e0d\\u4f1a\\uff01\\u76f8\\u53cd\\uff0c\\u6211\\u4eec\\u4f1a\\u66f4\\u52a0\\u52aa\\u529b\\u7684\\u53bb\\u63d0\\u5347\\u4ee3\\u53d1\\u670d\\u52a1\\u54c1\\u8d28\\uff0c\\u5982\\u679c\\u5bf9\\u98de\\u901f\\u4ee3\\u53d1\\u670d\\u52a1\\u6709\\u4e0d\\u6ee1\\u610f\\u7684\\u5730\\u65b9\\uff0c\\u53ef\\u4ee5\\u76f4\\u63a5\\u6295\\u8bc9\\u81f3QQ55189125<\\/span><\\/p><p><br  \\/><\\/p><p><span style=\\"color: rgb(0, 176, 240);\\"><strong>\\u95ee\\uff1a\\u552e\\u540e\\u8d54\\u507f\\u8fd0\\u8d39\\u600e\\u4e48\\u5904\\u7406\\uff1f<\\/strong><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\">\\u7b54\\uff1a\\u5982\\u56e0\\u98de\\u901f\\u4ee3\\u53d1\\u5de5\\u4f5c\\u5931\\u8bef\\u5bfc\\u81f4\\u552e\\u540e\\u8fd0\\u8d39\\uff0c\\u5feb\\u9012\\u8d39\\u6309\\u53d1\\u51fa\\u901a\\u79684\\u5143+\\u987e\\u5ba2\\u9000\\u56de\\u5b9e\\u9645\\u90ae\\u8d39\\u8d54\\u507f<\\/span><\\/p><p><br  \\/><\\/p><hr  \\/><p><strong><span style=\\"color: rgb(0, 0, 0);\\">\\u5982\\u6709\\u4e0d\\u660e\\u8bf7\\u968f\\u65f6\\u8054\\u7cfb\\u6211\\u4eec<\\/span><\\/strong><\\/p><p><strong><span style=\\"color: rgb(0, 0, 0);\\">Q Q\\uff1a800 0571 44<\\/span><\\/strong><\\/p><p><strong><span style=\\"color: rgb(0, 0, 0);\\">\\u7535\\u8bdd\\uff1a0571-8369 5781<\\/span><\\/strong><\\/p><p><strong><span style=\\"color: rgb(0, 0, 0);\\">\\u5730\\u5740\\uff1a\\u8427\\u5c71\\u533a\\u5efa\\u8bbe\\u4e8c\\u8def67\\u53f7\\u9510\\u9e70\\u7535\\u5546\\u56edC\\u5e624\\u5c42<\\/span><\\/strong><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\"><br  \\/><\\/span><\\/p><p><span style=\\"color: rgb(0, 0, 0);\\"><strong><span style=\\"color:#ff0000\\">\\u70b9\\u51fb\\u9605\\u8bfb\\u539f\\u6587\\u67e5\\u770b\\u8be6\\u60c5<\\/span><\\/strong><\\/span><\\/p><p style=\\"line-height: 1.5em;\\"><br  \\/><\\/p>","content_source_url":"http:\\/\\/www.flysoo.cn\\/sale\\/1001\\/sale1001.html","thumb_media_id":"Zp1RRYSRxvP8zvU8PVOrUbmFTMgrYsjNvudeUO1A2rk","show_cover_pic":0,"url":"http:\\/\\/mp.weixin.qq.com\\/s?__biz=MzA3MzAzMTExOQ==&mid=368288055&idx=1&sn=8c13b3cf7f3748a8ac88054b95dd3943&scene=18#wechat_redirect","thumb_url":"http:\\/\\/mmbiz.qpic.cn\\/mmbiz\\/rkF4hoBcgAWx8q8FuVMqyL9vskxIcXNLKcnRXNtWbkSjSCGNLau6eYeUTTNWWpSTiaiaOZ7edQfBGW2PlSfkSuhg\\/0?wx_fmt=jpeg"}]}', 1446128666),
(81, 'permanent', 'news', 'wT1McLH6WKndW-F1kAJei0viTVoDIzGQ0MPdfihH4K0', '{"news_item":[{"title":"\\u98de\\u901f\\u4ee3\\u53d12015\\u5e741\\u67081\\u65e5\\u642c\\u4ed3\\u516c\\u544a","author":"\\u98de\\u901f\\u4ee3\\u53d1","digest":" \\u4eb2\\uff0c\\u5f88\\u62b1\\u6b49\\u6253\\u6270\\u5230\\u60a8\\u3002\\u2605\\u2605\\u2605\\u2605\\u2605\\u98de\\u901f\\u4ee3\\u53d12015\\u5e741\\u67081\\u65e5\\u642c\\u4ed3\\u516c\\u544a\\u2605\\u2605\\u2605\\u2605\\u2605\\u8be6\\u7ec6\\u5185\\u5bb9\\u8bf7\\u70b9\\u51fb\\u9605\\u8bfb\\u539f\\u6587\\u67e5\\u770b\\u3002\\u6216\\u8005","content":"<p> \\u4eb2\\uff0c\\u5f88\\u62b1\\u6b49\\u6253\\u6270\\u5230\\u60a8\\u3002<br  \\/>\\u2605\\u2605\\u2605\\u2605\\u2605\\u98de\\u901f\\u4ee3\\u53d12015\\u5e741\\u67081\\u65e5\\u642c\\u4ed3\\u516c\\u544a\\u2605\\u2605\\u2605\\u2605\\u2605<br  \\/>\\u8be6\\u7ec6\\u5185\\u5bb9\\u8bf7\\u70b9\\u51fb\\u9605\\u8bfb\\u539f\\u6587\\u67e5\\u770b\\u3002<br  \\/>\\u6216\\u8005\\u5728\\u98de\\u901f\\u4ee3\\u53d1\\u7f51\\u7ad9\\u7528\\u6237\\u4e2d\\u5fc3\\u9996\\u9875\\u67e5\\u770b\\u3002<\\/p><p>\\u98de\\u901f\\u4ee3\\u53d1\\u7f51\\u5740:www.571fs.com<\\/p><p><br  \\/><\\/p>","content_source_url":"http:\\/\\/www.571fs.com\\/article.php?id=11","thumb_media_id":"wT1McLH6WKndW-F1kAJeiyOTEFo2dCeYoG_FnXIlu8o","show_cover_pic":1,"url":"http:\\/\\/mp.weixin.qq.com\\/s?__biz=MzA3MzAzMTExOQ==&mid=267024304&idx=1&sn=cdb211fb676d5ad089ed1dd395f9b365#rd","thumb_url":"http:\\/\\/mmbiz.qpic.cn\\/mmbiz\\/rkF4hoBcgAVWiaPsptmHkibpzGiaUAJPs0g5NL6Mblcj6oJFHUjblSGpgm67NuKQJdk2CMiauu0rNgtOpbTomED9zQ\\/0"}]}', 1419931873),
(82, 'permanent', 'news', 'VN3d5q7kJ7vddNMMELNEXs3sRFvcI0h5OTC0vRc_34A', '{"news_item":[{"title":"\\u56db\\u5b63\\u661f\\u5ea7\\u6863\\u53e3\\u642c\\u5bb6\\u4e00\\u89c8\\u8868","author":"\\u98de\\u901f\\u4ee3\\u53d1","digest":"\\u4eb2\\uff0c\\u7531\\u4e8e\\u8fd1\\u671f\\u539f\\u56db\\u5b63\\u661f\\u5ea7\\u90e8\\u5206\\u6863\\u53e3\\u642c\\u81f3\\u7535\\u5546\\u57fa\\u5730\\uff0c\\u5bfc\\u81f4\\u90e8\\u5206\\u5546\\u54c1\\u4e0d\\u80fd\\u53ca\\u65f6\\u62ff\\u8d27\\u3001\\u53d1\\u8d27\\uff0c\\u73b0\\u98de\\u901f\\u4ee3\\u53d1\\u6574\\u7406\\u51fa\\u4e00\\u90e8\\u5206\\u642c\\u5bb6\\u6863\\u53e3\\uff0c","content":"<p><span style=\\"\\">\\u4eb2\\uff0c\\u7531\\u4e8e\\u8fd1\\u671f\\u539f\\u56db\\u5b63\\u661f\\u5ea7\\u90e8\\u5206\\u6863\\u53e3\\u642c\\u81f3\\u7535\\u5546\\u57fa\\u5730\\uff0c\\u5bfc\\u81f4\\u90e8\\u5206\\u5546\\u54c1\\u4e0d\\u80fd\\u53ca\\u65f6\\u62ff\\u8d27\\u3001\\u53d1\\u8d27\\uff0c\\u73b0\\u98de\\u901f\\u4ee3\\u53d1\\u6574\\u7406\\u51fa\\u4e00\\u90e8\\u5206\\u642c\\u5bb6\\u6863\\u53e3\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u4fee\\u6539\\u597d\\u5546\\u54c1\\u7684\\u5546\\u5bb6\\u7f16\\u7801\\uff0c\\u540c\\u65f6\\u591a\\u591a\\u6ce8\\u610f\\u6863\\u53e3\\u6dd8\\u5b9d\\u5e97\\u9996\\u9875\\u642c\\u5bb6\\u516c\\u544a\\uff0c\\u4ee5\\u514d\\u56e0\\u6863\\u53e3\\u4e0d\\u5bf9\\u5bfc\\u81f4\\u62ff\\u4e0d\\u5230\\u8d27\\u800c\\u4e0d\\u80fd\\u53ca\\u65f6\\u53d1\\u8d27\\u3002\\u70b9\\u51fb\\u67e5\\u770b\\uff1a<\\/span>http:\\/\\/m.571fs.com\\/article.php?id=10  \\u8be6\\u7ec6\\u8bf7\\u70b9\\u51fb\\uff1a\\u9605\\u8bfb\\u539f\\u6587<\\/p>","content_source_url":"http:\\/\\/m.571fs.com\\/article.php?id=10","thumb_media_id":"VN3d5q7kJ7vddNMMELNEXnkHi1C09oRMwNNVCsB-8mM","show_cover_pic":1,"url":"http:\\/\\/mp.weixin.qq.com\\/s?__biz=MzA3MzAzMTExOQ==&mid=265877558&idx=1&sn=4ada96c5a4af60d8f1c744d4cf2b5ef6#rd","thumb_url":"http:\\/\\/mmbiz.qpic.cn\\/mmbiz\\/rkF4hoBcgAWnSV4HKXkIrDaYh8Esmx5gmYxXMy1CUibBCblhXoTicL54KEeOYy5khMOuWetfMKeLpPq2na7FlErw\\/0"}]}', 1419599457);

-- --------------------------------------------------------

--
-- 表的结构 `we_menu`
--

CREATE TABLE IF NOT EXISTS `we_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `keyword` varchar(128) CHARACTER SET utf8 NOT NULL,
  `type` varchar(16) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(10) NOT NULL,
  `create_time` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `we_menu`
--

INSERT INTO `we_menu` (`menu_id`, `name`, `keyword`, `type`, `parent_id`, `create_time`, `sort`) VALUES
(4, 'test', 'test', 'click', 0, 0, 22),
(5, 'test1', 'https://www.baidu.com/', 'view', 0, 0, 44),
(6, '飞速代发', 'http://www.flysoo.cn/', 'view', 0, 0, 55),
(7, 'sub1', '55', 'click', 4, 1453090776, 666);

-- --------------------------------------------------------

--
-- 表的结构 `we_options`
--

CREATE TABLE IF NOT EXISTS `we_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_key` varchar(32) NOT NULL,
  `option_value` text NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `we_options`
--

INSERT INTO `we_options` (`option_id`, `option_key`, `option_value`, `create_time`) VALUES
(2, 'token', 'sdads', 1453212290),
(3, 'appid', 'adsad', 1453212290),
(4, 'appsecret', 'sadsad', 1453212290),
(5, 'appkey', 'dsadas', 1453212290),
(6, 'voice_count', '0', 1453362819),
(7, 'video_count', '0', 1453362819),
(8, 'image_count', '6', 1453362819),
(9, 'news_count', '3', 1453362819);

-- --------------------------------------------------------

--
-- 表的结构 `we_response`
--

CREATE TABLE IF NOT EXISTS `we_response` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(256) DEFAULT NULL,
  `msgtype` varchar(32) DEFAULT NULL,
  `msgreply` varchar(32) DEFAULT NULL,
  `content` longtext,
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `we_response`
--

INSERT INTO `we_response` (`id`, `keyword`, `msgtype`, `msgreply`, `content`, `create_time`) VALUES
(1, '33', 'text', 'text', 'wwwwwww', 1453191886);

-- --------------------------------------------------------

--
-- 表的结构 `we_temp`
--

CREATE TABLE IF NOT EXISTS `we_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_key` varchar(64) NOT NULL,
  `template_id` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `we_temp`
--

INSERT INTO `we_temp` (`id`, `template_key`, `template_id`, `title`, `create_time`) VALUES
(1, 'order_submit_success', '2hZLNOUqYoETblHjcervGIyyFyAtl8FzC8HQywSJL1U', '订单提交成功', 1453373688),
(2, 'refund_success_msg', '3gokehVzRSYazBhtz0JHMozhW-Rl6Htu7xLmWK_4aAQ', '退款成功通知 ', 1453433092),
(3, 'user_cash_msg', '4rfOF7jUxeNjmNIusmwfvvlC2u6iC0W6H6pkWRvtyUg', '会员充值通知', 1453450997),
(4, 'op_check_code_tips', 'GCBTY-0F9BK4Upq85992pOJFRFu93-mFmdJLotvUz5U', '操作验证码提醒', 1453451054),
(5, 'acount_money_change_tips', 'M0pCh-r70x63qrfX9img7bVRLS2vmU2nxFEywzuSyp4', '帐户资金变动提醒', 1453451094),
(6, 'good_repay_msg', 'QJtYpcH1ByC3YeWIkRjXW0r6ww1_xHrnrfJBB7Kp6Uc', '商品补款通知', 1453451792),
(7, 'goods_refund_msg', 'SyYzJrhrqxo3h0cIDraTrUsI9ueSlE13aykZpzZ_LP8', '缺货退款通知', 1453451856),
(8, 'sale_after_deal_msg', 'Uuc-ZxocnGtUBsY3Pvkq1FzRuxIRELp1riCzA-byGJA', '售后服务处理进度提醒', 1453451928),
(9, 'order_status_update', 'oqx5kuOz2nE2yFqHKWVQUAtvhXU4vSRD7t1-geJ9VO8', '订单状态更新', 1453451958),
(10, 'order_pay_for_success_msg', 'rWZz7wADI7uaNfOjMmaGVpOfmipwNCYA4wVDLuaU_5s', '订单支付成功', 1453451992),
(11, 'order_changer_tips', 'wpnN6Cw5nyZZWcOxXwMSCSqhicSLX-gbS0swtEZhTx0', '订单变更提醒', 1453452037);

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
-- 表的结构 `we_uploads`
--

CREATE TABLE IF NOT EXISTS `we_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `type` varchar(16) NOT NULL,
  `media_id` varchar(255) DEFAULT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `we_uploads`
--

INSERT INTO `we_uploads` (`id`, `path`, `type`, `media_id`, `create_time`) VALUES
(2, '1453355880_1207967124.jpg', 'jpg', '', 1453355880),
(3, '1453361306_104269559.jpg', 'jpg', 'uLIbCj0pmkCDmh9n7y2qKOHCDxc0VGz1loeSxRauwbI', 1453361309);

-- --------------------------------------------------------

--
-- 表的结构 `we_user`
--

CREATE TABLE IF NOT EXISTS `we_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `openid` varchar(255) NOT NULL COMMENT '微信用户唯一标识',
  `shop_id` int(11) NOT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `subscribe` int(2) DEFAULT NULL COMMENT '判断是否关注',
  `sex` int(2) DEFAULT NULL COMMENT '性别',
  `city` varchar(32) DEFAULT NULL COMMENT '所在城市',
  `nickname` varchar(64) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL COMMENT '所在国家',
  `province` varchar(32) DEFAULT NULL COMMENT '所在省份',
  `language` varchar(16) DEFAULT NULL COMMENT '语言',
  `headimgurl` varchar(256) DEFAULT NULL COMMENT '头像',
  `subscribe_time` int(11) DEFAULT NULL COMMENT '关注时间',
  `remark` varchar(64) DEFAULT NULL COMMENT '备注信息',
  `groupid` int(11) DEFAULT NULL COMMENT '所在分组id',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
