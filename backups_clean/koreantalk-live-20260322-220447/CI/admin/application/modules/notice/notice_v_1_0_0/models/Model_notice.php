<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2022-09-14
| Memo : 공지사항 관리
|------------------------------------------------------------------------
*/

Class Model_notice extends MY_Model{

	// 공지사항 리스트
	public function notice_list($data){
  
		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$title 		 = $data['title'];
		$s_date 	 = $data['s_date'];
		$e_date 	 = $data['e_date'];
		$site_code 	 = $data['site_code'];

		$sql = "SELECT
							notice_idx,
							title,
							a.display_yn,				
							a.site_code,
							b.site_name,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') as  ins_date
						FROM
							tbl_notice as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn = 'N'
						";

		if($title != ""){
			$sql .= " AND title LIKE '%$title%' ";
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

		$sql .=" ORDER BY notice_idx DESC LIMIT ?, ? ";

		return $this->query_result($sql,
                              array(
                              $page_no,
                              $page_size
                              ),
                              $data);
	}

	// 공지사항 리스트 총 카운트
	public function notice_list_count($data){

		$title 		 = $data['title'];
		$s_date 	 = $data['s_date'];
		$e_date 	 = $data['e_date'];
		$site_code 	 = $data['site_code'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_notice as a
               join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn = 'N'
						";

		if($title != ""){
			$sql .= " AND title LIKE '%$title%' ";
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

		return $this->query_cnt($sql,
                            array(
                            )
                            );
	}

	// 공지사항 등록
	public function notice_reg_in($data){

		$title = $data['title'];
		$contents = $data['contents'];
		$site_code = $data['site_code'];
    $display_yn = $data['display_yn'];
    $img_path = $data['img_path'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_notice
						(
							title,
							contents,
							site_code,
              display_yn,
              img_path,
							del_yn,
							ins_date,
							upd_date
						) VALUES (
							?, 
							?, 
							?,
							?, 
              ?,
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
                array(
                $title,
                $contents,
                $site_code,
                $display_yn,
                $img_path,
                ),
                $data);

		$notice_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return $notice_idx;
		}
	}

	// 공지사항 상세
	public function notice_detail($data){

		$notice_idx = $data['notice_idx'];

		$sql = "SELECT
	          	notice_idx,
							title,
							contents,
							img_path,
							a.site_code,
              b.site_name,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') AS ins_date,
							DATE_FORMAT(a.upd_date,'%Y-%m-%d') AS upd_date,
							display_yn							
	        	FROM
	          	tbl_notice as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
	        	WHERE
	           	notice_idx = ?
							AND a.del_yn = 'N'
					";

   		return $this->query_row($sql,
                              array(
                              $notice_idx
                              ),
                              $data);
	}

	// 공지사항 수정
	public function notice_mod_up($data){

		$notice_idx = $data['notice_idx'];
		$title = $data['title'];
		$contents = $data['contents'];
		$site_code = $data['site_code'];
    $display_yn = $data['display_yn'];
    $img_path = $data['img_path'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_notice
						SET
							title = ?,
							contents = ?,
							img_path = ?,
							site_code=?,
              display_yn=?,
							upd_date = NOW()
						WHERE
							notice_idx = ?
						";

		$this->query($sql,
                array(
                $title,
                $contents,
                $img_path,
                $site_code,
                $display_yn,
                $notice_idx
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

	// 공지사항 상태 변경
	public function notice_state_mod_up($data){

		$notice_idx  = $data['notice_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_notice
						SET
							notice_state = if(display_yn='Y','N','Y'),
							upd_date = NOW()
						WHERE
							notice_idx = ?
						";

		$this->query($sql,
                array(        
                $notice_idx
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

	// 공지사항 삭제
	public function notice_del($data){

		$notice_idx = $data['notice_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_notice
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							notice_idx IN ($notice_idx)
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

	// 공지사항 알림 발송 
	public function notice_push_mod_up($data){

		$notice_idx = $data['notice_idx'];

		$this->db->trans_begin();

		$sql = " 	UPDATE
									tbl_notice
							SET
									push_send_yn = 'Y',
									push_send_date = NOW()
							WHERE
									notice_idx ='$notice_idx'
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
