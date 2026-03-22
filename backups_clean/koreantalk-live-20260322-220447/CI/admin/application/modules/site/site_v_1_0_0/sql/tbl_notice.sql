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

/*Table structure for table `tbl_notice` */

DROP TABLE IF EXISTS `tbl_notice`;

CREATE TABLE `tbl_notice` (
  `notice_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '공지 사항 키\n',
  `title` varchar(200) DEFAULT NULL COMMENT '제목\n',
  `contents` text COMMENT '내용\n',
  `img` varchar(200) DEFAULT NULL COMMENT '이미지 경로\n',
  `img_width` int(11) DEFAULT NULL COMMENT '이미지 가로\n',
  `img_height` int(11) DEFAULT NULL COMMENT '이미지 세로\n',
  `del_yn` char(1) DEFAULT 'N' COMMENT '삭제유무 (N)정상,(Y)삭제',
  `ins_date` datetime DEFAULT NULL COMMENT '등록일\n',
  `upd_date` datetime DEFAULT NULL COMMENT '수정일\n',
  `notice_type` varchar(1) DEFAULT NULL COMMENT '공지타입(0공지/1발표)',
  `ref` int(11) DEFAULT '0' COMMENT '조회수',
  `real_del_yn` char(1) DEFAULT 'N' COMMENT 'N보임/Y안보임',
  PRIMARY KEY (`notice_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='공지 사항\n';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
