<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	김용옥훈
| Create-Date : 2018-02-17
| Memo : cron
|------------------------------------------------------------------------
*/

Class Model_cron extends MY_Model {

  //매일자정시(108)
	public function corp_profile_alarm(){
		$this->db->trans_begin();

		//알림믕록
		$sql = "SELECT
						a.member_idx,
						FN_AES_DECRYPT(a.member_name) AS member_name,
						a.all_alarm_yn as alarm_yn,
						a.device_os,
						a.gcm_key
						from tbl_member as a
					 where a.del_yn='N'
		 			  and DATE_ADD(ifnull(profile_save_date,a.ins_date), INTERVAL 30 day)<now()
		";
		$result_list=	$this->query_result($sql,array());
		foreach($result_list as $row){

			$data['member_idx'] = $row->member_idx;
			$data['gcm_key'] = $row->gcm_key;
			$data['device_os'] = $row->device_os;
			$data['title']=$title= '';
			$data['msg']=$msg= '아이들과 만나실수 있는 일정이 변경 되셨나요? 프로필에서 일정변경을 해주세요.';
			$data["index"] ="108";
			$data["alarm_yn"] =$row->alarm_yn;

			$sql = "INSERT INTO
								tbl_alarm
							(
								member_idx,
								`data`,
								title,
								msg,
								`index`,
								device_os,
								gcm_key,
								alarm_yn,
								send_yn,
								read_yn,
								del_yn,
								ins_date,
								upd_date
							)VALUES (
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								'N',
								'N',
								'N',
								NOW(),
								NOW()
							)
			";

			$this->query($sql,
									array(
									$row->member_idx,
									json_encode($data),
									$title,
									$msg,
									'108',
									$row->device_os,
									$row->gcm_key,
									$row->alarm_yn,
									)
									);
		}

    if($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      return "0";
    } else {
      $this->db->trans_commit();
      return "1";
    }
  }


	//자동취소(1분마다)
	public function auto_cancel(){
		$this->db->trans_begin();

		//알림믕록
		$sql = "SELECT
		        z.order_idx,
						a.member_idx,
						FN_AES_DECRYPT(a.member_name) AS member_name,
						a.all_alarm_yn as alarm_yn,
						a.device_os,
						a.gcm_key
						from tbl_order as z
						 join tbl_member as a on a.member_idx=z.member_idx and a.del_yn='N'
					 where z.del_yn='N'
					  and z.real_yn='Y'
						and z.order_state='3'
						and z.pay_state='0'
						and DATE_ADD(choice_date, INTERVAL 24 hour)<now()
		";
		$result_list=	$this->query_result($sql,array());
		foreach($result_list as $row){

			$data['member_idx'] = $row->member_idx;
			$data['gcm_key'] = $row->gcm_key;
			$data['device_os'] = $row->device_os;
			$data['title']=$title= '';
			$data['msg']=$msg= '아쉬워요! 지원하신 위밋이 성사되지 않았습니다.';
			$data["index"] ="110";
			$data["alarm_yn"] =$row->alarm_yn;

			$sql = "INSERT INTO
								tbl_alarm
							(
								member_idx,
								`data`,
								title,
								msg,
								`index`,
								device_os,
								gcm_key,
								alarm_yn,
								send_yn,
								read_yn,
								del_yn,
								ins_date,
								upd_date
							)VALUES (
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								'N',
								'N',
								'N',
								NOW(),
								NOW()
							)
			";

			$this->query($sql,
									array(
									$row->member_idx,
									json_encode($data),
									$title,
									$msg,
									'110',
									$row->device_os,
									$row->gcm_key,
									$row->alarm_yn,
									)
									);

		$sql="UPDATE tbl_order set order_state='5',cancel_date=now() where order_idx='$row->order_idx'";
		$this->query($sql,array());


		}

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		} else {
			$this->db->trans_commit();
			return "1";
		}
	}




	//알람발송
	public function alarm_send(){
		header('Content-Type: application/json');

		$sgcm = new GCMPushMessage();
		$sgcm->setApiKey(GCM_KEY_1);

		$sql ="SELECT
						 alarm_idx,
						 member_idx,
						 `index` as _index,
						 gcm_key,
						 device_os,
						 title,
						 msg,
						 alarm_yn,
						 data
					 FROM
						tbl_alarm
					 where
					      alarm_yn='Y'
						and send_yn='N'
						order by 	alarm_idx asc limit 5
		";
		 $result_list=	$this->query_result($sql,array());
		 $alarm_idx=0;
		 foreach($result_list as $row){
			 $data['member_idx'] = $row->member_idx;
			 $data['gcm_key'] = $row->gcm_key;
			 $data['device_os'] = $row->device_os;
			 $data['msg']=  $row->msg;
			 $data["index"] =$row->_index;
			 $body_loc_key = $row->_index;
			 $body_loc_args =[""];
			 $alarm_idx=$row->alarm_idx;

			 if($row->gcm_key !="" && $row->alarm_yn=="Y"){
					 $sgcm->setDevices($row->gcm_key);
					 $response = $sgcm->send($row->msg,$row->device_os,json_decode($row->data),$row->title,$body_loc_key,$body_loc_args,"");
			 }
		}

		$this->db->trans_begin();

		$sql="UPDATE
					 tbl_alarm
					set
						send_yn='Y',
						read_yn=if(`index` >=900,'Y','N')
					where 	 alarm_yn='Y'
					and send_yn='N'
					and alarm_idx<=$alarm_idx
		";
		$this->query($sql,array());

		if($this->db->trans_status() === FALSE){
		 $this->db->trans_rollback();
		 return "0";
		} else {
		 $this->db->trans_commit();
		 return "1";
		}
	}



	//이메일발송
	public function email_send(){
		header('Content-Type: application/json');

		$sql ="SELECT
						 smtp_email_idx,
						 `index` as _index,
						 subject,
						 to_email,
						 member_idx,
						 corp_idx,
						 data
					 FROM
						tbl_smtp
					 where
						del_yn='N'
						and send_yn='N'
						order by 	smtp_email_idx asc LIMIT 0, 2
		";

		$result_list=	$this->query_result($sql,array());

		foreach($result_list as $row){

			$config = array();
	    $config['useragent'] = 'CodeIgniter';
	    $config['mailpath']  = '/usr/sbin/sendmail';
	    $config['protocol']  = 'smtp';
	    $config['smtp_host'] = SMTP_HOST;
	    $config['smtp_user'] = SMTP_USER;
	    $config['smtp_pass'] = SMTP_PASS;
	    $config['smtp_port'] = SMTP_PORT;
	    $config['smtp_crypto'] = 'ssl';
	    $config['mailtype'] = 'html';
	    $config['charset'] = 'utf-8';
	    $config['newline'] = "\r\n";
	    $config['wordwrap'] = TRUE;

	    $this->email->initialize($config);
	    $this->email->clear(TRUE);
			$this->email->from(FROM_EMAIL, FROM_NAME);

			$data = json_encode($row->data);

			switch ($row->_index) {
				case '101' : $message = $this->load->view('cron/view_pwd_reset_email', array("data"=>$data), true);break;
				case '102' : $message = $this->load->view('cron/view_pwd_reset_email', array("data"=>$data), true);break;
				case '103' : $message = $this->load->view('cron/view_pwd_reset_email', array("data"=>$data), true);break;
 		  }

	    $this->email->to($row->to_email);
	    $this->email->subject($row->subject);
	    $this->email->message($message);

	    if ($this->email->send()) {
				$send_yn = 'Y';

				$_data['index'] = $row->_index;
				$_data['member_idx'] = $row->member_idx;
				$_data['corp_idx'] = $row->corp_idx;
				$_data['smtp_host'] = SMTP_HOST;
				$_data['smtp_user'] = SMTP_USER;
				$_data['smtp_pass'] = SMTP_PASS;
				$_data['smtp_port'] = SMTP_PORT;
				$_data['from_email'] = FROM_EMAIL;
				$_data['from_name'] = FROM_NAME;
				$_data['subject'] = $row->subject;
				$_data['to_email'] = $row->to_email;

				$json_data =json_encode($_data);

				$sql="UPDATE
							  tbl_smtp
							set
								send_yn='Y',
								result_code='$send_yn',
								result_contents='$send_yn',
								`data`='$json_data',
								send_date = NOW()
							where
								del_yn='N'
								and smtp_email_idx='$row->smtp_email_idx'
				";

					$this->query($sql,array());
				}
	    }

		$this->db->trans_begin();

		if($this->db->trans_status() === FALSE){
		 $this->db->trans_rollback();
		 return "0";
		} else {
		 $this->db->trans_commit();
		 return "1";
		}
	}



}
?>
