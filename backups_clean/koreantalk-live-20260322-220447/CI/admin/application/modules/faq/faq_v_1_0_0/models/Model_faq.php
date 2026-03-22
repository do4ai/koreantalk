<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	-
| Create-Date : 2022-09-15
| Memo : FAQ 관리
|------------------------------------------------------------------------
*/

Class Model_faq extends MY_Model{

	// FAQ 리스트
	public function faq_list_get($data){
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$title = $data['title'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$site_code = $data['site_code'];

		$sql = "SELECT
							a.faq_idx,
							a.title,
							a.display_yn,           
              a.faq_category_idx,
							a.ins_date,						
							b.site_name
						FROM
							tbl_faq a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'							
						WHERE
							a.del_yn = 'N'
						";

		if($site_code != ""){
			$sql .= " AND a.site_code LIKE '%$site_code%' ";
		}
		if($title != ""){
			$sql .= " AND a.title LIKE '%$title%' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$e_date' ";
		}

		$sql .=" ORDER BY a.ins_date DESC LIMIT ?, ? ";

		return $this->query_result($sql,
															array(
															$page_no,
															$page_size
															),$data
															);
	}

	// FAQ 리스트 총 카운트
	public function faq_list_count($data){

		$title = $data['title'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];		 
    $site_code = $data['site_code'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_faq a
               join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'		
						WHERE
							a.del_yn = 'N'
						";

		if($site_code != ""){
			$sql .= " AND a.site_code LIKE '%$site_code%' ";
		}
		if($title != ""){
			$sql .= " AND a.title LIKE '%$title%' ";
		}  
		if($s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$e_date' ";
		}
		return $this->query_cnt($sql,array(),$data);
	}

	// faq 상세
	public function faq_detail($data){

		$faq_idx=$data['faq_idx'];

		$sql = "SELECT
							a.faq_idx,
							a.title,
							a.faq_category_idx,
							a.contents,     
							a.ins_date,
							a.upd_date,
              a.display_yn,
              a.site_code,
              b.site_name,
							a.del_yn
						FROM
							tbl_faq a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.faq_idx = ?
							AND a.del_yn = 'N'
						";

			return $this->query_row($sql,array(
													    $faq_idx
												    	),$data
													  );
	}

	// faq 수정
	public function faq_mod_up($data){

		$faq_idx = $data['faq_idx'];
		$title = $data['title'];
		$site_code = $data['site_code'];
		$contents = $data['contents'];  
    $display_yn = $data['display_yn'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_faq
						SET
							title = ?,							
							contents = ?,
              site_code = ?,
              display_yn = ?,
							upd_date = NOW()
						WHERE
							faq_idx = ?
						";

		$this->query($sql,array(
							   $title,								 
							   $contents,
                 $site_code,
                 $display_yn,
							   $faq_idx
							   ),$data
							 );

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}

	}

	// faq 삭제
	public function faq_del($data){

		$faq_idx = $data['faq_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_faq
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							faq_idx IN ($faq_idx)
						";

		$this->query($sql,array(),$data);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}

	}

	// faq 등록
	public function faq_reg_in($data){

		$title = $data['title'];
		$contents = $data['contents'];
    $site_code = $data['site_code'];
		$display_yn = $data['display_yn'];
    

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_faq
						(
							title,							
							contents,
              site_code,
              display_yn,
							del_yn,
							ins_date,
							upd_date
						)VALUES(
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
								),$data
								);

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
