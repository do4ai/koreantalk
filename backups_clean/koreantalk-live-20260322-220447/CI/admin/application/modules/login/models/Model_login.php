<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :  -
| Create-Date : 2017-07-24
| Memo : 로그인
|------------------------------------------------------------------------
*/

Class Model_login extends MY_Model {

  // 로그인 체크 
	public function login_check($data) {
		
    $admin_id = $data['admin_id'];
		$admin_pw = $data['admin_pw'];

		$sql = "SELECT
							admin_idx,
							FN_AES_DECRYPT(admin_id) AS admin_id,
							admin_name,
							admin_grade,
							site_code,
							admin_right
						FROM
							tbl_admin
						WHERE
							FN_AES_DECRYPT(admin_id) = ?
							AND admin_pw = SHA2(?,512)
					";

		return $this->query_row($sql,
														array(
														$admin_id,
														$admin_pw
														),
														$data);
	}
}
?>
