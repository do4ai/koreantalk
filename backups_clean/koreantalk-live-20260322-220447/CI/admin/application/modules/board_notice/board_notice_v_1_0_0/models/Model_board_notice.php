<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2025-04-16
| Memo : 커뮤니티 공지사항 관리
|------------------------------------------------------------------------
*/

Class Model_board_notice extends MY_Model{

	// 커뮤니티 공지사항 리스트
	public function board_notice_list($data){
  
		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$title 		 = $data['title'];
		$s_date 	 = $data['s_date'];
		$e_date 	 = $data['e_date'];
		$site_code 	 = $data['site_code'];
		$category 	 = $data['category'];

		$sql = "SELECT
							board_idx,
							title,
							a.display_yn,				
							a.site_code,
							b.site_name,
							a.category,
							a.board_notice_order_no,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') AS  ins_date
						FROM
							tbl_board AS a
              JOIN tbl_site AS b ON b.site_code=a.site_code AND b.del_yn='N'
						WHERE
							a.del_yn = 'N'
							AND a.notice_yn = 'Y'
							
						";

		if($title != ""){
			$sql .= " AND a.title LIKE '%$title%' ";
		}
		if($site_code != ""){
			$sql .= " AND a.site_code = '$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}
		if($category != ""){
			$sql .= " AND a.category = '$category' ";
		}

		$sql .=" ORDER BY board_notice_order_no ASC LIMIT ?, ? ";

		return $this->query_result($sql,
                              array(
                              $page_no,
                              $page_size
                              ),
                              $data);
	}

	// 커뮤니티 공지사항 리스트 총 카운트
	public function board_notice_list_count($data){

		$title 		 = $data['title'];
		$s_date 	 = $data['s_date'];
		$e_date 	 = $data['e_date'];
		$site_code 	 = $data['site_code'];
		$category 	 = $data['category'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_board AS a
              JOIN tbl_site AS b ON b.site_code=a.site_code AND b.del_yn='N'
						WHERE
							a.del_yn = 'N'
							AND a.notice_yn = 'Y'
						";

		if($title != ""){
			$sql .= " AND a.title LIKE '%$title%' ";
		}
		if($site_code != ""){
			$sql .= " AND a.site_code = '$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}
		if($category != ""){
			$sql .= " AND a.category = '$category' ";
		}

		return $this->query_cnt($sql,
                            array(
                            )
                            );
	}

	// 커뮤니티 공지사항 등록
	public function board_notice_reg_in($data){

		$title = $data['title'];
		$contents = $data['contents'];
		$site_code = $data['site_code'];
		$category = $data['category'];
		$board_notice_order_no = $data['board_notice_order_no'];
    $display_yn = $data['display_yn'];
    $board_img = $data['board_img'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_board
						(
							title,
							contents,
							site_code,
							category,
							board_notice_order_no,
              display_yn,
              board_img,
							del_yn,
							notice_yn,
							ins_date,
							upd_date
						) VALUES (
							?, 
							?, 
							?,
							?,
							?,
							?, 
              ?,
							'N',
							'Y',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
                array(
                $title,
                $contents,
                $site_code,
                $category,
                $board_notice_order_no,
                $display_yn,
                $board_img,
                ),
                $data);


		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return "1";
		}
	}

	// 커뮤니티 공지사항 상세
	public function board_notice_detail($data){

		$board_idx = $data['board_idx'];

		$sql = "SELECT
	          	board_idx,
							title,
							contents,
							board_img,
							a.site_code,
							a.category,
							a.board_notice_order_no,
              b.site_name,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') AS ins_date,
							DATE_FORMAT(a.upd_date,'%Y-%m-%d') AS upd_date,
							display_yn							
	        	FROM
	          	tbl_board as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
	        	WHERE
	           	a.board_idx = ?
							AND a.del_yn = 'N'
					";

   		return $this->query_row($sql,
                              array(
                              $board_idx
                              ),
                              $data);
	}

	// 커뮤니티 공지사항 수정
	public function board_notice_mod_up($data){

		$board_idx = $data['board_idx'];
		$category = $data['category'];
		$title = $data['title'];
		$contents = $data['contents'];
		$site_code = $data['site_code'];
		$board_notice_order_no = $data['board_notice_order_no'];
		$contents_text = $data['contents_text'];
    $display_yn = $data['display_yn'];
    $board_img = $data['board_img'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET
							category = ?,
							title = ?,
							contents = ?,
							site_code=?,
							board_notice_order_no=?,
							contents_text=?,
              display_yn=?,
							board_img = ?,
							upd_date = NOW()
						WHERE
							board_idx = ?
						";

		$this->query($sql,
                array(
                $category,
                $title,
                $contents,
                $site_code,
                $board_notice_order_no,
                $contents_text,
                $display_yn,
                $board_img,
                $board_idx
                ),
                $data);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
	}


	// 커뮤니티 공지사항 삭제
	public function board_notice_del($data){

		$board_idx = $data['board_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							board_idx IN ($board_idx)
						";

		$this->query($sql,
                array(
                ),
                $data);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		  return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
	}

}	//클래스의 끝
?>
