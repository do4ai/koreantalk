<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	김용덕
| Create-Date : 2017-01-15
| Memo :  로그인
|------------------------------------------------------------------------
*/

class Model_login extends MY_Model{

// 로그인 시도
  public function login_action_member($data){

    $member_id = $data['member_id'];
    $member_pw = $data['member_pw'];

    $sql = "SELECT		
							a.member_idx,	    
							FN_AES_DECRYPT(member_id) AS member_id,
							FN_AES_DECRYPT(member_name) As member_name,
							FN_AES_DECRYPT(member_phone) As member_phone,
              a.del_yn
            from tbl_member as a
            WHERE
              a.del_yn = 'N'
              AND member_id = FN_AES_ENCRYPT(?)
              AND member_pw = SHA2(?, 512)
    ";

    return $this->query_row($sql,
                              array(
                                $member_id,
                                $member_pw
                              ),$data
                            );
  }






} // 클래스의 끝
?>
