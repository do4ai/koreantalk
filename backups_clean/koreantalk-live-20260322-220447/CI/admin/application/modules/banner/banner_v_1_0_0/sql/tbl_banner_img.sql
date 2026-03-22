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

/*Table structure for table `tbl_banner_img` */

DROP TABLE IF EXISTS `tbl_banner_img`;

CREATE TABLE `tbl_banner_img` (
  `banner_img_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '배너 이미지 키',
  `img_url` varchar(200) DEFAULT NULL COMMENT '이미지 경로',
  `link_url` varchar(200) DEFAULT NULL COMMENT '링크 url',
  `start_date` datetime DEFAULT NULL COMMENT '시작날짜',
  `end_date` datetime DEFAULT NULL COMMENT '종료날짜',
  `del_yn` varchar(1) DEFAULT NULL COMMENT '삭제유무 (N) 정상 ,(Y)삭제',
  `ins_date` datetime DEFAULT NULL COMMENT '등록일',
  `upd_date` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`banner_img_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='배너 이미지';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
