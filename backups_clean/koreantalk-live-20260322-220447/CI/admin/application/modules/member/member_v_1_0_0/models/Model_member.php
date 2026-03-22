<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 회원 관리
|------------------------------------------------------------------------
*/

Class Model_member extends MY_Model{

	// 회원 리스트
	public function member_list($data){
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$member_id = $data['member_id'];
		$member_name = $data['member_name'];
		$member_phone = $data['member_phone'];
		$member_nickname = $data['member_nickname'];
		$site_name = $data['site_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$del_yn = $data['del_yn'];

		$sql = "SELECT
							a.member_idx,
							FN_AES_DECRYPT(a.member_id) AS member_id,
							FN_AES_DECRYPT(a.member_name) AS member_name,
							FN_AES_DECRYPT(a.member_phone) AS member_phone,				
							a.member_nickname,
							a.current_lang,
							b.site_name,
							a.del_yn,
							a.ins_date
						FROM
							tbl_member a
              join tbl_site as b on b.site_code=a.current_lang
						WHERE
							1=1
          	";

		if($member_id != ""){
			$sql .= " AND FN_AES_DECRYPT(a.member_id) LIKE '%$member_id%'";
    }
		if($member_nickname != ""){
			$sql .= " AND a.member_nickname LIKE '%$member_nickname%'  ";
    }
   	if($site_name != ""){
			$sql .= " AND b.site_name LIKE '%$site_name%'  ";
    }
    if($member_name != ""){
      $sql .= " AND FN_AES_DECRYPT(a.member_name) LIKE '%$member_name%' ";
    }
		if($member_phone != ""){
      $sql .= " AND FN_AES_DECRYPT(a.member_phone) LIKE '%$member_phone%' ";
    }
		if($s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$e_date' ";
		}
		if($del_yn != ""){
			$sql .= " AND a.del_yn = '$del_yn' ";
		}

		$sql .= " ORDER BY a.ins_date DESC LIMIT ?, ? ";

  	return  $this->query_result($sql,
																array(
																$page_no,
																$page_size
																),
                                $data);
	}

	// 회원 리스트 카운트
  public function member_list_count($data){

		$member_id = $data['member_id'];
		$member_name = $data['member_name'];
		$member_phone = $data['member_phone'];
		$member_nickname = $data['member_nickname'];
		$site_name = $data['site_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$del_yn = $data['del_yn'];

		$sql = "SELECT
							COUNT(1) AS cnt
						FROM
							tbl_member a
               join tbl_site as b on b.site_code=a.current_lang
						WHERE
							1=1
          	";

		if($member_id != ""){
			$sql .= " AND FN_AES_DECRYPT(a.member_id) LIKE '%$member_id%'";
    }
		if($member_nickname != ""){
			$sql .= " AND a.member_nickname LIKE '%$member_nickname%'  ";
    }
   	if($site_name != ""){
			$sql .= " AND b.site_name LIKE '%$site_name%'  ";
    }
    if($member_name != ""){
      $sql .= " AND FN_AES_DECRYPT(a.member_name) LIKE '%$member_name%' ";
    }
		if($member_phone != ""){
      $sql .= " AND FN_AES_DECRYPT(a.member_phone) LIKE '%$member_phone%' ";
    }
		if($s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$e_date' ";
		}
		if($del_yn != ""){
			$sql .= " AND a.del_yn = '$del_yn' ";
		}

  	return  $this->query_cnt($sql,
														array(
														),
                            $data);
  }

	// 회원 리스트 엑셀
	public function member_list_excel($data){

		$member_id = $data['member_id'];
		$member_name = $data['member_name'];
		$member_phone = $data['member_phone'];
		$member_nickname = $data['member_nickname'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$del_yn = $data['del_yn'];

		$sql = "SELECT
							a.member_idx,
							FN_AES_DECRYPT(a.member_id) AS member_id,
							FN_AES_DECRYPT(a.member_name) AS member_name,
							FN_AES_DECRYPT(a.member_phone) AS member_phone,
							a.member_nickname,
							a.member_join_type,
							a.del_yn,
							a.ins_date
						FROM
							tbl_member a
						WHERE
							1=1
          	";

    if($member_id != ""){
			$sql .= " AND FN_AES_DECRYPT(a.member_id) LIKE '%$member_id%'";
    }
		if($member_nickname != ""){
			$sql .= " AND FN_AES_DECRYPT(a.member_nickname) LIKE '%$member_nickname%'  ";
    }
    if($member_name != ""){
      $sql .= " AND FN_AES_DECRYPT(a.member_name) LIKE '%$member_name%' ";
    }
		if($member_phone != ""){
      $sql .= " AND FN_AES_DECRYPT(a.member_phone) LIKE '%$member_phone%' ";
    }
		if($s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$e_date' ";
		}
		if($del_yn != ""){
			$sql .= " AND a.del_yn = '$del_yn' ";
		}

		$sql .= " ORDER BY a.ins_date DESC ";

  	return  $this->query_result($sql,
																array(
																),
                                $data);
	}

	// 회원 상세보기
	public function member_detail($data){

		$member_idx = (int)$data['member_idx'];

		$sql = "SELECT
							a.member_idx,
							a.member_join_type,
							FN_AES_DECRYPT(a.member_id) AS member_id,
							FN_AES_DECRYPT(a.member_name) AS member_name,
							FN_AES_DECRYPT(a.member_phone) AS member_phone,
							a.current_lang,
							b.site_name,
							a.member_nickname,
		          a.member_leave_reason,
              a.member_leave_date,          
							a.del_yn,
							a.ins_date,
							a.upd_date
						FROM
							tbl_member a
              join tbl_site as b on b.site_code=a.current_lang
						WHERE
							a.member_idx = ?
						";

		return  $this->query_row($sql,
														array(
														$member_idx
														),
                            $data);
	}

	// 회원 삭제하기
  public function member_del_yn_up($data){

    $member_idx = (int)$data['member_idx'];
    $del_yn = $data['del_yn'];

    $this->db->trans_begin();

    $sql = "UPDATE
              tbl_member
            SET
              del_yn = ?,
              gcm_key = NULL,
              upd_date = NOW()
            WHERE
              member_idx = ?
            ";

    $this->query($sql,
								array(
								$del_yn,
								$member_idx
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


	//  회원 정보 수정
	public function member_mod_up($data) {

		$member_idx = $data['member_idx'];
		$del_yn = $data['del_yn'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_member
						SET
							del_yn = if(del_yn='Y',del_yn,?),
              upd_date =now()
						WHERE
							member_idx = ?
						";

		$this->query($sql,
                array(
                $del_yn,
                $member_idx
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



}	// 클래스의 끝

?>
