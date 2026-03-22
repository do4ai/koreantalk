/*
SQLyog Community v12.4.1 (64 bit)
MySQL - 5.7.17-log : Database - krakenbeat_DB
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`krakenbeat_DB` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `krakenbeat_DB`;

/*Table structure for table `tbl_category_management` */

DROP TABLE IF EXISTS `tbl_category_management`;

CREATE TABLE `tbl_category_management` (
  `category_management_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '카테고리 키',
  `category_depth` int(11) DEFAULT NULL COMMENT '카테고리 depth',
  `parent_category_management_idx` int(11) DEFAULT NULL COMMENT '부모 카테고리 키',
  `type` char(1) DEFAULT NULL COMMENT '카테고리 타입 0:장르, 1:분위기, 2:속',
  `category_name` varchar(80) DEFAULT NULL COMMENT '카테고리 명 ',
  `state` char(1) DEFAULT '1' COMMENT '상태 0:비활성화, 1:활성화',
  `order_no` int(5) DEFAULT '0' COMMENT '순서 ',
  `del_yn` char(1) DEFAULT 'N' COMMENT '삭제유무 (N)정상,(Y)삭제',
  `ins_date` datetime DEFAULT NULL COMMENT '등록일',
  `upd_date` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`category_management_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
