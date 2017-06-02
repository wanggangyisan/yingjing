/*
Navicat MySQL Data Transfer

Source Server         : ss
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : artice

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-06-01 14:10:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for file_info
-- ----------------------------
DROP TABLE IF EXISTS `file_info`;
CREATE TABLE `file_info` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `filename` varchar(200) NOT NULL DEFAULT '' COMMENT '文件上传之前的名字',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` mediumtext NOT NULL COMMENT '文件内容',
  `abstract` text NOT NULL,
  `keyword` text NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态0正常 1删除',
  `file_path` varchar(200) NOT NULL,
  `upload_time` int(11) NOT NULL DEFAULT '0' COMMENT '文件上传时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(200) NOT NULL DEFAULT '' COMMENT '用户密码',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
