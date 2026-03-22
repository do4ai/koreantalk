<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
  .  ____  .    __________________________________________________________
  |/      \|   | Create-Date :  2018.05.31 | Author : 서민규
 [| ♥    ♥ |]  | Modify-Date :  2017.05.31 | Editor : 서민규
  |___==___|  V  Class-Name  :  Member
             / | Memo        :  회원가입
               |__________________________________________________________
*/

Class Model_join extends MY_Model {

	//약관 리스트
	public function terms_list() {

		$sql = "SELECT
							terms_management_idx,
							title,
							type,
							member_type,
							contents,
							upd_date
						FROM
							tbl_terms_management
						WHERE type<2
            and site_code='$this->current_lang'
							
          	";

  	return $this->query_result($sql,
                                array(
																)
                              );

	}

	//상세
  public function terms_detail($data){

    $type = $data['type'];

    $sql = "SELECT
              terms_management_idx,
              title,
              contents
            FROM
              tbl_terms_management
            WHERE  type =?
    ";

    return $this->query_row($sql,
                            array(
                            $type
                            )
                            );
  }

	// 1. 아이디 중복 체크
	public function member_id_check($data) {

		$member_id = $data['member_id'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_member
						WHERE
							member_id = FN_AES_ENCRYPT(?)
						";

		return $this->query_cnt($sql,array($member_id));
	}

	//회원 등록
	public function	member_reg_in($data) {

		$member_id = $data['member_id'];
		$member_pw = $data['member_pw'];
		$member_name = $data['member_name'];
		$member_nickname = $data['member_nickname'];
		$member_phone = $data['member_phone'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_member
						(
							member_id,
							member_pw,
							member_name,
							member_phone, 
							member_nickname,
              current_lang,
							del_yn,
							ins_date,
							upd_date
						)VALUES(
            	FN_AES_ENCRYPT(?), 
							SHA2(?,512), 
							FN_AES_ENCRYPT(?),  
							FN_AES_ENCRYPT(?),  							 
							?, 
							?, 
							'N',
							NOW(),
							NOW()
						);

						";
		$this->query($sql,
                array(
                $member_id,
                $member_pw,
                $member_name,
                $member_phone,
                $member_nickname,              
                $this->current_lang,              

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
