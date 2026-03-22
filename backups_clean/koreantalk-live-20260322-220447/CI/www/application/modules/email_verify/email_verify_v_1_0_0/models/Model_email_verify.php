<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Author : -member_email
| Create-Date : 2019-06-10
| Memo : 휴대폰 번호 인증
|------------------------------------------------------------------------
*/

Class Model_email_verify extends MY_Model {

  //휴대폰번호 중복체크
	public function email_overlap_check($data){
		$member_email = $data['member_email'];

		$sql = "SELECT
							email_verify_idx
						FROM
							tbl_email_verify
						WHERE
							member_email = FN_AES_ENCRYPT(?)
						";
		return $this->query_row($sql,
														array($member_email),
														$data
														);
	}

	
  //휴대폰 번호 초기화
	public function email_verify_initial($data){
		$member_email = $data['member_email'];		

		$this->db->trans_begin();

		$sql = "update
							tbl_email_verify
            set 
							del_yn='Y',
							upd_date=now()
						where 
						member_email = FN_AES_ENCRYPT(?)
		";

		$this->query($sql,
								array(
								$member_email,
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

  //휴대폰 인증을 위한 정보 insert
	public function email_verify_setting($data){
		$member_email = $data['member_email'];
		$verify_num = $data['verify_num'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_email_verify
						(
							member_email,
							verify_num,
							verify_yn,
							verify_cnt,
							ins_date,
							upd_date
						)VALUES(
							FN_AES_ENCRYPT(?),
							?,
							'N',
							'1',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
								array(
								$member_email,
								$verify_num
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

  //휴대폰 인증을 위한 정보 update (재인증)
	public function email_verify_resetting($data){
		$email_verify_idx = $data['email_verify_idx'];
		$member_email = $data['member_email'];
		$verify_num = $data['verify_num'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_email_verify
						SET
							member_email = FN_AES_ENCRYPT(?),
							verify_num = ?,
							verify_cnt = verify_cnt + 1,
							upd_date = NOW()
						WHERE
							email_verify_idx = ?
						";

		$this->query($sql,
								array(
								$member_email,
								$verify_num,
								$email_verify_idx
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

  //insert된 인증정보 가져오기
	public function verify_info_get($data){
		$member_email = $data['member_email'];

		$sql = "SELECT
							email_verify_idx,
							member_email,
							verify_num
						FROM
							tbl_email_verify
						WHERE
							member_email = FN_AES_ENCRYPT(?)
							and del_yn='N'
						";

		return $this->query_row($sql,
														array(
														$member_email
														),
														$data
														);
	}

  // 인증번호 확인
	public function email_verify_confirm($data){
		$email_verify_idx = $data['email_verify_idx'];
		$verify_num = $data['verify_num'];

		$sql = "SELECT
							COUNT(1) AS cnt
						FROM
							tbl_email_verify
						WHERE
							email_verify_idx = ?
							AND verify_num = ?
							and del_yn='N'
		";

		return $this->query_cnt($sql,
														array($email_verify_idx,
														$verify_num
														),
														$data
														);
	}

  // 인증여부 변경(인증 확인해주기)
	public function change_verify_yn($data){
		$email_verify_idx = $data['email_verify_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_email_verify
						SET
							verify_yn = 'Y',
							upd_date = NOW()
						WHERE
							email_verify_idx = ?
							and del_yn='N'
						";

		$this->query($sql,array(
											$email_verify_idx
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

  // 인증여부, 전화번호 가져오기
	public function email_get($data){
		$email_verify_idx = $data['email_verify_idx'];

		$sql = "SELECT
							verify_yn,
							member_email
						FROM
							tbl_email_verify
						WHERE
							email_verify_idx = ?
							and del_yn='N'
						";

		return $this->query_row($sql,
														array(
														$email_verify_idx
														),
														$data
														);
	}

}	// 클래스의 끝
?>
