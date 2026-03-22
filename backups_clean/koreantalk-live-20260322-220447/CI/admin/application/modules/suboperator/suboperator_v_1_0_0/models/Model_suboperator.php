<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 김옥훈
| Create-Date : 2016-01-11
|------------------------------------------------------------------------
*/

Class Model_suboperator extends MY_Model {

//관리자 리스트
	public function suboperator_list($data) {
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$admin_id = $data['admin_id'];
		$admin_name = $data['admin_name'];
		$site_code = $data['site_code'];

		$sql = "SELECT
							a.admin_idx,
							FN_AES_DECRYPT(a.admin_id) AS admin_id,
							a.admin_name,
							a.admin_phone,
							a.site_code,
							ifnull(b.site_name,'관리자') as site_name ,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d %H:%i') as  ins_date,
							a.del_yn
						FROM
							tbl_admin a
              left join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn ='N'
              and a.admin_idx>1
						";

		if($site_code != ""){
			$sql .= " AND a.site_code = '$site_code' ";
		}
    if($admin_name != ""){
			$sql .= " AND a.admin_name LIKE '%$admin_name%' ";
		}

		if($admin_id != ""){
			$sql .= " AND FN_AES_DECRYPT(a.admin_id) LIKE '%$admin_id%' ";
		}

		$sql .= " ORDER BY a.ins_date DESC LIMIT ?, ? ";

		return $this->query_result($sql,array(
															 $page_no,
															 $page_size
															 ),$data
														 );

	}

//관리자 리스트 카운트
	public function suboperator_list_count($data) {
		$admin_id = $data['admin_id'];
		$admin_name = $data['admin_name'];
		$site_code = $data['site_code'];
    
		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_admin a
						WHERE
							a.del_yn ='N'
              and a.admin_idx>1
						";

		if($admin_name != ""){
			$sql .= " AND a.admin_name LIKE '%$admin_name%' ";
		}
    if($site_code != ""){
			$sql .= " AND a.site_code = '$site_code' ";
		}

		if($admin_id != ""){
			$sql .= " AND FN_AES_DECRYPT(a.admin_id) LIKE '%$admin_id%' ";
		}


		return	$this->query_cnt($sql,array());
	}

  //중복체크
	public function id_check($data){

		$admin_id = $data['admin_id'];

		$sql = "SELECT
							a.admin_idx,
							FN_AES_DECRYPT(a.admin_id) AS admin_id,
							a.admin_name,
							a.admin_phone,
							a.site_code,
							a.admin_right
						FROM
							tbl_admin a
						WHERE
						  a.admin_id = FN_AES_ENCRYPT(?)
						";

		return $this->query_row($sql,array(
														$admin_id
														),$data
													);
	}

//관리자 등록
	public function suboperator_reg_in($data) {
		$admin_id=$data['admin_id'];
		$admin_name=$data['admin_name'];
		$admin_phone=$data['admin_phone'];
		$admin_pw=$data['admin_pw'];
		$admin_right=$data['admin_right'];
		$site_code=$data['site_code'];

		if($site_code==""){
			$admin_grade ="A";
		}else{
			$admin_grade = NULL;
		}

		$this->db->trans_begin();

	  $sql = "INSERT INTO
					  	tbl_admin
						(
							admin_grade,	
							admin_id,
							admin_name,
							admin_phone,
							admin_pw,
							admin_right,
							site_code,
							ins_date,
							upd_date
						)VALUES(
							?,
							FN_AES_ENCRYPT(?),
							?,
							?,
							SHA2(?,512),
							?,
							?,
							now(),
							now()
						)
					";

		  $this->query($sql
							,array(
								$admin_grade,
								$admin_id,
								$admin_name,
								$admin_phone,
								$admin_pw,
								$admin_right,
								$site_code,
								)
							);

	  if($this->db->trans_status() === FALSE){
	  $this->db->trans_rollback();
	  return "0";
	  }else{
	  $this->db->trans_commit();
	  return "1";
	  }
	}

	//상세
	public function suboperator_detail($data){

		$admin_idx = $data['admin_idx'];

		$sql = "SELECT
							a.admin_idx,
							FN_AES_DECRYPT(a.admin_id) AS admin_id,
							a.admin_name,
							a.admin_phone,
							a.site_code,
							a.admin_right
						FROM
							tbl_admin a
						WHERE
							a.del_yn = 'N'
							AND a.admin_idx = ?
						";

		return $this->query_row($sql,array(
														$admin_idx
														),$data
													);
	}

//관리자 수정
	public function suboperator_mod_up($data) {
		$admin_idx=$data['admin_idx'];
		$admin_name=$data['admin_name'];
		$admin_phone=$data['admin_phone'];
		$admin_pw=$data['admin_pw'];
    $admin_right=$data['admin_right'];

		if($site_code==""){
			$admin_grade ="A";
		}else{
			$admin_grade = NULL;
		}

		$this->db->trans_begin();

		if($admin_pw ==""){
		  $sql = "
				update
				  tbl_admin
				set
				  admin_grade=?,	
					admin_name=?,
					admin_phone=?,
					admin_right=?,
					upd_date =now()
        where admin_idx=?
			";

			  $this->query($sql
								,array(
									$admin_grade,	
									$admin_name,
									$admin_phone,
									$admin_right,
									$admin_idx
									)
								);
		}else{
		 $sql = "
				update
				  tbl_admin
				set
					admin_name=?,
					admin_phone=?,
					admin_pw=SHA2(?,512),
					admin_right=?,
					upd_date =now()
        where admin_idx=?
			";

			  $this->query($sql
								,array(
									$admin_name,
									$admin_phone,
									$admin_pw,
									$admin_right,
									$admin_idx
									)
								);


		}

	  if($this->db->trans_status() === FALSE){
	  $this->db->trans_rollback();
	  return "0";
	  }else{
	  $this->db->trans_commit();
	  return "1";
	  }

	}

	public function suboperator_del($data){
		$admin_idx=$data['admin_idx'];

		$sql="UPDATE
						tbl_admin
					SET
						del_yn = 'Y',
						upd_date = NOW()
					WHERE
						admin_idx = ?
		";

		$this->query($sql,
								array(
								$admin_idx
								),
								$data
							);

		if($this->db->trans_status() === FALSE){
	  $this->db->trans_rollback();
	  return "0";
	  }else{
	  $this->db->trans_commit();
	  return "1";
	  }

	}



}
?>
