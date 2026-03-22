<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	송민지
| Create-Date : 2023
|------------------------------------------------------------------------
*/

class Find_pw_v_1_0_0 extends MY_Controller {
  function __construct(){
    parent::__construct();

    $this->load->model(mapping('find_pw').'/model_find_pw');

  }

//인덱스
	public function index(){

		$this->find_pw_list();

	}

// find
	public function find_pw_list(){

		$this->_view(mapping('find_pw').'/view_find_pw_list');
	}

  public function find_pw_member(){

    $response = new StdClass();

    $type = $this->_input_check("type",array());

    $member_id = $this->_input_check("member_id",array("empty_msg"=>lang('lang_10356','아이디를 입력해주세요.'),"focus_id"=>"member_id"));
    $member_name = $this->_input_check("member_name",array("empty_msg"=>lang('lang_10357','이름을 입력해주세요.'),"focus_id"=>"member_name"));
    $member_phone = $this->_input_check("member_phone",array("empty_msg"=>lang('lang_10358','전화번호를 입력해주세요.'),"focus_id"=>"member_phone"));

    // $member_id ="ydkman@naver.com";
    // $member_name = "대표자명";
    // $member_phone ="01012345678";

		$data['member_id'] = $member_id;
    $data['member_name'] = $member_name;
    $data['member_phone'] = $member_phone;

    $member_check = $this->model_find_pw->member_check($data); // 회원 체크

    if(empty($member_check)) {
      $response->code = "-2";
      $response->code_msg = lang('lang_10359','일치하는 회원정보가 없습니다.');

      echo json_encode($response);
      exit;
    }

    # change_pw_key 생성
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rendom_str = "";
    $loopNum = 32;

    while($loopNum--){
      $tmp = mt_rand(0, strlen($characters));
      $rendom_str .= substr($characters,$tmp,1);
    }

    $data['change_pw_key'] = $rendom_str;

    # model. 비밀번호 변경 인증키 발급
    $this->model_find_pw->pw_change_key_up_member($data);

    $data['member_email'] = $member_check->member_email;
    $data['member_name'] = $member_check->member_name;
		
    # 이메일 보내기
    $to = array();
    array_push($to, $member_check->member_email);

    $subject = "[".SERVICE_NAME."]". lang('lang_10360','비밀번호 변경 메일입니다.'); # 메일 제목
    $message = $this->load->view(mapping('find_pw').'/view_find_pw_to_email', array("data"=>$data), true);

    $result = $this->_web_sendmail($to, $subject, $message);

    if($result == '0'){
      $response->code = "0";
      $response->code_msg = lang('lang_10361','메일발송에 실패하였습니다.');
      echo json_encode($response);
      exit;

    }else if($result == '1'){
      $response->code = "1000";
      $response->code_msg = lang('lang_10362','정상적으로 처리되었습니다.');

      echo json_encode($response);
      exit;
    }
	}

} // 클래스의 끝
?>
