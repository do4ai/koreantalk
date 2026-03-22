<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 공지사항
|------------------------------------------------------------------------
*/

class Model_lecture extends MY_Model{
  // 리스트

	// 주문 리스트 가져오기
	public function lecture_list($data) {
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];
	
		$sql = "SELECT
              a.lecture_movie_idx,
              a.ins_date,
              b.lecture_idx,
              b.lecture_category_idx,
              c.lecture_name,
              d.category_name,
              b.movie_name             
            FROM tbl_member_watched_movie AS a
              JOIN tbl_lecture_movie AS b ON b.lecture_movie_idx=a.lecture_movie_idx AND b.del_yn='N'
              JOIN tbl_lecture AS c ON c.lecture_idx=b.lecture_idx AND c.del_yn='N'
              JOIN tbl_lecture_category AS d ON d.lecture_category_idx=b.lecture_category_idx AND d.del_yn='N'
            WHERE a.del_yn='N'
            and a.member_idx='$this->member_idx'
	
		";

		$sql .= " ORDER BY a.upd_date ASC LIMIT ?, ? ";

		return $this->query_result($sql,
															 array(
															 $page_no,
															 $page_size
															 ),
															 $data);
	}

	
	// 주문 리스트 총 카운트
	public function lecture_list_count($data) {

		$sql = "SELECT
							COUNT(1) AS cnt
            FROM tbl_member_watched_movie AS a
              JOIN tbl_lecture_movie AS b ON b.lecture_movie_idx=a.lecture_movie_idx AND b.del_yn='N'
              JOIN tbl_lecture AS c ON c.lecture_idx=b.lecture_idx AND c.del_yn='N'
              JOIN tbl_lecture_category AS d ON d.lecture_category_idx=b.lecture_category_idx AND d.del_yn='N'
            WHERE a.del_yn='N'
            and a.member_idx='$this->member_idx'
		";

		return $this->query_cnt($sql,
														array(
														),
														$data);
	}



} // 클래스의 끝
?>
