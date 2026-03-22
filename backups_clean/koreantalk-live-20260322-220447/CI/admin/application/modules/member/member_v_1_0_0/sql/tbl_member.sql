/*
SQLyog Community v12.4.1 (64 bit)
MySQL - 5.7.17-log : Database - meetgo_DB
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`meetgo_DB` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `meetgo_DB`;

/*Table structure for table `tbl_member` */

DROP TABLE IF EXISTS `tbl_member`;

CREATE TABLE `tbl_member` (
  `member_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '회원테이블 키',
  `member_no` varchar(45) DEFAULT NULL COMMENT '회원번호[멤버쉽카드번호]',
  `member_id` varchar(200) DEFAULT NULL COMMENT '회원 아이디(이메일형태)',
  `member_view_id` varchar(200) DEFAULT NULL COMMENT '회원 아이디(이메일형태): SNS가입시 필요한 view용 아이디',
  `member_pw` varchar(200) DEFAULT NULL COMMENT '회원 패스워드\n',
  `member_name` varchar(200) DEFAULT NULL COMMENT '회원명\n',
  `member_nickname` varchar(40) DEFAULT NULL COMMENT '회원 닉네임',
  `member_phone` varchar(200) DEFAULT NULL COMMENT '회원 휴대폰 번호\n',
  `member_birth` varchar(200) DEFAULT NULL COMMENT '회원생년월일\n',
  `lunar_solar_birth` char(1) DEFAULT '0' COMMENT '(0): 양력, (1): 음력',
  `member_addr` varchar(300) DEFAULT NULL COMMENT '회원주소',
  `member_addr_detail` varchar(300) DEFAULT NULL COMMENT '회원주소 상세정보',
  `member_addr_postcode` varchar(45) DEFAULT NULL COMMENT '회원주소 우편번호',
  `member_join_type` char(1) DEFAULT NULL COMMENT '회원가입타입\nC:일반, K: 카카오톡, F:페이스북',
  `member_gender` char(1) DEFAULT NULL COMMENT '성별\n0:남성, 1:여성, 2:무관',
  `membership_grade` varchar(45) DEFAULT '0' COMMENT '멤버쉽 등급\n0: white, 1: red, 2: black, 3: gold',
  `member_favorite_performance` varchar(10) DEFAULT NULL COMMENT '회원이 선호하는 공연\n0 ~ Number (tbl_favorite_performace 참조)',
  `member_point` int(11) DEFAULT '1000' COMMENT '멤버포인트점수\n\n',
  `member_stamp` int(11) DEFAULT '0' COMMENT '멤버 스탬프 개수\n',
  `member_leave_type` char(1) DEFAULT NULL COMMENT '회원탈퇴 사유',
  `member_leave_reason` varchar(500) DEFAULT NULL COMMENT '회원탈퇴 사유 (텍스트형식)',
  `event_alarm_yn` char(1) DEFAULT 'Y' COMMENT '이벤트 푸쉬알람 (N): 수신거부, (Y): 수신',
  `notice_alarm_yn` char(1) DEFAULT 'Y' COMMENT '공지사항 푸쉬알람 (N): 수신거부, (Y): 수신',
  `all_alarm_yn` char(1) DEFAULT 'Y' COMMENT '모든 푸쉬알람(N):수신거부 (Y):수신',
  `email_alarm_yn` char(1) DEFAULT 'Y' COMMENT '이메일알람 (N): 수신거부, (Y): 수신',
  `change_pw_key` varchar(100) DEFAULT NULL COMMENT '비밀번호 변경키',
  `member_additional_info` char(1) DEFAULT 'N' COMMENT 'N: 미입력, Y:입력 // SNS로그인 후 추가 정보 입력을 했는지 확인하는 구분자',
  `del_yn` char(1) DEFAULT 'N' COMMENT '삭제유무\n(N):정상, (Y)삭제\n',
  `gcm_key` varchar(200) DEFAULT NULL COMMENT 'GCM 키',
  `device_os` varchar(1) DEFAULT NULL COMMENT '모바일 OS: (I) IOS, (A) 안드로이드',
  `ins_date` datetime DEFAULT NULL COMMENT '등록일',
  `upd_date` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`member_idx`),
  KEY `member_idx_UNIQUE` (`member_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='회원멤버테이블';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
