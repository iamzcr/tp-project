-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 12 月 11 日 10:15
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `deep_shop`
--

-- --------------------------------------------------------

--
-- 表的结构 `de_banner`
--

CREATE TABLE IF NOT EXISTS `de_banner` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `type` int(2) NOT NULL,
  `images` varchar(128) NOT NULL,
  `create_time` int(11) NOT NULL,
  `if_show` int(2) NOT NULL,
  `link` varchar(64) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `de_banner`
--

INSERT INTO `de_banner` (`banner_id`, `name`, `content`, `type`, `images`, `create_time`, `if_show`, `link`) VALUES
(2, '产品页头部广告', '', 1, '1442288649_213931378.jpg', 1442288649, 1, 'http://localhost/deep_shop/index.php/home/product/index'),
(3, '左侧广告图', '', 2, '1442288731_139896976.jpg', 1442288731, 1, 'http://localhost/deep_shop/index.php/home/product/index'),
(4, 'test', '', 3, 'deep_shop.jpg', 1442459057, 1, '3');

-- --------------------------------------------------------

--
-- 表的结构 `de_brand`
--

CREATE TABLE IF NOT EXISTS `de_brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `slug` varchar(16) NOT NULL,
  `description` text NOT NULL,
  `if_show` int(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `de_brand`
--

INSERT INTO `de_brand` (`brand_id`, `name`, `slug`, `description`, `if_show`, `create_time`) VALUES
(4, '七匹狼', 'qi', '七匹狼', 1, 1442388608),
(5, 'H&amp;M', 'HM', 'H&amp;M', 1, 1442388626);

-- --------------------------------------------------------

--
-- 表的结构 `de_cart`
--

CREATE TABLE IF NOT EXISTS `de_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(128) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(64) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `de_cart`
--

INSERT INTO `de_cart` (`cart_id`, `user_id`, `session_id`, `product_id`, `product_name`, `price`, `quantity`, `image`) VALUES
(1, 4, '3u72ldvqcruebblmp5ehg62o56', 16, '测试产品3', 444.00, 10, '1442544629_202211247.jpg'),
(2, 4, '3u72ldvqcruebblmp5ehg62o56', 15, '测试产品1', 444.00, 4, '1442544565_1249257925.jpg'),
(3, 0, 'ornkimemnsjskm6cgeek9gt5g7', 17, '测试产品5', 0.01, 1, '1442545348_890788881.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `de_category`
--

CREATE TABLE IF NOT EXISTS `de_category` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(32) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `if_show` int(2) NOT NULL,
  `slug` varchar(16) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `de_category`
--

INSERT INTO `de_category` (`cate_id`, `cate_name`, `parent_id`, `if_show`, `slug`, `create_time`) VALUES
(10, '男装', 0, 1, 'man', 1442388545),
(11, '女装', 0, 1, 'wumen', 1442388566),
(12, '童装', 0, 1, 'child', 1442388582);

-- --------------------------------------------------------

--
-- 表的结构 `de_cms_category`
--

CREATE TABLE IF NOT EXISTS `de_cms_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `slug` varchar(16) NOT NULL,
  `if_show` int(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `de_cms_category`
--

INSERT INTO `de_cms_category` (`id`, `name`, `slug`, `if_show`, `create_time`) VALUES
(1, 'cms1', 'cms1', 1, 1441768617);

-- --------------------------------------------------------

--
-- 表的结构 `de_cms_post`
--

CREATE TABLE IF NOT EXISTS `de_cms_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `summary` varchar(1024) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(128) NOT NULL,
  `create_time` int(11) NOT NULL,
  `if_show` int(2) NOT NULL,
  `cms_category_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `de_cms_post`
--

INSERT INTO `de_cms_post` (`post_id`, `title`, `summary`, `content`, `image`, `create_time`, `if_show`, `cms_category_id`) VALUES
(2, '55555555555555', 'test', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', '1442290141_2065166989.png', 1442290141, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `de_contact`
--

CREATE TABLE IF NOT EXISTS `de_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `email` varchar(32) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `de_coupon`
--

CREATE TABLE IF NOT EXISTS `de_coupon` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_price` decimal(10,2) NOT NULL,
  `coupon_price` decimal(10,2) NOT NULL,
  `end_time` int(11) NOT NULL,
  `begin_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `if_show` int(2) NOT NULL,
  `type` int(2) NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `de_links`
--

CREATE TABLE IF NOT EXISTS `de_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `links` varchar(128) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `de_links`
--

INSERT INTO `de_links` (`id`, `name`, `links`, `create_time`) VALUES
(2, 'test', 'test', 0),
(3, 'dddddd', 'ddddddddddd', 1442822781);

-- --------------------------------------------------------

--
-- 表的结构 `de_manager`
--

CREATE TABLE IF NOT EXISTS `de_manager` (
  `manager_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(32) NOT NULL,
  `if_show` int(2) NOT NULL,
  PRIMARY KEY (`manager_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `de_manager`
--

INSERT INTO `de_manager` (`manager_id`, `name`, `password`, `email`, `if_show`) VALUES
(2, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'iamzcr@gmail.com', 1);

-- --------------------------------------------------------

--
-- 表的结构 `de_options`
--

CREATE TABLE IF NOT EXISTS `de_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_key` varchar(64) NOT NULL,
  `option_value` text NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `de_options`
--

INSERT INTO `de_options` (`id`, `option_key`, `option_value`, `create_time`) VALUES
(13, 'deep_name', 'dddddddddd', 1441869772),
(14, 'deep_phone', '44444444444444444444', 1441869772),
(15, 'deep_fax', '44444444444444444', 1441869772),
(16, 'deep_email', '444444444444444444', 1441869773),
(17, 'deep_address', '44444444444444444444', 1441869773),
(18, 'deep_title', '跳跳糖跳跳糖吞吞吐吐', 1441870845),
(19, 'deep_keyword', '他跳跳糖跳跳糖吞吞吐吐跳跳糖跳跳糖吞吞吐吐跳跳糖跳跳糖吞吞吐吐', 1441870845),
(20, 'deep_seo', '跳跳糖跳跳糖吞吞吐吐他跳跳糖跳跳糖吞吞吐吐跳跳糖跳跳糖吞吞吐吐跳跳糖跳跳糖吞吞吐吐', 1441870845),
(21, 'deep_summary', '网上商城类似于现实世界当中的商店，差别是利用电子商务的各种手段，达成从买到卖的过程的虚拟商店，从而减少中间环节，消除运输成本和代理中间的差价，造就对普通消费和加大市场流通带来巨大的发展空间。尽能的还消费者以利益，带动公司发展和企业腾飞，引导国民经济稳定快速发展，推动国内生产总值。', 1442472198),
(22, 'deep_content', '<p>网上商城类似于现实世界当中的<strong>商店，差别是利用电子商务的各种手段，达成从买到卖的过程的虚拟商店，从而减少中间环节，消除运输成本和代理中间的差价，造就对普通消费和加大市场流通带来巨大的发展空间。尽能的还消费者以利益，带动公司发展和企业腾飞，引导国民经济稳定快速发展，推动国内生产总值。</strong></p><p><strong>CIA（Cross Industry Alliance，异业联盟）CIA模式是目前最新型的电子商务模式，典型代表有科萃异业联盟。该模式以“合纵连横、共创共享”的思想为指导，让商家与商家之间资源共享、互助共赢；同时，以消费返利的方式，实现消</strong>费者零成本创业。通过消费者与商家的互动，实现市场终端的无限扩张。&nbsp;B2B（Business To Business, 商家对商家）&nbsp;B2B典型代表有阿里巴巴。中国制造网，慧聪等，主要是从事批发业务；&nbsp;B2C(Business To Customer,商家对顾客销售)&nbsp;</p><p>B2C典型代表有当当网、京东商城、中国购、凡客诚品、稀货街、百宝汇商城、新蛋商城、中国巨蛋、思和电器商城、聚购商城、聚购商城主要是经营工艺品与创意产品。\r\nB2C中又分为三种，一种是实体企业转网上商城，代表网站为库巴网；一种是实体市场转网上商城，代表为蚕丝网城；一种是原有电子商务公司建设的网上商城，代表为京东商城，中华网库商城系统等</p><p>C2C(Customer to Customer,客户和客户）C2C典型代表有淘宝、中国购、易趣、倾心淘宝导购返利网、拍拍、百度有啊、奥图商城、缘物语。</p><p>O2O（Online To Offline,线上线下相结合）\r\nO2O典型代表有象屿商城。</p><p>&nbsp;G2C:G2C电子政务是指政府( Government)与公众(Citizen)之间的电子政务。是政府通过电子网络系统为公民提供各种服务。B2B2C:目前最新的一种整合型网上商城模式，代表有汇诚网（又名“汇城网”）。</p><p>O2P（Online To Place,本地化线上线下）O2P：2013年最新的一种电商模式，针对大型家电或者汽车等大件商品不便运输，由电动车业界精英提出的线上商城，本地化配送的新模式。代表有道易行商城（又名“道易行专业电动车商城”）。</p><p>G2C电子政务所包含的内容十分广泛，主要的应用包括：公众信息服务、电子身份认证、电子税务、电子社会保障服务、电子民主管理、电子医疗服务、电子就业服务、电子教育、培训服务、电子交通管理等。G2C电子政务的目的是除了政府给公众提供方便、快捷、高质量的服务外，更重要的是可以开辟公众参政、议政的渠道，畅通公众的利益表达机制，建立政府与公众的良性互动平台。\r\n网上商城是在为个人用户和企业用户提供人性化的全方位服务，努力为用户创造亲切、轻松和愉悦的购物环境，不断丰富产品结构，最大化地满足消费者日趋多样的购物需求，并凭借更具竞争力的价格和逐渐完善的物流配送体系等各项优势，赢得市场占有率多年稳居行业首位的骄人成绩，也是时代发展的趋势。</p>', 1442472198),
(23, 'deep_logo', '<p>ddddddddddddddd</p>', 1442479854),
(24, 'deep_service', '<p>ddddddddddddddddd</p>', 1442479920);

-- --------------------------------------------------------

--
-- 表的结构 `de_order`
--

CREATE TABLE IF NOT EXISTS `de_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `invoice_number` bigint(20) DEFAULT '0' COMMENT '订单流水号',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `amount` decimal(19,2) NOT NULL COMMENT '总额',
  `status` tinyint(6) NOT NULL DEFAULT '0' COMMENT '订单状态0 新建1 等待支付2 已支付3 已发货4 已完成/已收货5 已取消',
  `payment_status` tinyint(6) NOT NULL DEFAULT '0' COMMENT '支付状态\r\n0 未支付\r\n1 已支付',
  `payment_type` tinyint(6) NOT NULL DEFAULT '0' COMMENT '支付方式\r\n0 未知/未定义\r\n1 paypal\r\n2 asiapay\r\n3 jetco\r\n4 alipay\r\n5 wechatpay',
  `shipping_id` int(11) DEFAULT '0' COMMENT '送货ID',
  `shipping_fee` decimal(19,2) DEFAULT '0.00' COMMENT '运费',
  `delivery_number` varchar(255) DEFAULT NULL COMMENT '送货单号',
  `created_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  KEY `invoice_number_index` (`invoice_number`),
  KEY `user_id_index` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单表' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `de_order`
--

INSERT INTO `de_order` (`order_id`, `invoice_number`, `user_id`, `amount`, `status`, `payment_status`, `payment_type`, `shipping_id`, `shipping_fee`, `delivery_number`, `created_time`, `updated_time`) VALUES
(9, 2015091852975249, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(10, 2015091897101505, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(11, 2015091851995610, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(12, 2015091810152569, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(13, 2015091810152564, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(14, 2015091810110152, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(15, 2015091849504810, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(16, 2015091897551009, 3, 0.01, 0, 0, 2, 2, 0.00, NULL, NULL, NULL),
(17, 2015091853100975, 3, 987.01, 0, 0, 2, 3, 0.00, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `de_order_detail`
--

CREATE TABLE IF NOT EXISTS `de_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(128) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_amount` decimal(10,2) NOT NULL,
  `image` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `de_order_detail`
--

INSERT INTO `de_order_detail` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `price_amount`, `image`) VALUES
(3, 0, 0, '', 0, 0.00, 0.00, ''),
(4, 0, 0, '', 0, 0.00, 0.00, ''),
(5, 0, 0, '', 0, 0.00, 0.00, ''),
(6, 0, 0, '', 0, 0.00, 0.00, ''),
(7, 0, 0, '', 0, 0.00, 0.00, ''),
(8, 0, 0, '', 0, 0.00, 0.00, ''),
(9, 0, 0, '', 0, 0.00, 0.00, ''),
(10, 0, 0, '', 0, 0.00, 0.00, ''),
(11, 0, 0, '', 0, 0.00, 0.00, '');

-- --------------------------------------------------------

--
-- 表的结构 `de_product`
--

CREATE TABLE IF NOT EXISTS `de_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `input_price` decimal(10,2) NOT NULL,
  `market_price` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `default_image` varchar(256) NOT NULL,
  `if_show` int(2) NOT NULL,
  `if_features` int(2) NOT NULL,
  `if_recommend` int(2) NOT NULL,
  `stock` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `de_product`
--

INSERT INTO `de_product` (`product_id`, `cate_id`, `brand_id`, `name`, `description`, `input_price`, `market_price`, `price`, `default_image`, `if_show`, `if_features`, `if_recommend`, `stock`, `create_time`) VALUES
(14, 10, 4, '测试产品', '测试产品\r\n测试产品', 99.00, 99.00, 99.00, '1442391516_1904115912.jpg', 1, 1, 1, 0, 1442391516),
(15, 11, 5, '测试产品1', '<p>测试产品1</p><p>测试产品1</p><p>测试产品1</p>', 444.00, 444.00, 444.00, '1442544565_1249257925.jpg', 1, 1, 1, 99, 1442544565),
(16, 12, 5, '测试产品3', '<p>测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3</p><p>测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3</p><p>测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3</p><p>测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3</p><p>测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3</p><p><img src="/ueditor/php/upload/image/20150918/1442544616798153.png" title="1442544616798153.png" alt="iframe2.png"/></p><p>测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3测试产品3</p>', 5555.00, 666.00, 444.00, '1442544629_202211247.jpg', 1, 1, 0, 99, 1442544629),
(17, 10, 4, '测试产品5', '<p>0.01</p><p>0.01</p><p>0.01</p><p>0.01</p><p>0.01</p><p>0.01</p>', 0.01, 0.01, 0.01, '1442545348_890788881.jpg', 1, 1, 1, 99, 1442545348);

-- --------------------------------------------------------

--
-- 表的结构 `de_product_brand_relation`
--

CREATE TABLE IF NOT EXISTS `de_product_brand_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `de_product_cate_relation`
--

CREATE TABLE IF NOT EXISTS `de_product_cate_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `de_product_tag_relation`
--

CREATE TABLE IF NOT EXISTS `de_product_tag_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `de_product_tag_relation`
--

INSERT INTO `de_product_tag_relation` (`id`, `tag_id`, `product_id`) VALUES
(1, 1, 13),
(2, 3, 13),
(3, 4, 13),
(4, 1, 16),
(5, 3, 16),
(6, 4, 16),
(7, 1, 17),
(8, 3, 17),
(9, 4, 17);

-- --------------------------------------------------------

--
-- 表的结构 `de_shipping`
--

CREATE TABLE IF NOT EXISTS `de_shipping` (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(256) NOT NULL,
  `if_default` int(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `de_shipping`
--

INSERT INTO `de_shipping` (`shipping_id`, `email`, `name`, `phone`, `user_id`, `address`, `if_default`, `create_time`) VALUES
(3, '888888888888', '888888', '88888888', 3, '88888888', 1, 1442218362);

-- --------------------------------------------------------

--
-- 表的结构 `de_tag`
--

CREATE TABLE IF NOT EXISTS `de_tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `if_show` int(2) NOT NULL,
  `slug` varchar(16) NOT NULL,
  `name` varchar(32) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `de_tag`
--

INSERT INTO `de_tag` (`tag_id`, `if_show`, `slug`, `name`, `create_time`) VALUES
(1, 1, 'tag1', 'tag1', 1442312099),
(3, 1, 'tag2', 'tag2', 1442312128),
(4, 1, 'tag4', 'tag3', 1442312147);

-- --------------------------------------------------------

--
-- 表的结构 `de_user`
--

CREATE TABLE IF NOT EXISTS `de_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `create_time` int(11) NOT NULL,
  `if_show` int(2) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `de_user`
--

INSERT INTO `de_user` (`user_id`, `username`, `password`, `email`, `phone`, `create_time`, `if_show`) VALUES
(3, '8888', 'cfcd208495d565ef66e7dff9f98764da', '88888888888', '8888888888', 0, 0),
(4, 'iamzcr@gmail.com', '670b14728ad9902aecba32e22fa4f6bd', 'iamzcr@gmail.com', 'iamzcr@gmail.com', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `de_wish`
--

CREATE TABLE IF NOT EXISTS `de_wish` (
  `wish_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`wish_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `de_wish`
--

INSERT INTO `de_wish` (`wish_id`, `product_id`, `user_id`, `create_time`, `type`) VALUES
(7, 14, 3, 1442484831, 'product');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
