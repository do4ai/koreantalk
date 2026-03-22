/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 5.7.26-log : Database - gymboxx_DB
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gymboxx_DB` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `gymboxx_DB`;

/*Table structure for table `tbl_start_popup` */

DROP TABLE IF EXISTS `tbl_start_popup`;

CREATE TABLE `tbl_start_popup` (
  `start_popup_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '앱 시작시 팝업 키',
  `corp_idx` int(11) DEFAULT NULL COMMENT '지점 키',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '제목',
  `contents` mediumtext COLLATE utf8mb4_unicode_ci COMMENT '내용',
  `state` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '상태 0:비활성화, 1:활성화',
  `img_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '이미지 경로',
  `link_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '링크',
  `device_os` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '구분(I:ios,A:aos)',
  `start_date` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '시작일',
  `end_date` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '종료일',
  `del_yn` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N' COMMENT '삭제유무 (N) 정상 ,(Y)삭제',
  `ins_date` datetime DEFAULT NULL COMMENT '등록일',
  `upd_date` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`start_popup_idx`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='시작팝업 관리 ';

/*Data for the table `tbl_start_popup` */

insert  into `tbl_start_popup`(`start_popup_idx`,`corp_idx`,`title`,`contents`,`state`,`img_url`,`link_url`,`device_os`,`start_date`,`end_date`,`del_yn`,`ins_date`,`upd_date`) values
(1,1,'테스트2','내용','1','http://admin.gymboxx.rocateerdev.co.kr/media/commonfile/202011/09/81c24f30c773975d7474d6aab8d6889f.jpg',NULL,'A','2020-06-12','2021-06-12','N','2020-06-12 11:10:39','2020-09-28 14:50:12'),
(2,NULL,'암웨이22','2222222222','1','http://admin.nobleclub.kr/media/commonfile/202009/04/0ddde1677ffd721ad8291324ce122e70.png','http://koc.7meerkat.com/event_v_1_0_0','I',NULL,NULL,'Y','2020-08-13 11:58:55','2020-09-10 22:41:55'),
(3,NULL,'ㅇㅇ','ㅁㅇㄹ','1','http://admin.nobleclub.kr/media/commonfile/202009/12/06187c3cb23e9c885b425a3057832023.png','http://nobleclub.kr','I',NULL,NULL,'Y','2020-09-11 14:41:32','2020-11-05 14:43:01'),
(4,NULL,'시작팝업 테스트!!',NULL,'1','http://admin.gymboxx.rocateerdev.co.kr/media/commonfile/202011/09/bc3b336ed513d4071f2ccd0bf195fe87.jpg',NULL,NULL,NULL,NULL,'N','2020-11-05 14:42:38','2020-11-05 14:42:38'),
(5,1,'지점관리자 시작팝업 테스트!!',NULL,'1','http://admin.gymboxx.rocateerdev.co.kr/media/commonfile/202011/09/1cf8b00c5a25c62ca61dd3d3bf60373e.jpg',NULL,NULL,NULL,NULL,'N','2020-11-05 18:46:52','2020-11-05 18:46:52'),
(6,NULL,'쿠키 테스트!!!',NULL,'1','http://admin.gymboxx.rocateerdev.co.kr/media/commonfile/202011/09/dcaec4bba411231d4c78007514cc98db.jpg',NULL,NULL,NULL,NULL,'N','2020-11-09 15:29:52','2020-11-09 15:31:50'),
(7,NULL,'팝업 테스트!!',NULL,'1','http://admin.gymboxx.rocateerdev.co.kr/media/commonfile/202011/09/0fb95dc0012a22b271366a86be4738e5.png',NULL,NULL,NULL,NULL,'N','2020-11-09 15:34:17','2020-12-31 16:29:24');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
