<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-06-26
| Memo : 관리
|------------------------------------------------------------------------
*/

Class Model_site extends MY_Model{

	// 리스트
	public function site_list($data){
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$site_name = $data['site_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];

		$sql = "SELECT
							site_idx,
							site_code,
							site_name,					
							del_yn,
							DATE_FORMAT(ins_date,'%Y-%m-%d') as  ins_date,
							DATE_FORMAT(upd_date,'%Y-%m-%d') as  upd_date
						FROM
							tbl_site
						WHERE
							del_yn = 'N'
						";

		if($site_name != ""){
			$sql .= " AND site_name LIKE '%$site_name%' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		$sql .=" ORDER BY ins_date DESC LIMIT ?, ? ";

		return $this->query_result($sql,
                              array(
                              $page_no,
                              $page_size
                              ),
                              $data);
	}

	// 리스트 총 카운트
	public function site_list_count($data){

		$site_name = $data['site_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_site
						WHERE
							del_yn = 'N'
						";

		if($site_name != ""){
			$sql .= " AND site_name LIKE '%$site_name%' ";
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

	// 등록
	public function site_reg_in($data){

		$site_code = $data['site_code'];
		$site_name = $data['site_name'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_site
						(
							site_code,
							site_name,					
							del_yn,
							ins_date,
							upd_date
						) VALUES (
							?, 
							?,						
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
                array(
                $site_code,
                $site_name,              
                ),
                $data);

		$site_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return $site_idx;
		}
	}

	// 상세
	public function site_detail($data){

		$site_idx = $data['site_idx'];

		$sql = "SELECT
	          	site_idx,							
							site_code,
							site_name,
							DATE_FORMAT(ins_date,'%Y-%m-%d') AS ins_date,
							DATE_FORMAT(upd_date,'%Y-%m-%d') AS upd_date,
							menu,
							lang_contents,
							del_yn
	        	FROM
	          	tbl_site
	        	WHERE
	           	site_idx = ?
							AND del_yn = 'N'
					";

   	return $this->query_row($sql,
                            array(
                            $site_idx
                            ),
                            $data);
	}

	// 수정
	public function site_mod_up($data){

		$site_code = $data['site_code'];
		$site_name = $data['site_name'];
		$menu = $data['menu'];
		$lang_contents = $data['lang_contents'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_site
						SET
							site_code = ?,
							site_name = ?,
							menu = ?,
							lang_contents = ?,
							upd_date = NOW()
						WHERE
							site_idx = ?
						";

		$this->query($sql,
                array(
                $site_code,
                $site_name,
                $json_encode($menu),
                $json_encode($lang_contents),
                $site_idx
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

	// 삭제
	public function site_del($data){

		$site_idx = $data['site_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_site
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							site_idx IN ($site_idx)
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
