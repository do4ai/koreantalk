<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 담당자관리
|------------------------------------------------------------------------
*/

Class Model_member_info extends MY_Model {



  // 회원 정보
	public function member_info_detail($data){		
    $member_idx =$data['member_idx'];

		$sql = "SELECT
							member_idx,							
							member_nickname,							
							current_lang,							
							FN_AES_DECRYPT(member_id) AS member_id,
							FN_AES_DECRYPT(member_name) As member_name,
							FN_AES_DECRYPT(member_phone) As member_phone,						
							upd_date	
						FROM
							tbl_member
						WHERE
							del_yn = 'N'						  
              and member_idx=?
							";

		return $this->query_row($sql,
														array(
														$member_idx
														),
                            $data
													);
	}

  // 5. 회원정보수정
	public function member_info_mod_up($data){

		$member_idx = $data['member_idx']; 
		$member_nickname = $data['member_nickname']; 
		$current_lang = $data['current_lang']; 
		$member_phone = $data['member_phone']; 


		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_member
						SET
							member_nickname =  ?,
							current_lang =  ?,              
              member_phone = FN_AES_ENCRYPT(?),        
							upd_date = NOW()
						WHERE
							member_idx = ?
						";

		$this->query($sql,array(							
								 $member_nickname,
								 $current_lang,
								 $member_phone,
								 $member_idx
							   ),$data
					  	 );

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "-1";
		}else{
			$this->db->trans_commit();
			return "1000";
		}
	}


  








}	// 클래스의 끝
?>
