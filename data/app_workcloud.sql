-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2017 年 06 月 01 日 17:05
-- 服务器版本: 5.6.23
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_workcloud`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_admin_user`
--

CREATE TABLE IF NOT EXISTS `think_admin_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '管理员用户名',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1 启用 0 禁用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(20) DEFAULT NULL COMMENT '最后登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_admin_user`
--

INSERT INTO `think_admin_user` (`id`, `username`, `password`, `status`, `create_time`, `last_login_time`, `last_login_ip`) VALUES
(1, 'admin', 'edf928bd7ceb77a5f3dc5a4c3651a1cb', 1, '2016-10-18 15:28:37', '2017-06-01 00:20:36', '14.204.125.239'),
(2, 'test', '7b79e788e94c1c41b6d4e1b1280c4bdb', 1, '2017-03-30 14:44:52', '2017-03-30 14:45:01', '0.0.0.0');

-- --------------------------------------------------------

--
-- 表的结构 `think_article`
--

CREATE TABLE IF NOT EXISTS `think_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `cid` smallint(5) unsigned NOT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `introduction` varchar(255) DEFAULT '' COMMENT '简介',
  `content` longtext COMMENT '内容',
  `author` varchar(20) DEFAULT '' COMMENT '作者',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0 待审核  1 审核',
  `reading` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `photo` text COMMENT '图集',
  `is_top` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶  0 不置顶  1 置顶',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `publish_time` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `think_article`
--

INSERT INTO `think_article` (`id`, `cid`, `title`, `introduction`, `content`, `author`, `status`, `reading`, `thumb`, `photo`, `is_top`, `sort`, `create_time`, `publish_time`) VALUES
(8, 5, '测试', '测试', '<p>范德萨咖啡店撒</p>', 'admin', 1, 0, '/public/uploads/20170521/254a4d7c5cbba24634cfc26faef02d70.jpg', NULL, 0, 0, '2017-05-17 18:00:05', '2017-05-17 17:58:37'),
(9, 6, '行业趋势测试', '行业趋势测试行业趋势测试行业趋势测试行业趋势测试', '<p>行业趋势测试行业趋势测试行业趋势测试行业趋势测试行业趋势测试行业趋势测试</p>', 'admin', 1, 0, '/public/uploads/20170521/d55c7a2272b052a98ad1f05ecc8afbe6.jpg', NULL, 0, 0, '2017-05-17 18:00:49', '2017-05-17 18:00:37'),
(10, 7, '电力工程项目管理系统', '', '', 'admin', 1, 0, '/public/uploads/20170521/bd6790fd67b5e061192a1cab67ba5431.png', NULL, 0, 0, '2017-05-18 15:42:05', '2017-05-18 15:41:43'),
(11, 7, '银行集中放款系统', '', '', 'admin', 1, 0, '/public/uploads/20170521/9e3b983a6215dcfbec3c84a361f0b7c9.png', NULL, 0, 0, '2017-05-18 15:42:37', '2017-05-18 15:42:15'),
(12, 7, '银行客户关系管理系统', '', '', 'admin', 1, 0, '/public/uploads/20170521/e0857b701bb4a3e022c971079754d035.png', NULL, 0, 0, '2017-05-18 15:42:55', '2017-05-18 15:42:41'),
(13, 7, '创先工作管理平台', '', '', 'admin', 1, 0, '/public/uploads/20170521/01e7e6c2ccdf7c05dad3a16f52eca18b.png', NULL, 0, 0, '2017-05-18 15:43:16', '2017-05-18 15:42:58'),
(14, 7, '企业在线培训、考试系统', '', '', 'admin', 1, 0, '/public/uploads/20170521/ecb51b8a2e9b9dd5568696fa1c199647.png', NULL, 0, 0, '2017-05-18 15:43:35', '2017-05-18 15:43:20'),
(15, 7, '小型企业生产管理系统', '', '', 'admin', 1, 0, '/public/uploads/20170521/08b3763cd24c17acedf6b28d1f265f00.png', NULL, 0, 0, '2017-05-18 15:43:57', '2017-05-18 15:43:38'),
(16, 8, '乐乎旅行网', '', '', 'admin', 1, 0, '/public/uploads/20170521/a60fcb31ef2eac681fc403776416fa97.png', NULL, 0, 0, '2017-05-18 15:57:00', '2017-05-18 15:56:40'),
(17, 8, '自来水微信公众服务平台', '', '', 'admin', 1, 0, '/public/uploads/20170521/f509bb00fa9bf0c526ac512dd7a2dfe2.png', NULL, 0, 0, '2017-05-18 15:57:23', '2017-05-18 15:57:04'),
(18, 8, '方寸销帮', '', '', 'admin', 1, 0, '/public/uploads/20170521/0d48580aa0d867af1d3405232dc7f86e.png', NULL, 0, 0, '2017-05-18 15:57:43', '2017-05-18 15:57:27'),
(19, 8, '农村科技服务平台', '', '', 'admin', 1, 0, '/public/uploads/20170521/02a9b58a1f53466e2e84f162cc5df8bb.png', NULL, 0, 0, '2017-05-18 15:58:03', '2017-05-18 15:57:47'),
(20, 8, '微信招聘平台', '', '', 'admin', 1, 0, '/public/uploads/20170521/f474b13fc6a21d5f12ddcb1f29847bd9.png', NULL, 0, 0, '2017-05-18 15:58:21', '2017-05-18 15:58:06'),
(21, 9, '输电线路在线监测方案', '', '', 'admin', 1, 0, '/public/uploads/20170519/6e67865a7048bb94bb656b18a0d5735b.png', NULL, 0, 0, '2017-05-18 16:17:14', '2017-05-18 16:15:23'),
(22, 9, '配网故障在线监测平台', '', '', 'admin', 1, 0, '/public/uploads/20170519/bbc4107722cb78868b2297576cac56e5.png', NULL, 0, 0, '2017-05-18 16:17:32', '2017-05-18 16:17:18'),
(23, 9, '配网三相平衡解决方案', '', '', 'admin', 1, 0, '/public/uploads/20170519/f383bfe672548bc128897bf66eecf1e4.png', NULL, 0, 0, '2017-05-18 16:17:50', '2017-05-18 16:17:36'),
(24, 9, '分布式馈线自动化解决方案', '', '', 'admin', 1, 0, '/public/uploads/20170519/4c198ef847143288ecd6609a67def42c.png', NULL, 0, 0, '2017-05-18 16:18:08', '2017-05-18 16:17:53'),
(25, 9, '营业厅视频监控解决方案', '', '', 'admin', 1, 0, '/public/uploads/20170519/88451adca1b93b275818edab9ed0d538.png', NULL, 0, 0, '2017-05-18 16:18:28', '2017-05-18 16:18:11'),
(26, 10, '瑞攀官网后台操作说明', '', '<p><strong>解决方案：</strong></p><p><strong>1.必须上传缩略图（尺寸488*323px）；</strong></p><p><strong>最新新闻和行业趋势：</strong></p><p><strong>1.必须上传缩略图（尺寸488*323px）；</strong></p><p><strong>2.必须填简介。</strong></p>', 'admin', 1, 0, '', NULL, 0, 0, '2017-05-19 11:12:47', '2017-05-19 11:11:15'),
(27, 5, '最新新闻测试', '—记瑞攀第一届亲子活动 盼望着，盼望着，5月23日来了，骚动的心沸腾了。为促进员工家庭亲子关系、体现公司大家庭', '<p style="margin-top: 0px; margin-bottom: 14px; padding: 0px; -webkit-tap-highlight-color: transparent; color: rgb(34, 34, 34); font-family: ">生成的<span class="e-search-highlight" style="margin: 0px; padding: 0px; -webkit-tap-highlight-color: transparent; outline: 0px; background-color: rgb(254, 231, 47) !important;">URL</span>地址为：</p><pre style="margin-top: 0px; margin-bottom: 14px; padding: 16px; -webkit-tap-highlight-color: transparent; overflow: auto; font-size: 13.6px; line-height: 1.45; background-color: rgb(247, 247, 247); border: 1px solid silver; border-radius: 3px; font-family: Consolas, ">http://blog.thinkphp.cn/read/id/5.html#anchor</pre><h2 style="margin: 0px 0px 14px; padding: 0px 0px 0.3em; -webkit-tap-highlight-color: transparent; font-size: 1.75em; font-weight: 200; line-height: 1.225; border-bottom: 1px solid rgb(238, 238, 238); color: rgb(34, 34, 34); font-family: ">隐藏或者加上入口文件<img src="/public/uploads/image/20170525/1495694844454587.jpg" title="1495694844454587.jpg" alt="38b26477e608c0193f848d69544d4da3ca8c34e12e4ff-LKLfNb_fw658.jpg"/></h2><p style="margin-top: 0px; margin-bottom: 14px; padding: 0px; -webkit-tap-highlight-color: transparent; color: rgb(34, 34, 34); font-family: ">有时候我们生成的<span class="e-search-highlight" style="margin: 0px; padding: 0px; -webkit-tap-highlight-color: transparent; outline: 0px; background-color: rgb(254, 231, 47) !important;">URL</span>地址可能需要加上<code style="font-family: Consolas, ">index.php</code>或者去掉<code style="font-family: Consolas, ">index.php</code>，大多数时候系统会自动判断，如果发现自动生成的地址有问题，可以直接在调用<code style="font-family: Consolas, ">build</code>方法之前调用<code style="font-family: Consolas, ">root</code>方法，例如加上<code style="font-family: Consolas, ">index.php</code>：</p><pre style="margin-top: 0px; margin-bottom: 14px; padding: 16px; -webkit-tap-highlight-color: transparent; overflow: auto; font-size: 13.6px; line-height: 1.45; background-color: rgb(247, 247, 247); border: 1px solid silver; border-radius: 3px; font-family: Consolas, ">Url::root(&#39;/index.php&#39;);Url::build(&#39;index/blog/read&#39;,&#39;id=5&#39;);</pre><p style="margin-top: 0px; margin-bottom: 14px; padding: 0px; -webkit-tap-highlight-color: transparent; color: rgb(34, 34, 34); font-family: ">或者隐藏<code style="font-family: Consolas, ">index.php</code>：</p><pre style="margin-top: 0px; margin-bottom: 14px; padding: 16px; -webkit-tap-highlight-color: transparent; overflow: auto; font-size: 13.6px; line-height: 1.45; background-color: rgb(247, 247, 247); border: 1px solid silver; border-radius: 3px; font-family: Consolas, ">Url::root(&#39;/&#39;);</pre><p><br/></p>', 'admin', 1, 0, '/public/uploads/20170521/6c54f26a5dcc52680e9a56ec04ccd585.jpg', NULL, 0, 0, '2017-05-19 15:49:05', '2017-05-19 15:48:59');

-- --------------------------------------------------------

--
-- 表的结构 `think_auth_group`
--

CREATE TABLE IF NOT EXISTS `think_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) DEFAULT NULL COMMENT '权限规则ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限组表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_auth_group`
--

INSERT INTO `think_auth_group` (`id`, `title`, `status`, `rules`) VALUES
(1, '超级管理组1', 1, '1,2,3,73,74,5,6,7,8,9,10,11,12,39,40,41,42,43,14,13,20,21,22,23,24,15,25,26,27,28,29,30,16,17,44,45,46,47,48,18,49,50,51,52,53,19,31,32,33,34,35,36,37,54,55,58,59,60,61,62,56,63,64,65,66,67,57,68,69,70,71,72'),
(2, '11', 1, '14,12,39,40,41,42,43,13,20,21,22,23,24,15,25,26,27,28,29,30,55,58,59,60,61,62,56,63,64,65,66,67,57,68,69,70,71,72');

-- --------------------------------------------------------

--
-- 表的结构 `think_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `think_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限组规则表';

--
-- 转存表中的数据 `think_auth_group_access`
--

INSERT INTO `think_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_auth_rule`
--

CREATE TABLE IF NOT EXISTS `think_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(20) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `pid` smallint(5) unsigned NOT NULL COMMENT '父级ID',
  `icon` varchar(50) DEFAULT '' COMMENT '图标',
  `sort` tinyint(4) unsigned NOT NULL COMMENT '排序',
  `condition` char(100) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='规则表' AUTO_INCREMENT=91 ;

--
-- 转存表中的数据 `think_auth_rule`
--

INSERT INTO `think_auth_rule` (`id`, `name`, `title`, `type`, `status`, `pid`, `icon`, `sort`, `condition`) VALUES
(1, 'admin/System/default', '系统配置', 1, 1, 0, '', 1, ''),
(2, 'admin/System/siteConfig', '站点配置', 1, 1, 1, '', 0, ''),
(3, 'admin/System/updateSiteConfig', '更新配置', 1, 0, 1, '', 0, ''),
(6, 'admin/Menu/index', '后台菜单', 1, 1, 1, '', 0, ''),
(7, 'admin/Menu/add', '添加菜单', 1, 0, 6, '', 0, ''),
(8, 'admin/Menu/save', '保存菜单', 1, 0, 6, '', 0, ''),
(9, 'admin/Menu/edit', '编辑菜单', 1, 0, 6, '', 0, ''),
(10, 'admin/Menu/update', '更新菜单', 1, 0, 6, '', 0, ''),
(11, 'admin/Menu/delete', '删除菜单', 1, 0, 6, '', 0, ''),
(12, 'admin/Nav/index', '前台菜单', 1, 1, 14, '', 0, ''),
(13, 'admin/Category/index', '栏目管理', 1, 1, 14, '', 0, ''),
(14, 'admin/Content/default', '内容管理', 1, 1, 0, '', 5, ''),
(15, 'admin/Article/index', '文章管理', 1, 1, 14, '', 0, ''),
(16, 'admin/User/default', '用户管理', 1, 1, 0, '', 2, ''),
(17, 'admin/User/index', '普通用户', 1, 1, 16, '', 0, ''),
(18, 'admin/AdminUser/index', '管理员', 1, 1, 16, '', 0, ''),
(19, 'admin/AuthGroup/index', '权限组', 1, 1, 16, '', 0, ''),
(20, 'admin/Category/add', '添加栏目', 1, 0, 13, '', 0, ''),
(21, 'admin/Category/save', '保存栏目', 1, 0, 13, '', 0, ''),
(22, 'admin/Category/edit', '编辑栏目', 1, 0, 13, '', 0, ''),
(23, 'admin/Category/update', '更新栏目', 1, 0, 13, '', 0, ''),
(24, 'admin/Category/delete', '删除栏目', 1, 0, 13, '', 0, ''),
(25, 'admin/Article/add', '添加文章', 1, 0, 15, '', 0, ''),
(26, 'admin/Article/save', '保存文章', 1, 0, 15, '', 0, ''),
(27, 'admin/Article/edit', '编辑文章', 1, 0, 15, '', 0, ''),
(28, 'admin/Article/update', '更新文章', 1, 0, 15, '', 0, ''),
(29, 'admin/Article/delete', '删除文章', 1, 0, 15, '', 0, ''),
(30, 'admin/Article/toggle', '文章审核', 1, 0, 15, '', 0, ''),
(31, 'admin/AuthGroup/add', '添加权限组', 1, 0, 19, '', 0, ''),
(32, 'admin/AuthGroup/save', '保存权限组', 1, 0, 19, '', 0, ''),
(33, 'admin/AuthGroup/edit', '编辑权限组', 1, 0, 19, '', 0, ''),
(34, 'admin/AuthGroup/update', '更新权限组', 1, 0, 19, '', 0, ''),
(35, 'admin/AuthGroup/delete', '删除权限组', 1, 0, 19, '', 0, ''),
(36, 'admin/AuthGroup/auth', '授权', 1, 0, 19, '', 0, ''),
(37, 'admin/AuthGroup/updateAuthGroupRule', '更新权限组规则', 1, 0, 19, '', 0, ''),
(39, 'admin/Nav/add', '添加菜单', 1, 0, 12, '', 0, ''),
(40, 'admin/Nav/save', '保存菜单', 1, 0, 12, '', 0, ''),
(41, 'admin/Nav/edit', '编辑菜单', 1, 0, 12, '', 0, ''),
(42, 'admin/Nav/update', '更新菜单', 1, 0, 12, '', 0, ''),
(43, 'admin/Nav/delete', '删除菜单', 1, 0, 12, '', 0, ''),
(44, 'admin/User/add', '添加用户', 1, 0, 17, '', 0, ''),
(45, 'admin/User/save', '保存用户', 1, 0, 17, '', 0, ''),
(46, 'admin/User/edit', '编辑用户', 1, 0, 17, '', 0, ''),
(47, 'admin/User/update', '更新用户', 1, 0, 17, '', 0, ''),
(48, 'admin/User/delete', '删除用户', 1, 0, 17, '', 0, ''),
(49, 'admin/AdminUser/add', '添加管理员', 1, 0, 18, '', 0, ''),
(50, 'admin/AdminUser/save', '保存管理员', 1, 0, 18, '', 0, ''),
(51, 'admin/AdminUser/edit', '编辑管理员', 1, 0, 18, '', 0, ''),
(52, 'admin/AdminUser/update', '更新管理员', 1, 0, 18, '', 0, ''),
(53, 'admin/AdminUser/delete', '删除管理员', 1, 0, 18, '', 0, ''),
(55, 'admin/SlideCategory/index', '轮播分类', 1, 1, 14, '', 0, ''),
(56, 'admin/Slide/index', '轮播图管理', 1, 1, 14, '', 0, ''),
(57, 'admin/Link/index', '友情链接', 1, 1, 14, '', 0, ''),
(58, 'admin/SlideCategory/add', '添加分类', 1, 0, 55, '', 0, ''),
(59, 'admin/SlideCategory/save', '保存分类', 1, 0, 55, '', 0, ''),
(60, 'admin/SlideCategory/edit', '编辑分类', 1, 0, 55, '', 0, ''),
(61, 'admin/SlideCategory/update', '更新分类', 1, 0, 55, '', 0, ''),
(62, 'admin/SlideCategory/delete', '删除分类', 1, 0, 55, '', 0, ''),
(63, 'admin/Slide/add', '添加轮播', 1, 0, 56, '', 0, ''),
(64, 'admin/Slide/save', '保存轮播', 1, 0, 56, '', 0, ''),
(65, 'admin/Slide/edit', '编辑轮播', 1, 0, 56, '', 0, ''),
(66, 'admin/Slide/update', '更新轮播', 1, 0, 56, '', 0, ''),
(67, 'admin/Slide/delete', '删除轮播', 1, 0, 56, '', 0, ''),
(68, 'admin/Link/add', '添加链接', 1, 0, 57, '', 0, ''),
(69, 'admin/Link/save', '保存链接', 1, 0, 57, '', 0, ''),
(70, 'admin/Link/edit', '编辑链接', 1, 0, 57, '', 0, ''),
(71, 'admin/Link/update', '更新链接', 1, 0, 57, '', 0, ''),
(72, 'admin/Link/delete', '删除链接', 1, 0, 57, '', 0, ''),
(73, 'admin/ChangePassword/index', '修改密码', 1, 1, 1, '', 0, ''),
(74, 'admin/ChangePassword/updatePassword', '更新密码', 1, 0, 1, '', 0, ''),
(75, 'admin/Dev/default', '系统开发', 1, 1, 0, '', 0, ''),
(76, 'admin/Map/index', '字典管理', 1, 1, 75, 'icon-resize', 0, ''),
(78, 'admin/Generate/index', '代码生成', 1, 1, 75, '', 0, ''),
(86, 'http://www.kancloud.cn/manual/thinkphp5', 'thinkphp5', 2, 1, 84, '', 0, ''),
(85, 'http://workcloud.sc2yun.com/static/admin/zui/index.html', 'ZUI', 2, 1, 84, '', 0, ''),
(84, 'admin/Dev/index', '开发文档', 1, 1, 0, '', 0, ''),
(82, 'admin/Plugin/index', '插件管理', 1, 1, 75, '', 0, ''),
(89, 'http://www.kancloud.cn/houdunwang/wechat/322346', '微信SDK', 2, 1, 84, '', 0, ''),
(81, 'admin/Index/index', '首页', 1, 1, 0, '', 10, ''),
(90, 'admin/Devlog/index', '开发日志', 1, 1, 84, '', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `think_category`
--

CREATE TABLE IF NOT EXISTS `think_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `alias` varchar(50) DEFAULT '' COMMENT '导航别名',
  `content` longtext COMMENT '分类内容',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `icon` varchar(20) DEFAULT '' COMMENT '分类图标',
  `list_template` varchar(50) DEFAULT '' COMMENT '分类列表模板',
  `detail_template` varchar(50) DEFAULT '' COMMENT '分类详情模板',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分类类型  1  列表  2 单页',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `path` varchar(255) DEFAULT '' COMMENT '路径',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `think_category`
--

INSERT INTO `think_category` (`id`, `name`, `alias`, `content`, `thumb`, `icon`, `list_template`, `detail_template`, `type`, `sort`, `pid`, `path`, `create_time`) VALUES
(5, '最新新闻', '', '', '', '', '', '', 1, 0, 0, '0,', '2017-05-17 17:57:22'),
(6, '行业趋势', '', '', '', '', '', '', 1, 0, 0, '0,', '2017-05-17 17:57:33'),
(7, '管理信息化解决方案', '', '', '', '', '', '', 1, 0, 0, '0,', '2017-05-17 17:57:56'),
(8, '移动应用解决方案', '', '', '', '', '', '', 1, 0, 0, '0,', '2017-05-17 17:58:10'),
(9, '物联网解决方案', '', '', '', '', '', '', 1, 0, 0, '0,', '2017-05-17 17:58:20'),
(10, '操作说明', '', '', '', '', '', '', 1, 0, 0, '0,', '2017-05-19 11:11:07');

-- --------------------------------------------------------

--
-- 表的结构 `think_devlog`
--

CREATE TABLE IF NOT EXISTS `think_devlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '开发日志主键',
  `title` varchar(255) DEFAULT NULL COMMENT '主题',
  `content` varchar(255) DEFAULT NULL COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1-正常 | 0-禁用',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态，1-删除 | 0-正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='开发日志' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_devlog`
--

INSERT INTO `think_devlog` (`id`, `title`, `content`, `status`, `isdelete`, `create_time`, `update_time`) VALUES
(1, '测试一下', '这里是描述', 1, 0, 1495866141, 1495866141);

-- --------------------------------------------------------

--
-- 表的结构 `think_link`
--

CREATE TABLE IF NOT EXISTS `think_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '链接名称',
  `link` varchar(255) DEFAULT '' COMMENT '链接地址',
  `image` varchar(255) DEFAULT '' COMMENT '链接图片',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1 显示  2 隐藏',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_link`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_map`
--

CREATE TABLE IF NOT EXISTS `think_map` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '字典名称',
  `value` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '字典值',
  `group` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '所属组',
  `describe` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='系统字典表' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_map`
--

INSERT INTO `think_map` (`id`, `name`, `value`, `group`, `describe`) VALUES
(1, '链接', 'url', 'admin-nav-type', ''),
(2, '文章类目', 'cate', 'admin-nav-type', ''),
(4, '链接', '2', 'admin-menu-type', ''),
(5, '控制器', '1', 'admin-menu-type', '');

-- --------------------------------------------------------

--
-- 表的结构 `think_module`
--

CREATE TABLE IF NOT EXISTS `think_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  `actions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `think_module`
--

INSERT INTO `think_module` (`id`, `name`, `title`, `actions`) VALUES
(14, 'wechat', '微信管理', '[{"title":"微信托管","name":"wechat","icon":""}]');

-- --------------------------------------------------------

--
-- 表的结构 `think_nav`
--

CREATE TABLE IF NOT EXISTS `think_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL COMMENT '父ID',
  `name` varchar(20) NOT NULL COMMENT '导航名称',
  `type` varchar(20) DEFAULT NULL,
  `alias` varchar(20) DEFAULT '' COMMENT '导航别称',
  `link` varchar(255) DEFAULT '' COMMENT '导航链接',
  `icon` varchar(255) DEFAULT '' COMMENT '导航图标',
  `target` varchar(10) DEFAULT '' COMMENT '打开方式',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态  0 隐藏  1 显示',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='导航表' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_nav`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_slide`
--

CREATE TABLE IF NOT EXISTS `think_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '轮播图名称',
  `description` varchar(255) DEFAULT '' COMMENT '说明',
  `link` varchar(255) DEFAULT '' COMMENT '链接',
  `target` varchar(10) DEFAULT '' COMMENT '打开方式',
  `image` varchar(255) DEFAULT '' COMMENT '轮播图片',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态  1 显示  0  隐藏',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='轮播图表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_slide`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_slide_category`
--

CREATE TABLE IF NOT EXISTS `think_slide_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '轮播图分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='轮播图分类表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_slide_category`
--

INSERT INTO `think_slide_category` (`id`, `name`) VALUES
(1, '首页轮播');

-- --------------------------------------------------------

--
-- 表的结构 `think_system`
--

CREATE TABLE IF NOT EXISTS `think_system` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '配置项名称',
  `value` text NOT NULL COMMENT '配置项值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统配置表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_system`
--

INSERT INTO `think_system` (`id`, `name`, `value`) VALUES
(1, 'site_config', 'a:8:{s:10:"site_title";s:4:"BLOG";s:9:"seo_title";s:5:" BLOG";s:11:"seo_keyword";s:0:"";s:15:"seo_description";s:0:"";s:14:"site_copyright";s:31:"@2017  网站版权所有者：";s:8:"site_icp";s:0:"";s:11:"site_tongji";s:0:"";s:15:"adminsite_title";s:18:"瑞攀开发平台";}');

-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

CREATE TABLE IF NOT EXISTS `think_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机',
  `email` varchar(50) DEFAULT '' COMMENT '邮箱',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态  1 正常  2 禁止',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登陆时间',
  `last_login_ip` varchar(50) DEFAULT '' COMMENT '最后登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_user`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_wechat`
--

CREATE TABLE IF NOT EXISTS `think_wechat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wechat` text NOT NULL,
  `remark` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` int(10) NOT NULL,
  `isdelete` tinyint(1) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='微信信息表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `think_wechat`
--

INSERT INTO `think_wechat` (`id`, `wechat`, `remark`, `status`, `create_time`, `isdelete`, `update_time`) VALUES
(1, 'a:2:{s:5:"appid";s:4:"1212";s:9:"appsecret";s:5:"12121";}', '12', 0, 1496219864, 1, 1496237040),
(2, 'a:2:{s:5:"appid";s:4:"1223";s:9:"appsecret";s:3:"321";}', 'gds', 0, 1496220908, 1, 1496237027),
(3, 'a:3:{s:5:"appid";s:18:"wx8a459ca4b6296cfb";s:9:"appsecret";s:32:"8584f5fe3d5ba088a364b95bb102b50c";s:5:"token";s:4:"d1rj";}', '', 0, 1496240582, 0, 1496283054);
