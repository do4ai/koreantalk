<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
  .  ____  .    __________________________________________________________
  |/      \|   | Create-Date :  2018.05.31 | Author : 서민규
 [| ♥    ♥ |]  | Modify-Date :  2017.05.31 | Editor : 서민규
  |___==___|  V  Class-Name  :  Member
             / | Memo        :  회원가입
               |__________________________________________________________
*/

Class Model_member_out extends MY_Model {

  // 5. 회원탈퇴
	public function member_out_mod_up($data){

		$member_leave_reason = $data['member_leave_reason']; 

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_corp
						SET
              corp_state = '3',
              member_leave_reason=?, 
              member_leave_date=now(), 
							upd_date = NOW()
						WHERE
							corp_idx = ?
						";

		$this->query($sql,array(					
                 		        $member_leave_reason,
                            $this->corp_idx
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
