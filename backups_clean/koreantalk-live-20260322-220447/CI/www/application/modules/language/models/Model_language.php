<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2023-11-30
| Memo : 회원 언어 세팅
|------------------------------------------------------------------------
*/

Class Model_language extends MY_Model {

	public function change_lang($data){
		
		$current_lang = $data['current_lang'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_member
						SET
							current_lang = ?,
							upd_date = NOW()
						WHERE
							member_idx = ?
		";

		$this->query($sql,array(
									$current_lang,
									$this->member_idx
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


}	// 클래스의 끝
?>
