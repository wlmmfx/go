/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : thinkphp5

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2017-07-10 23:00:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for frontend_user
-- ----------------------------
DROP TABLE IF EXISTS `frontend_user`;
CREATE TABLE `frontend_user` (
  `user_id` int(100) NOT NULL AUTO_INCREMENT,
  `parent_id` int(128) DEFAULT '2',
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `apikey_value` varchar(60) DEFAULT NULL,
  `apikey_time` varchar(60) DEFAULT NULL,
  `logintime` varchar(128) DEFAULT NULL,
  `loginip` varchar(128) DEFAULT '127.0.0.1',
  `status` int(2) DEFAULT '0',
  `description` text,
  `create_time` int(11) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  `delete_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of frontend_user
-- ----------------------------
INSERT INTO `frontend_user` VALUES ('8', '0', 'Administrator', '21232f297a57a5a743894a0e4a801fc3', '007c91306de15735ad90b7d5633556d5b0633429', '1452649122', null, null, '1', null, null, null, null);
INSERT INTO `frontend_user` VALUES ('18', '1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '99809701ba179cae883a32db741422c20eda4b9d', '1453168690', '1498875746', '127.0.0.1', '0', null, null, '1498875746', null);
INSERT INTO `frontend_user` VALUES ('27', '2', '33333333333', '1234561975', null, null, null, '127.0.0.1', '0', null, null, null, null);
INSERT INTO `frontend_user` VALUES ('28', '2', 'Guest1722318623', '1234569003', null, null, null, '127.0.0.1', '0', null, null, null, null);
INSERT INTO `frontend_user` VALUES ('29', '2', 'tinywan7', '83008', null, null, '1487498134', '127.0.0.1', '0', null, null, null, null);
INSERT INTO `frontend_user` VALUES ('62', '2', 'USER23281', '6382c4bb2e89bf078200b9ed512099e5', '756684177@qq.com', null, null, '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('63', '2', 'USER26229', '18c9b6e9b5337141b5a3055b48470df3', '756684177@qq.com', null, '1498874909', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('64', '2', 'USER57644', '09950ba68596afccb1e9ca362f213a46', '756684177@qq.com', null, '1498874930', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('65', '2', 'USER53073', '791d5462ceb04c1b65b71e08eb176c48', '756684177@qq.com', null, '1498874931', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('66', '2', 'USER44937', '936fbb9d68ab2ced1eeb8755e46a9ec9', '756684177@qq.com', '1498874941', '1498874941', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('67', '2', 'USER37533', '03c2378383a599f5d086e6f94e04aadf', '756684177@qq.com', '1498874941', '1498874941', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('68', '2', 'USER82034', '20c03eab9d57cb1d65fbad914f2d3d10', '756684177@qq.com', '1498874942', '1498874942', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('69', '2', 'USER51486', '577618c5a9540bac478568db034fdec3', '756684177@qq.com', '1498874942', '1498874942', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('70', '2', 'USER15811', '782d4765e736479b97b8ce2dd2678d4c', '756684177@qq.com', '1498874942', '1498874942', '127.0.0.1', '0', '模型修改器', null, null, null);
INSERT INTO `frontend_user` VALUES ('71', '2', 'USER16806', '84443984e93823f619a155d154b55ed9', '756684177@qq.com', '1498875512', '1498875512', '127.0.0.1', '0', '模型修改器', '1498875511', '1498875511', null);
INSERT INTO `frontend_user` VALUES ('72', '2', 'USER43142', '263053c3fd86042892e466437f0296c1', '756684177@qq.com', '1498875589', '1498875589', '127.0.0.1', '0', '模型修改器', '1498875589', '1498875589', null);
INSERT INTO `frontend_user` VALUES ('73', '2', 'USER5368', 'bf41738e409ae83dd89c40774fb13661', '756684177@qq.com', '1498875590', '1498875590', '127.0.0.1', '0', '模型修改器', '1498875590', '1498875590', null);
INSERT INTO `frontend_user` VALUES ('74', '2', 'USER10903', '10c5ce340005ef9aca4d126729b9b40a', '756684177@qq.com', '1498875590', '1498875590', '127.0.0.1', '0', '模型修改器', '1498875590', '1498875590', null);
INSERT INTO `frontend_user` VALUES ('75', '2', 'USER39047', '4fd642b2d8c68925fea769707cf940e6', '756684177@qq.com', '1498875590', '1498875590', '127.0.0.1', '0', '模型修改器', '1498875590', '1498875590', null);
INSERT INTO `frontend_user` VALUES ('76', '2', 'USER15969', '330e8a5f6e23bc72c8ca9d37ad150eba', '756684177@qq.com', '1498875590', '1498875590', '127.0.0.1', '0', '模型修改器', '1498875590', '1498875590', null);
INSERT INTO `frontend_user` VALUES ('77', '2', 'USER89718', '1afd7d654865340cd205c758177e541c', '756684177@qq.com', '1498875699', '1498875699', '127.0.0.1', '0', '模型修改器', '1498875699', '1498875699', null);
INSERT INTO `frontend_user` VALUES ('78', '2', 'USER5215', '5f11d9d099133a192ff026f02c143552', '756684177@qq.com', '1498875739', '1498875739', '127.0.0.1', '0', '模型修改器', '1498875739', '1498875739', null);
INSERT INTO `frontend_user` VALUES ('79', '2', 'USER79257', '0b4de3e8e8663ee36179745824222278', '756684177@qq.com', '1498875740', '1498876468', '127.0.0.1', '1', '模型修改器', '1498875740', '1498876468', '1498876468');
INSERT INTO `frontend_user` VALUES ('80', '2', 'USER79537', 'e7433367293a1fb0902d6aeec6776692', '756684177@qq.com', '1498876058', '1498876058', '127.0.0.1', '0', '模型修改器', '1498876058', '1498876058', null);
INSERT INTO `frontend_user` VALUES ('81', '2', 'USER73745', '8b92f975055c923b671818c1381ba832', '756684177@qq.com', '1498876062', '1498876062', '127.0.0.1', '0', '模型修改器', '1498876062', '1498876062', null);
INSERT INTO `frontend_user` VALUES ('82', '2', 'USER12435', 'e25963baba0231f34cad0833f8b2244b', '756684177@qq.com', '1498876141', '1498876141', '127.0.0.1', '0', '模型修改器', '1498876141', null, null);
INSERT INTO `frontend_user` VALUES ('83', '2', 'USER96780', 'e2cd092e3c4b949809c41cefe966db7a', '756684177@qq.com', '1498876142', '1498876142', '127.0.0.1', '0', '模型修改器', '1498876142', null, null);

-- ----------------------------
-- Table structure for resty_alipay
-- ----------------------------
DROP TABLE IF EXISTS `resty_alipay`;
CREATE TABLE `resty_alipay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `subject` varchar(100) DEFAULT '0' COMMENT '订单标题',
  `total_fee` float(10,2) DEFAULT '0.00' COMMENT '价格',
  `trade_no` varchar(30) DEFAULT '0' COMMENT '支付宝交易号',
  `out_trade_no` varchar(20) DEFAULT '0' COMMENT '商户网站唯一订单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_alipay
-- ----------------------------
INSERT INTO `resty_alipay` VALUES ('1', '商品测试1', '0.01', '1', 'CS400699250001123');
INSERT INTO `resty_alipay` VALUES ('2', '商品测试2', '0.01', '1', 'CS400699250002123545');
INSERT INTO `resty_alipay` VALUES ('3', 'iPhone 8 Plus', '0.01', '0', 'CS4006992500031231');

-- ----------------------------
-- Table structure for resty_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `resty_auth_group`;
CREATE TABLE `resty_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_auth_group
-- ----------------------------
INSERT INTO `resty_auth_group` VALUES ('1', '1', '1', '1111111');

-- ----------------------------
-- Table structure for resty_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `resty_auth_group_access`;
CREATE TABLE `resty_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_auth_group_access
-- ----------------------------

-- ----------------------------
-- Table structure for resty_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `resty_auth_rule`;
CREATE TABLE `resty_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for resty_category
-- ----------------------------
DROP TABLE IF EXISTS `resty_category`;
CREATE TABLE `resty_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `pid` int(10) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `alias` varchar(32) DEFAULT NULL COMMENT '别名',
  `type` varchar(32) DEFAULT NULL COMMENT '类型',
  `level` int(10) DEFAULT '0' COMMENT '级别',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `userId` varchar(32) DEFAULT NULL,
  `orgId` varchar(16) DEFAULT NULL COMMENT '组织ID',
  `order` int(32) DEFAULT NULL COMMENT '排序',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_category
-- ----------------------------
INSERT INTO `resty_category` VALUES ('75', '开源项目', '0', null, null, null, '1', '1', null, null, null, '开源项目11');
INSERT INTO `resty_category` VALUES ('76', '视频课程', '0', null, null, null, '0', '1', null, null, null, '视频课程');
INSERT INTO `resty_category` VALUES ('77', '新闻', '0', null, null, null, '12', '1', null, null, null, '新闻新闻新闻');
INSERT INTO `resty_category` VALUES ('78', '新浪新闻', '77', null, null, null, '0', '1', null, null, null, '新浪新闻');
INSERT INTO `resty_category` VALUES ('80', 'QQ新闻', '95', null, null, null, '123', '1', null, null, null, 'QQ新闻');
INSERT INTO `resty_category` VALUES ('81', 'Wehcat新闻', '95', null, null, null, '1', '1', null, null, null, 'Wehcat新闻');
INSERT INTO `resty_category` VALUES ('82', '微博新闻', '78', null, null, null, '0', '1', null, null, null, '微博新闻微博新闻');
INSERT INTO `resty_category` VALUES ('83', '佛教新闻', '78', null, null, null, '12', '1', null, null, null, '佛教新闻QQ');
INSERT INTO `resty_category` VALUES ('84', '电视剧', '76', null, null, null, '0', '1', null, null, null, '电视剧电视剧');
INSERT INTO `resty_category` VALUES ('85', '电影', '76', null, null, null, '0', '1', null, null, null, '电影');
INSERT INTO `resty_category` VALUES ('87', '古代', '84', null, null, null, '0', '1', null, null, null, '古代');
INSERT INTO `resty_category` VALUES ('88', '现代', '84', null, null, null, '0', '1', null, null, null, '现代');
INSERT INTO `resty_category` VALUES ('90', '城市', '88', null, null, null, '0', '1', null, null, null, '城市');
INSERT INTO `resty_category` VALUES ('91', '国外', '88', null, null, null, '0', '1', null, null, null, '国外');
INSERT INTO `resty_category` VALUES ('93', '二级', '88', null, null, null, '0', '1', null, null, null, '二级');
INSERT INTO `resty_category` VALUES ('94', '三级', '88', null, null, null, '0', '1', null, null, null, '三级');
INSERT INTO `resty_category` VALUES ('95', '腾讯新闻', '0', null, null, null, '1', '1', null, null, null, '腾讯新闻');
INSERT INTO `resty_category` VALUES ('96', ' 弍萬80端口测试', '75', null, null, null, '0', '1', null, null, null, '关于鞋子');

-- ----------------------------
-- Table structure for resty_config
-- ----------------------------
DROP TABLE IF EXISTS `resty_config`;
CREATE TABLE `resty_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system` varchar(255) DEFAULT NULL COMMENT '系统配置不能为空',
  `wechat` varchar(255) DEFAULT NULL COMMENT '公众号配置不能为空',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_config
-- ----------------------------
INSERT INTO `resty_config` VALUES ('1', 'Tinywan', '26');
INSERT INTO `resty_config` VALUES ('2', 'Tinywan', '26');
INSERT INTO `resty_config` VALUES ('3', 'Tinywan', '26');
INSERT INTO `resty_config` VALUES ('4', 'Tinywan', '26');
INSERT INTO `resty_config` VALUES ('5', 'Tinywan', '26');

-- ----------------------------
-- Table structure for resty_file
-- ----------------------------
DROP TABLE IF EXISTS `resty_file`;
CREATE TABLE `resty_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `min_path` varchar(100) NOT NULL,
  `path` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_file
-- ----------------------------
INSERT INTO `resty_file` VALUES ('35', '1', 'Product/2016-08-17/mini_57b47810d7435.jpg', 'Product/2016-08-17/57b47810d7435.jpg');
INSERT INTO `resty_file` VALUES ('36', '1', 'Product/2016-08-17/mini_57b478cbf362c.jpg', 'Product/2016-08-17/57b478cbf362c.jpg');
INSERT INTO `resty_file` VALUES ('37', '1', 'Product/2016-08-17/mini_57b478f91ffcf.jpg', 'Product/2016-08-17/57b478f91ffcf.jpg');
INSERT INTO `resty_file` VALUES ('38', '1', 'Product/2016-08-17/mini_57b479456851c.jpg', 'Product/2016-08-17/57b479456851c.jpg');
INSERT INTO `resty_file` VALUES ('39', '1', 'Product/2016-08-17/mini_57b4795d8ba20.jpg', 'Product/2016-08-17/57b4795d8ba20.jpg');
INSERT INTO `resty_file` VALUES ('40', '1', 'Product/2016-08-17/mini_57b47a0768546.jpg', 'Product/2016-08-17/57b47a0768546.jpg');
INSERT INTO `resty_file` VALUES ('41', '1', 'Product/2016-08-17/mini_57b47ab41b150.jpg', 'Product/2016-08-17/57b47ab41b150.jpg');
INSERT INTO `resty_file` VALUES ('42', '1', 'Product/2016-08-17/mini_57b47accd74d7.jpg', 'Product/2016-08-17/57b47accd74d7.jpg');
INSERT INTO `resty_file` VALUES ('43', '1', 'Product/2016-08-17/mini_57b47aed0c88d.jpg', 'Product/2016-08-17/57b47aed0c88d.jpg');
INSERT INTO `resty_file` VALUES ('44', '1', 'Product/2016-08-17/mini_57b47d4098bb0.jpg', 'Product/2016-08-17/57b47d4098bb0.jpg');
INSERT INTO `resty_file` VALUES ('45', '1', 'Product/2016-08-17/mini_57b47d8417502.jpg', 'Product/2016-08-17/57b47d8417502.jpg');
INSERT INTO `resty_file` VALUES ('46', '1', 'Product/2016-08-17/mini_57b47da53d457.jpg', 'Product/2016-08-17/57b47da53d457.jpg');
INSERT INTO `resty_file` VALUES ('47', '1', 'Product/2016-08-17/mini_57b47e44692c1.jpg', 'Product/2016-08-17/57b47e44692c1.jpg');
INSERT INTO `resty_file` VALUES ('48', '1', 'Product/2016-08-17/mini_57b47e66538ae.jpg', 'Product/2016-08-17/57b47e66538ae.jpg');
INSERT INTO `resty_file` VALUES ('49', '1', 'Product/2016-08-17/mini_57b47eb99f333.jpg', 'Product/2016-08-17/57b47eb99f333.jpg');
INSERT INTO `resty_file` VALUES ('50', '1', 'Product/2016-08-17/mini_57b47ef511f6f.jpg', 'Product/2016-08-17/57b47ef511f6f.jpg');
INSERT INTO `resty_file` VALUES ('51', '1', 'Product/2016-08-17/mini_57b480cd2751b.jpg', 'Product/2016-08-17/57b480cd2751b.jpg');
INSERT INTO `resty_file` VALUES ('52', '1', 'Product/2016-08-17/mini_57b48115165eb.jpg', 'Product/2016-08-17/57b48115165eb.jpg');
INSERT INTO `resty_file` VALUES ('53', '1', 'Product/2016-08-17/mini_57b4816140045.jpg', 'Product/2016-08-17/57b4816140045.jpg');
INSERT INTO `resty_file` VALUES ('54', '2', 'Product/2016-08-17/mini_57b481b0b4196.jpg', 'Product/2016-08-17/57b481b0b4196.jpg');
INSERT INTO `resty_file` VALUES ('55', '1', 'Product/2016-08-17/mini_57b483c375905.jpg', 'Product/2016-08-17/57b483c375905.jpg');
INSERT INTO `resty_file` VALUES ('57', '26', 'Product/2016-08-17/mini_57b48493cdcfb.jpg', 'Product/2016-08-17/57b48493cdcfb.jpg');
INSERT INTO `resty_file` VALUES ('58', '29', 'Product/2016-08-17/mini_57b486725c59b.jpg', 'Product/2016-08-17/57b486725c59b.jpg');
INSERT INTO `resty_file` VALUES ('60', '35', 'Product/2016-08-20/mini_57b7f1b323690.jpg', 'Product/2016-08-20/57b7f1b323690.jpg');
INSERT INTO `resty_file` VALUES ('62', '1', 'Product/2016-08-20/mini_57b803956e123.jpg', 'Product/2016-08-20/57b803956e123.jpg');
INSERT INTO `resty_file` VALUES ('64', '1', 'Product/2016-08-20/mini_57b804d07ffea.jpg', 'Product/2016-08-20/57b804d07ffea.jpg');
INSERT INTO `resty_file` VALUES ('79', '53', 'Product/2016-08-21/mini_57b91be818c06.jpg', 'Product/2016-08-21/57b91be818c06.jpg');
INSERT INTO `resty_file` VALUES ('80', '1', 'Product/2016-08-21/mini_57b91c614af52.jpg', 'Product/2016-08-21/57b91c614af52.jpg');
INSERT INTO `resty_file` VALUES ('81', '54', 'Product/2016-08-21/mini_57b91db2c2528.jpg', 'Product/2016-08-21/57b91db2c2528.jpg');
INSERT INTO `resty_file` VALUES ('82', '55', 'Product/2016-08-21/mini_57b91e8f93f38.jpg', 'Product/2016-08-21/mini_57b91e8f93f38.jpg');
INSERT INTO `resty_file` VALUES ('83', '56', 'Product/2016-08-21/mini_57b9221439e69.jpg', 'Product/2016-08-21/mini_57b9221439e69.jpg');
INSERT INTO `resty_file` VALUES ('84', '59', 'Product/2016-08-21/mini_57b92362b466c.jpg', 'Product/2016-08-21/mini_57b92362b466c.jpg');
INSERT INTO `resty_file` VALUES ('85', '60', 'Product/2016-08-22/mini_57baff688d243.png', 'Product/2016-08-22/mini_57baff688d243.png');
INSERT INTO `resty_file` VALUES ('86', '61', 'Product/2016-12-07/mini_5847c3fd2ad53.jpg', 'Product/2016-12-07/mini_5847c3fd2ad53.jpg');

-- ----------------------------
-- Table structure for resty_logs
-- ----------------------------
DROP TABLE IF EXISTS `resty_logs`;
CREATE TABLE `resty_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(100) NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `app` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `method` varchar(100) NOT NULL,
  `query_string` text NOT NULL,
  `is_desc` varchar(100) NOT NULL,
  `desc` varchar(100) NOT NULL,
  `ipaddr` varchar(100) NOT NULL,
  `unique_flag` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_logs
-- ----------------------------
INSERT INTO `resty_logs` VALUES ('27', '158', '2016-09-01 00:04:01', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('28', '158', '2016-09-01 00:15:42', 'admin4', 'admin4', 'Home', 'Index', 'userAgent', '', '0', '', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('29', '158', '2016-09-01 00:24:13', 'admin4', 'admin4', 'Home', 'AdminUser', 'createadminuser', 'admin888--123456--1', '1', '给ID为:[1]的角色,新增用户:[admin888],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('30', '161', '2016-09-01 00:24:23', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('31', '161', '2016-09-01 00:29:47', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('32', '161', '2016-09-01 00:38:19', 'admin888', 'admin888', 'Home', 'AdminUser', 'createadminuser', 'admin666--123456--2', '1', '给ID为:[2]的角色,新增用户:[admin666],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('33', '162', '2016-09-01 00:38:45', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('34', '161', '2016-09-01 00:39:22', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('35', '158', '2016-09-01 09:31:09', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('36', '161', '2016-09-01 09:31:26', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('37', '161', '2016-09-01 09:32:54', 'admin888', 'admin888', 'Home', 'AdminUser', 'delUser', '147', '1', '删除用户ID:147成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('38', '161', '2016-09-01 09:34:27', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('39', '161', '2016-09-01 09:34:57', 'admin888', 'admin888', 'Home', 'AdminUser', 'createadminuser', 'admin110--123456--1', '1', '给ID为:[1]的角色,新增用户:[admin110],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('40', '162', '2016-09-01 09:37:05', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('41', '161', '2016-09-01 10:07:25', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('42', '161', '2016-09-01 10:07:48', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('43', '161', '2016-09-01 10:08:46', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('44', '161', '2016-09-01 10:09:12', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('45', '158', '2016-09-01 10:09:37', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('46', '162', '2016-09-01 10:13:07', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('47', '158', '2016-09-01 10:27:09', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('48', '162', '2016-09-01 10:27:20', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('49', '158', '2016-09-01 10:29:45', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('50', '162', '2016-09-01 10:32:34', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('51', '162', '2016-09-01 13:55:46', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('52', '161', '2016-09-01 14:04:06', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('53', '162', '2016-09-01 14:27:38', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('54', '158', '2016-09-01 14:47:09', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('55', '157', '2016-09-01 14:47:56', 'admin456', 'admin456', 'Home', 'Login', 'checkLogin', 'admin456--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('56', '158', '2016-09-01 14:50:22', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('57', '162', '2016-09-01 14:52:07', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('58', '158', '2016-09-01 14:53:22', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('59', '162', '2016-09-01 14:53:48', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('60', '158', '2016-09-01 14:56:04', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('61', '162', '2016-09-01 14:57:18', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('62', '158', '2016-09-01 15:11:33', 'admin4', 'admin4', 'Home', 'Login', 'checkLogin', 'admin4--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('63', '158', '2016-09-01 15:11:51', 'admin4', 'admin4', 'Home', 'Rbac', 'delUser', '152', '1', '删除用户ID:152成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('64', '158', '2016-09-01 15:11:59', 'admin4', 'admin4', 'Home', 'Rbac', 'delUser', '151', '1', '删除用户ID:151成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('65', '158', '2016-09-01 15:17:26', 'admin4', 'admin4', 'Home', 'Rbac', 'createadminuser', 'admin123789--123456--2', '1', '给ID为:[2]的角色,新增用户:[admin123789],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('66', '158', '2016-09-01 15:17:42', 'admin4', 'admin4', 'Home', 'Rbac', 'delUser', '159', '1', '删除用户ID:159成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('67', '158', '2016-09-01 15:17:48', 'admin4', 'admin4', 'Home', 'Rbac', 'delUser', '157', '1', '删除用户ID:157成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('68', '158', '2016-09-01 15:17:55', 'admin4', 'admin4', 'Home', 'Rbac', 'delUser', '158', '1', '删除用户ID:158成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('69', '162', '2016-09-01 16:05:29', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('70', '161', '2016-09-01 16:08:45', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('71', '161', '2016-09-01 16:14:38', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('72', '161', '2016-09-01 17:37:41', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('73', '164', '2016-09-01 17:43:51', 'admin123789', 'admin123789', 'Home', 'Login', 'checkLogin', 'admin123789--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('74', '164', '2016-09-01 17:45:33', 'admin123789', 'admin123789', 'Home', 'Login', 'checkLogin', 'admin123789--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('75', '161', '2016-09-01 17:47:30', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('76', '164', '2016-09-01 17:48:38', 'admin123789', 'admin123789', 'Home', 'Login', 'checkLogin', 'admin123789--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('77', '161', '2016-09-01 17:49:54', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('78', '164', '2016-09-01 17:50:23', 'admin123789', 'admin123789', 'Home', 'Login', 'checkLogin', 'admin123789--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('79', '162', '2016-09-01 17:52:00', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('80', '161', '2016-09-01 18:03:37', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('81', '162', '2016-09-01 18:06:38', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('82', '161', '2016-09-01 18:14:19', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('83', '162', '2016-09-01 18:17:27', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('84', '161', '2016-09-01 18:21:29', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('85', '162', '2016-09-01 18:24:54', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('86', '164', '2016-09-01 20:46:37', 'admin123789', 'admin123789', 'Home', 'Login', 'checkLogin', 'admin123789--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('87', '164', '2016-09-01 20:49:10', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '108', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('88', '164', '2016-09-01 20:49:27', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '164', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('89', '164', '2016-09-01 20:52:23', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '108', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('90', '164', '2016-09-01 20:53:04', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '164', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('91', '164', '2016-09-01 20:53:14', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('92', '164', '2016-09-01 20:55:21', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '164', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('93', '164', '2016-09-01 20:55:38', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '164', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('94', '164', '2016-09-01 20:58:46', 'admin123789', 'admin123789', 'Home', 'Rbac', 'userStatus', '', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('95', '161', '2016-09-01 20:59:40', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('96', '161', '2016-09-01 20:59:56', 'admin888', 'admin888', 'Home', 'Rbac', 'delUser', '108', '1', '删除用户ID:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('97', '161', '2016-09-01 21:00:51', 'admin888', 'admin888', 'Home', 'Rbac', 'delUser', '108', '1', '删除用户ID:108成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('98', '162', '2016-09-01 21:04:45', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('99', '162', '2016-09-01 21:05:08', 'admin666', 'admin666', 'Home', 'Rbac', 'userStatus', '', '1', '设置用户状态:失败', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('100', '161', '2016-09-01 22:28:01', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('101', '161', '2016-09-01 22:50:24', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('102', '161', '2016-09-01 22:51:16', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('103', '162', '2016-09-01 23:40:11', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('104', '161', '2016-09-01 23:43:00', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('105', '162', '2016-09-01 23:44:29', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('106', '162', '2016-09-01 23:44:56', 'admin666', 'admin666', 'Home', 'Rbac', 'createadminuser', '管理人--123456--26', '1', '给ID为:[26]的角色,新增用户:[管理人],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('107', '161', '2016-09-01 23:48:31', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('108', '162', '2016-09-02 10:36:09', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('109', '161', '2016-09-02 10:38:15', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('110', '162', '2016-09-02 10:39:40', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('111', '161', '2016-09-02 10:40:32', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('112', '161', '2016-09-02 11:02:18', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('113', '161', '2016-09-02 11:05:43', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('114', '161', '2016-09-02 11:08:58', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('115', '161', '2016-09-04 09:27:22', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('116', '161', '2016-09-04 09:27:51', 'admin888', 'admin888', 'Home', 'Rbac', 'delUser', '163', '1', '删除用户ID:163成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('117', '161', '2016-09-04 09:29:32', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('118', '161', '2016-09-04 09:30:06', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('119', '162', '2016-09-04 09:31:24', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('120', '161', '2016-09-06 14:18:29', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('121', '161', '2016-09-08 09:15:05', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('122', '161', '2016-09-08 09:17:16', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('123', '161', '2016-09-08 09:18:26', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('124', '161', '2016-09-08 10:17:03', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('125', '161', '2016-09-08 10:36:57', 'admin888', 'admin888', 'Home', 'Rbac', 'createadminuser', 'admin000--123456--30', '1', '给ID为:[30]的角色,新增用户:[admin000],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('126', '166', '2016-09-08 10:37:07', 'admin000', 'admin000', 'Home', 'Login', 'checkLogin', 'admin000--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('127', '161', '2016-09-08 10:37:34', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('128', '161', '2016-09-08 10:38:46', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('129', '166', '2016-09-08 11:51:01', 'admin000', 'admin000', 'Home', 'Login', 'checkLogin', 'admin000--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('130', '162', '2016-09-08 11:51:15', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('131', '161', '2016-09-08 11:57:04', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('132', '161', '2016-09-08 14:00:16', 'admin888', 'admin888', 'Home', 'Rbac', 'createadminuser', '王者--123456--30', '1', '给ID为:[30]的角色,新增用户:[王者],密码为:[123456]其他参数Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('133', '167', '2016-09-08 14:00:29', '王者', '王者', 'Home', 'Login', 'checkLogin', '王者--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('134', '161', '2016-09-08 14:00:52', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('135', '167', '2016-09-08 14:01:51', '王者', '王者', 'Home', 'Login', 'checkLogin', '王者--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('136', '161', '2016-09-08 14:19:27', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('137', '162', '2016-09-08 14:19:53', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('138', '161', '2016-09-08 15:09:51', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('139', '161', '2016-09-08 15:10:04', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限1', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('140', '161', '2016-09-08 15:10:08', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限1', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('141', '161', '2016-09-08 15:10:34', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('142', '161', '2016-09-08 15:11:40', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限166_1--171_2--172_3--173_3--174_3--175_3--176_3--177_2--178_3--179_3--192_3--232_3--187_', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('143', '161', '2016-09-08 15:13:31', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限191--3', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('144', '161', '2016-09-08 15:13:47', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限166_1--171_2--172_3--173_3--174_3--175_3--176_3--177_2--178_3--179_3--192_3--232_3--187_', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('145', '161', '2016-09-08 15:13:58', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限166_1--219_2--220_3--221_3', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('146', '161', '2016-09-08 15:14:48', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'tmpcreatenode--权限列表权限列表--icon-calendar--3--203', '1', '添加新节点:Array', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('147', '161', '2016-09-08 15:16:24', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'tmpcreatenode123--权限列表权12312--icon-calendar--3--203', '1', '添加新节点:tmpcreatenode123-权限列表权12312-icon-calendar-3-203', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('148', '161', '2016-09-08 15:17:26', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '237', '1', '删除节点:', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('149', '161', '2016-09-08 15:19:48', 'admin888', 'admin888', 'Home', 'Rbac', 'delRole', '31', '1', '删除角色ID:31成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('150', '146', '2016-09-08 15:20:27', 'Tinywan', 'Tinywan', 'Home', 'Login', 'checkLogin', 'Tinywan--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('151', '146', '2016-09-08 15:21:05', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'createnode', 'tmpcreatenode456--权限列表权12312--icon-calendar--3--203', '1', '添加新节点:tmpcreatenode456|权限列表权12312|icon-calendar|3|203', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('152', '146', '2016-09-08 15:21:48', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'createnode', 'actionLog--日志列表--icon-calendar--3--219', '1', '添加新节点:actionLog|日志列表|icon-calendar|3|219', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('153', '146', '2016-09-08 15:22:03', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色:超级管理员设置权限166_1--171_2--172_3--173_3--174_3--175_3--176_3--177_2--178_3--179_3--192_3--232_3--187', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('154', '146', '2016-09-08 15:22:14', 'Tinywan', 'Tinywan', 'Home', 'Login', 'checkLogin', 'Tinywan--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('155', '146', '2016-09-08 15:39:06', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限Array--30', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('156', '146', '2016-09-08 15:40:04', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:最强王者设置权限221--3', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('157', '146', '2016-09-08 15:41:02', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:设置权限221--3', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('158', '146', '2016-09-08 15:41:23', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色:30设置权限221--3', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('159', '146', '2016-09-08 15:43:06', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色ID为:30设置权限166_1|171_2|172_3|173_3|174_3|175_3|176_3|203_2|205_3|206_3|207_3|209_3|210_3|211_3|212', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('160', '146', '2016-09-08 15:47:45', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色ID为:30设置权限166|171|172|173|174|175|176|203|205|206|207|209|210|211|212|213|214|215|216|217|218|229', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('161', '146', '2016-09-08 15:49:07', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色ID为：30设置节点ID：166|219|220|221|239', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('162', '146', '2016-09-08 15:49:47', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--26', '1', '给角色ID为:26：设置节点ID：166|203|205|206|207|209|210|211|212|213|214|215|216|217|218|229|235|236|238', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('163', '146', '2016-09-08 15:50:39', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--26', '1', '给角色ID为:26：设置节点ID：166|187|188|189|190|191', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('164', '146', '2016-09-08 15:52:47', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'delNode', '238', '1', '删除节点:238', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('165', '146', '2016-09-08 15:53:41', 'Tinywan', 'Tinywan', 'Home', 'Rbac', 'addNode', 'Array--2', '1', '给角色ID为:2：设置节点ID：166|171|173|174|175|177|178|179|187|203|205|207|209|219|239', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('166', '162', '2016-09-08 15:54:00', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('167', '161', '2016-09-08 17:34:34', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('168', '161', '2016-09-08 17:40:08', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色ID为:30：设置节点ID：166|219|220|221|239', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('169', '162', '2016-09-08 17:40:30', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('170', '161', '2016-09-08 17:40:51', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('171', '161', '2016-09-08 17:41:25', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色ID为:30：设置节点ID：166|203|218|219|220|239', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('172', '162', '2016-09-08 17:41:35', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('173', '161', '2016-09-09 08:38:45', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('174', '161', '2016-09-10 16:27:11', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('175', '161', '2016-09-10 16:27:56', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('176', '161', '2016-09-12 14:52:29', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('177', '161', '2016-09-12 14:52:41', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('178', '161', '2016-09-13 16:38:34', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'Database--数据库配置--icon-calendar--3--219', '1', '添加新节点:Database|数据库配置|icon-calendar|3|219', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('179', '161', '2016-09-13 16:38:42', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('180', '161', '2016-09-13 16:39:09', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('181', '161', '2016-09-13 16:42:22', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('182', '161', '2016-09-13 16:42:52', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('183', '161', '2016-09-13 17:19:05', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('184', '161', '2016-09-13 17:19:27', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('185', '162', '2016-09-13 17:20:07', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('186', '161', '2016-09-13 17:20:43', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('187', '161', '2016-09-13 17:54:06', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'setAuth--权限开关--icon-calendar--3--219', '1', '添加新节点:setAuth|权限开关|icon-calendar|3|219', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('188', '161', '2016-09-13 17:54:13', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('189', '162', '2016-09-13 17:55:04', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('190', '161', '2016-09-13 17:55:44', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('191', '162', '2016-09-13 17:56:16', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('192', '161', '2016-09-13 17:56:41', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('193', '162', '2016-09-13 17:57:46', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('194', '161', '2016-09-13 17:58:08', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('195', '161', '2016-09-13 18:03:47', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'EmailManage--邮件管理--icon-calendar--2--166', '1', '添加新节点:EmailManage|邮件管理|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('196', '161', '2016-09-13 18:04:10', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'sendMail--发送邮件--icon-calendar--3--242', '1', '添加新节点:sendMail|发送邮件|icon-calendar|3|242', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('197', '161', '2016-09-13 18:04:32', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('198', '161', '2016-09-13 18:04:53', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('199', '161', '2016-09-13 18:11:36', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'EmailManage--发送邮件2--icon-calendar--3--242', '1', '添加新节点:EmailManage|发送邮件2|icon-calendar|3|242', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('200', '161', '2016-09-13 18:11:44', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '244', '1', '删除节点:244', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('201', '161', '2016-09-13 18:13:24', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--30', '1', '给角色ID为:30：设置节点ID：166|187|188|189|190|191', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('202', '161', '2016-09-18 13:35:27', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('203', '162', '2016-09-27 12:59:23', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('204', '161', '2016-09-27 13:00:03', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('205', '161', '2016-10-09 08:54:33', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('206', '161', '2016-10-28 09:07:34', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('207', '162', '2016-10-31 11:15:50', 'admin666', 'admin666', 'Home', 'Login', 'checkLogin', 'admin666--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('208', '161', '2016-10-31 11:16:29', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('209', '161', '2016-10-31 11:16:42', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--2', '1', '给角色ID为:2：设置节点ID：166|171|173|174|175|177|178|179|187|219|239', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('210', '161', '2016-10-31 11:17:40', 'admin888', 'admin888', 'Home', 'Rbac', 'delUser', '167', '1', '删除用户ID:167成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('211', '161', '2016-10-31 13:15:32', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('212', '161', '2016-10-31 13:16:54', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('213', '161', '2016-10-31 13:31:21', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('214', '161', '2016-10-31 13:33:38', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('215', '161', '2016-10-31 13:58:43', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('216', '161', '2016-10-31 14:34:30', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('217', '161', '2016-10-31 14:35:01', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('218', '161', '2016-10-31 14:48:36', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('219', '161', '2016-10-31 14:52:32', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('220', '161', '2016-10-31 15:10:22', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('221', '161', '2016-10-31 15:11:09', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('222', '161', '2016-10-31 15:24:16', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456--on', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('223', '161', '2016-11-01 08:58:38', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('224', '161', '2016-11-14 10:37:55', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('225', '161', '2016-11-21 08:51:15', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'Redis--Redis管理--icon-calendar--2--166', '1', '添加新节点:Redis|Redis管理|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('226', '161', '2016-11-21 08:51:33', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('227', '161', '2016-11-21 08:52:36', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', '主页--index--icon-calendar--3--245', '1', '添加新节点:主页|index|icon-calendar|3|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('228', '161', '2016-11-21 08:53:15', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'userRegister--微博注册--icon-calendar--3--245', '1', '添加新节点:userRegister|微博注册|icon-calendar|3|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('229', '161', '2016-11-21 08:53:28', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'userLogin--微博登陆--icon-calendar--3--245', '1', '添加新节点:userLogin|微博登陆|icon-calendar|3|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('230', '161', '2016-11-21 08:53:48', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'createContentByUserId--发表微博--icon-calendar--1--245', '1', '添加新节点:createContentByUserId|发表微博|icon-calendar|1|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('231', '161', '2016-11-21 08:54:24', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'getLastUserId--最新注册用户--icon-calendar--3--245', '1', '添加新节点:getLastUserId|最新注册用户|icon-calendar|3|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('232', '161', '2016-11-21 08:54:31', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '249', '1', '删除节点:249', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('233', '161', '2016-11-21 08:54:48', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'createContentByUserI--发表微博--icon-calendar--3--245', '1', '添加新节点:createContentByUserI|发表微博|icon-calendar|3|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('234', '161', '2016-11-21 08:55:05', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('235', '161', '2016-11-21 08:55:17', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('236', '161', '2016-11-21 08:56:14', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '246', '1', '删除节点:246', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('237', '161', '2016-11-21 08:56:26', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'index--主页--icon-calendar--3--245', '1', '添加新节点:index|主页|icon-calendar|3|245', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('238', '161', '2016-11-21 08:56:35', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('239', '161', '2016-11-21 08:56:47', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('240', '161', '2016-11-24 09:01:00', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'ECharts--ECharts图标表--icon-calendar--2--166', '1', '添加新节点:ECharts|ECharts图标表|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('241', '161', '2016-11-24 09:01:17', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'index--首页展示--icon-calendar--3--253', '1', '添加新节点:index|首页展示|icon-calendar|3|253', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('242', '161', '2016-11-24 09:01:48', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'getJson--异步JSON--icon-calendar--3--253', '1', '添加新节点:getJson|异步JSON|icon-calendar|3|253', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('243', '161', '2016-11-24 09:01:58', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('244', '161', '2016-11-24 09:02:31', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('245', '161', '2016-11-25 09:15:04', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('246', '161', '2016-11-30 13:25:14', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('247', '161', '2016-12-07 09:11:50', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('248', '161', '2016-12-07 09:12:47', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'WebRTC--WebRTC通信--icon-calendar--2--166', '1', '添加新节点:WebRTC|WebRTC通信|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('249', '161', '2016-12-07 09:13:10', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'index--首页--icon-calendar--3--256', '1', '添加新节点:index|首页|icon-calendar|3|256', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('250', '161', '2016-12-07 09:16:27', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'test--测试--icon-calendar--3--256', '1', '添加新节点:test|测试|icon-calendar|3|256', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('251', '161', '2016-12-07 09:17:10', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'getUserMedia--获同步流--icon-calendar--3--256', '1', '添加新节点:getUserMedia|获同步流|icon-calendar|3|256', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('252', '161', '2016-12-07 09:18:03', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|187|188|189|190|191|203|205|206|207|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('253', '161', '2016-12-07 09:18:08', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('254', '161', '2016-12-07 15:44:02', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('255', '161', '2016-12-07 16:12:12', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'shop--商城分类--icon-calendar--3--177', '1', '添加新节点:shop|商城分类|icon-calendar|3|177', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('256', '161', '2016-12-07 16:12:32', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'channel--频道分类--icon-calendar--3--177', '1', '添加新节点:channel|频道分类|icon-calendar|3|177', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('257', '161', '2016-12-07 16:12:51', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'system--系统分类--icon-calendar--3--177', '1', '添加新节点:system|系统分类|icon-calendar|3|177', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('258', '161', '2016-12-07 16:13:29', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'express--快递分类--icon-calendar--3--177', '1', '添加新节点:express|快递分类|icon-calendar|3|177', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('259', '161', '2016-12-07 16:13:57', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|260|261|262|263|187|188|189|190|191|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('260', '161', '2016-12-07 16:14:10', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('261', '161', '2016-12-09 13:17:42', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'icon--图标管理--icon-calendar--1--166', '1', '添加新节点:icon|图标管理|icon-calendar|1|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('262', '161', '2016-12-09 13:18:11', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '264', '1', '删除节点:264', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('263', '161', '2016-12-09 13:18:36', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'icon--图标管理--icon-calendar--2--166', '1', '添加新节点:icon|图标管理|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('264', '161', '2016-12-09 13:18:59', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'index--首页--icon-calendar--3--265', '1', '添加新节点:index|首页|icon-calendar|3|265', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('265', '161', '2016-12-09 13:19:19', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'gift--礼物管理--icon-calendar--2--166', '1', '添加新节点:gift|礼物管理|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('266', '161', '2016-12-09 13:19:33', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'index--首页--icon-calendar--3--267', '1', '添加新节点:index|首页|icon-calendar|3|267', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('267', '161', '2016-12-09 13:19:47', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|260|261|262|263|187|188|189|190|191|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('268', '161', '2016-12-09 13:20:08', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('269', '161', '2016-12-15 13:47:50', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('270', '161', '2016-12-16 13:05:47', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('271', '161', '2016-12-23 10:18:25', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('272', '161', '2017-01-03 11:34:11', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('273', '161', '2017-01-05 16:04:17', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('274', '161', '2017-01-09 10:59:13', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('275', '161', '2017-01-11 10:06:01', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('276', '161', '2017-01-11 15:27:19', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('277', '161', '2017-01-11 18:06:14', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'defaultIndex--原始模型数据--icon-calendar--3--253', '1', '添加新节点:defaultIndex|原始模型数据|icon-calendar|3|253', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('278', '161', '2017-01-11 18:06:35', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'index--异步数据--icon-calendar--1--0', '1', '添加新节点:index|异步数据|icon-calendar|1|0', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('279', '161', '2017-01-11 18:06:40', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '270', '1', '删除节点:270', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('280', '161', '2017-01-11 18:07:36', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'chart_type_step_line--StepLine折线图--icon-calendar--3--253', '1', '添加新节点:chart_type_step_line|StepLine折线图|icon-calendar|3|253', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('281', '161', '2017-01-11 18:07:56', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'chart_type_line--折线图堆叠--icon-calendar--3--253', '1', '添加新节点:chart_type_line|折线图堆叠|icon-calendar|3|253', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('282', '161', '2017-01-11 18:08:29', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('283', '161', '2017-01-11 18:09:43', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|260|261|262|263|187|188|189|190|191|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('284', '161', '2017-01-11 18:09:50', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('285', '161', '2017-01-11 18:12:57', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'FFmpeg--FFmpeg视频转码--icon-calendar--2--166', '1', '添加新节点:FFmpeg|FFmpeg视频转码|icon-calendar|2|166', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('286', '161', '2017-01-11 18:14:00', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'createFFmpeg--mpg转mp4格式--icon-calendar--3--273', '1', '添加新节点:createFFmpeg|mpg转mp4格式|icon-calendar|3|273', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('287', '161', '2017-01-11 18:14:26', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'mp4_to_mp3--mp4_to_mp3--icon-calendar--1--273', '1', '添加新节点:mp4_to_mp3|mp4_to_mp3|icon-calendar|1|273', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('288', '161', '2017-01-11 18:14:30', 'admin888', 'admin888', 'Home', 'Rbac', 'delNode', '275', '1', '删除节点:275', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('289', '161', '2017-01-11 18:14:39', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'mp4_to_mp3--mp4_to_mp3--icon-calendar--3--273', '1', '添加新节点:mp4_to_mp3|mp4_to_mp3|icon-calendar|3|273', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('290', '161', '2017-01-11 18:14:58', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'extracting_image--视频提取图片--icon-calendar--3--273', '1', '添加新节点:extracting_image|视频提取图片|icon-calendar|3|273', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('291', '161', '2017-01-11 18:15:37', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'FFProbe--编码h264格式--icon-calendar--3--273', '1', '添加新节点:FFProbe|编码h264格式|icon-calendar|3|273', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('292', '161', '2017-01-11 18:16:33', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|260|261|262|263|187|188|189|190|191|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('293', '161', '2017-01-11 18:16:43', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('294', '161', '2017-01-15 14:34:44', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('295', '161', '2017-01-16 09:03:13', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'WeChat--微信模块--icon-calendar--1--0', '1', '添加新节点:WeChat|微信模块|icon-calendar|1|0', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('296', '161', '2017-01-16 09:04:11', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'Index--首页管理--icon-calendar--2--279', '1', '添加新节点:Index|首页管理|icon-calendar|2|279', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('297', '161', '2017-01-16 09:05:04', 'admin888', 'admin888', 'Home', 'Rbac', 'createnode', 'Addons--添加Addons--icon-calendar--3--280', '1', '添加新节点:Addons|添加Addons|icon-calendar|3|280', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('298', '161', '2017-01-16 09:05:44', 'admin888', 'admin888', 'Home', 'Rbac', 'addNode', 'Array--1', '1', '给角色ID为:1：设置节点ID：166|171|172|173|174|175|176|177|178|179|192|232|260|261|262|263|187|188|189|190|191|', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('299', '161', '2017-01-16 09:07:01', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('300', '161', '2017-02-04 13:05:04', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('301', '161', '2017-02-19 17:25:52', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('302', '161', '2017-03-26 18:08:24', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');
INSERT INTO `resty_logs` VALUES ('303', '161', '2017-03-26 22:27:47', 'admin888', 'admin888', 'Home', 'Login', 'checkLogin', 'admin888--123456', '1', '登陆成功', '127.0.0.1', 'system');

-- ----------------------------
-- Table structure for resty_user
-- ----------------------------
DROP TABLE IF EXISTS `resty_user`;
CREATE TABLE `resty_user` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL COMMENT '平台用户名',
  `password` varchar(60) DEFAULT NULL COMMENT '密码',
  `logintime` varchar(128) DEFAULT NULL COMMENT '登陆时间',
  `loginip` text COMMENT '登陆Ip地址',
  `status` int(2) DEFAULT '0' COMMENT '状态',
  `expire` int(32) DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `password_time` varchar(128) DEFAULT NULL COMMENT '发送密码过期时间',
  `enable` tinyint(1) DEFAULT '0' COMMENT '邮箱 激活状态1 未激活0',
  `password_token` varchar(128) DEFAULT NULL COMMENT '发送密码token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resty_user
-- ----------------------------
INSERT INTO `resty_user` VALUES ('86', 'admin', '0192023a7bbd73250516f069df18b500', '1470401515', '115.205.238.94', '1', '0', '7584177@qq.com', '1499489581', null, null);
INSERT INTO `resty_user` VALUES ('146', 'Tinywan', 'e10adc3949ba59abbe56e057f20f883e', '1473319334', '127.0.0.1', '1', '10', '764209193@qq.com', null, null, null);
INSERT INTO `resty_user` VALUES ('173', 'Golang', '4297f44b13955235245b2497399d7a93', null, '127.0.0.1', '0', '0', '1722318623@qq.com', '1499520341', '1', '235a7240ef79cae3bec739b0aa1a52ab');
INSERT INTO `resty_user` VALUES ('176', '圣卢西亚-路楼', 'e10adc3949ba59abbe56e057f20f883e', null, '127.0.0.1', '0', '0', '756684177@qq.com', '1499682622', '1', 'e29c955268d9f5c85e5c4d5d2941f945');
INSERT INTO `resty_user` VALUES ('177', '瓜德罗普岛-梁志诚', 'e10adc3949ba59abbe56e057f20f883e', null, '127.0.0.1', '0', '0', '2680737855@qq.com', null, '1', '9de6f78e0c0fcf3416ac9efbd75267a5');

-- ----------------------------
-- Table structure for tour_monolog
-- ----------------------------
DROP TABLE IF EXISTS `tour_monolog`;
CREATE TABLE `tour_monolog` (
  `channel` varchar(255) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `message` longtext,
  `time` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tour_monolog
-- ----------------------------
INSERT INTO `tour_monolog` VALUES ('PDO_Record', '600', '[2017-01-06 10:45:36] PDO_Record.EMERGENCY: 1111111111132144444444444444441 [] []\n', '1483699536');
INSERT INTO `tour_monolog` VALUES ('PDO_Record', '600', '[2017-01-06 10:46:32] PDO_Record.EMERGENCY: 1111111111132144444444444444441 [] []\n', '1483699592');
INSERT INTO `tour_monolog` VALUES ('PDO_Record', '600', '[2017-01-06 10:47:52] PDO_Record.EMERGENCY: 1111111111132144444444444444441 [] []\n', '1483699672');
INSERT INTO `tour_monolog` VALUES ('PDO_Record', '100', '[2017-01-06 10:47:52] PDO_Record.DEBUG: debug123 [] []\n', '1483699672');

-- ----------------------------
-- Table structure for tour_product
-- ----------------------------
DROP TABLE IF EXISTS `tour_product`;
CREATE TABLE `tour_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pName` varchar(255) NOT NULL,
  `pSn` varchar(50) NOT NULL,
  `pNum` int(10) unsigned NOT NULL DEFAULT '1',
  `mPrice` decimal(10,2) NOT NULL,
  `iPrice` decimal(10,2) NOT NULL,
  `pDesc` text NOT NULL,
  `pubTime` datetime NOT NULL,
  `isShow` tinyint(1) NOT NULL DEFAULT '1',
  `isHot` tinyint(1) NOT NULL DEFAULT '0',
  `cId` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pName` (`pName`) USING BTREE,
  UNIQUE KEY `pName_2` (`pName`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tour_product
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
