<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2019-06-10
| Memo : 휴대폰 번호 인증
|------------------------------------------------------------------------

input_check 가이드
_________________________________________________________________________________
|  !!. 변수설명
| $key       : 파라미터로 받을 변수명
| $empty_msg : 유효성검사 실패 시 전송할 메세지,
|              ("empty_msg" => "유효성검사 메세지") 로 구분하며 list 타입임.
| $focus_id  : 유효성검사 실패 시 foucus 이동 ID,
|              ("focus_id" => "foucus 대상 ID")
| $ternary  : 삼항 연산자 받을 변수명
|              ("ternary" => "1")
| $esc       : 개행문자 제거 요청시 true, 아닐시 false
|              false를 요청하는 경우-> (ex. 장문의 글 작성 시 false)
|           	 값이 array 형태일 경우 false로 적용
| $regular_msg : 정규표현식 검사 실패 시 전송할 메세지,
|              ("regular_msg" => "정규표현식 메세지","type" => "number")
| $type    	: 유효성검사할 타입
|           	 number   : 숫자검사
|            	email    : 이메일 양식 검사
|            	password : 비밀번호 양식 검사
|            	tel1     : 전화번호 양식 검사 (- 미포함)
|            	tel2     : 전화번호 양식 검사 (- 포함)
|            	custom   : 커스텀 양식, $custom의 양식으로 검사함
|            	default  : default, 검사를 안합니다.
| $custom 	: 유효성검사 custom으로 진행 시 받을 값 (정규표현식)
|
|  !!!. 값이 array형태로 들어올 경우
| $this->input_chkecu("파라미터로 받을 변수명[]");
| 형태로 받는다.
|_________________________________________________________________________________
*/

class email_verify_v_1_0_0 extends MY_Controller {

  function __construct(){
    parent::__construct();

    $this->load->model('Email_verify_v_1_0_0/model_email_verify');
  }

  // 이메일 인증키 발급
  public function email_verify_setting(){
    header('Content-Type: application/json');
  
    $member_email = $this->_input_check("member_email",array("empty_msg"=>lang('lang_10336','아이디를 입력해주세요.'),"regular_msg" => lang('lang_10337','아이디는 이메일 형식으로 입력해 주세요.'),"type" => "email", "focus_id"=>"member_email"));
    $verify_num = $this->global_function->create_verify_num('verify',6);//휴대폰 인증 번호 생성

//    $member_email="ydkman@naver.com";
    $data['member_email'] = $member_email;
    $data['verify_num'] = $verify_num;

    $tel_overlap_check = $this->model_email_verify->email_verify_initial($data); // 휴대폰번호 초기화

    $result = $this->model_email_verify->email_verify_setting($data); // 휴대폰번호 인증을 위한 insert

    if($result == 0) {
			$response = new stdClass;
			$response->code = "-1"; // 인증번호 발급 실패
			$response->code_msg = lang('lang_10338','인증번호 발급 실패');
			echo json_encode($response);
			exit;
		}else{
      # 이메일 보내기
      $to = array();
      array_push($to, $member_email);

      $subject = "[".SERVICE_NAME."]". lang('lang_10339','이메일 인증 확인 메일입니다.'); # 메일 제목
      $message = $this->load->view(mapping('email_verify').'/view_auth_to_email', array("data"=>$data), true);

      $result = $this->_web_sendmail($to, $subject, $message);

      if($result == '0'){
        $response->code = "0";
        $response->code_msg = lang('lang_10340','메일발송에 실패하였습니다.');
        echo json_encode($response);
        exit;

      }else if($result == '1'){

        $get_verify_info = $this->model_email_verify->verify_info_get($data);
        $response = new stdClass;
        $response->code = "1000"; // 가입성공
        $response->code_msg = lang('lang_10341','발급된 인증번호를 입력해주세요');
        $response->email_verify_idx = $get_verify_info->email_verify_idx;
        $response->verify_num = $get_verify_info->verify_num;


        echo json_encode($response);
        exit;
      }
		}
  }

  // 이메일 인증키 재발급
  public function email_verify_resetting(){
    header('Content-Type: application/json');
    $email_verify_idx = $this->_input_check("email_verify_idx",array());
    $member_email = $this->_input_check("member_email",array("empty_msg"=>lang('lang_10342','아이디를 입력해주세요.'),"regular_msg" => lang('lang_10343','아이디는 이메일 형식으로 입력해 주세요.'),"type" => "email", "focus_id"=>"member_email"));
    $verify_num = $this->global_function->create_verify_num('verify',6);//휴대폰 인증 번호 생성

    $data['email_verify_idx'] = $email_verify_idx;
    $data['member_email'] = $member_email;
    $data['verify_num'] = $verify_num;

    $result = $this->model_email_verify->email_verify_resetting($data); // 휴대폰번호 인증을 위한 update

    if($result == 0) {
			$response = new stdClass;
			$response->code = "-1"; // 인증번호 발급 실패
			$response->code_msg = lang('lang_10344','인증번호 발급 실패');
			echo json_encode($response);

		}else if($result == 1) {

     # 이메일 보내기
      $to = array();
      array_push($to, $member_check->member_email);

      $subject = "[".SERVICE_NAME."]". lang('lang_10345','이메일 인증 확인 메일입니다.'); # 메일 제목
      $message = $this->load->view(mapping('email_verify').'/view_auth_to_email', array("data"=>$data), true);

      $result = $this->_web_sendmail($to, $subject, $message);

      if($result == '0'){
        $response->code = "0";
        $response->code_msg = lang('lang_10346','메일발송에 실패하였습니다.');
        echo json_encode($response);
        exit;

      }else if($result == '1'){

        $verify_info_get = $this->model_email_verify->verify_info_get($data);//insert된 인증정보 가져오기
        $response = new stdClass;
        $response->code = "1000"; // 가입성공
        $response->code_msg = lang('lang_10347','재발급된 인증번호를 입력해주세요');
        $response->email_verify_idx = $verify_info_get->email_verify_idx;
        $response->verify_num = $verify_info_get->verify_num;

        echo json_encode($response);
        exit;
		  }
		}
  }

  // 이메일  인증키 확인
  public function email_verify_confirm(){
    header('Content-Type: application/json');
    $email_verify_idx = $this->_input_check("email_verify_idx",array());
    $verify_num = $this->_input_check("verify_num",array());

    $data['email_verify_idx'] = $email_verify_idx;
    $data['verify_num'] = $verify_num;

    $verify_num_check = is_numeric($verify_num);  // 인증번호에 대한 유효성 검사

    if($verify_num_check == 0) {
      $response = new stdClass;
			$response->code = "-1"; // 잘못된 인증번호 입니다.
			$response->code_msg = lang('lang_10348','잘못된 인증번호 입니다.');
			echo json_encode($response);
			exit;
    }

    $result = $this->model_email_verify->email_verify_confirm($data); // 인증번호 일치여부 확인

    if($result == 0) {
			$response = new stdClass;
			$response->code = "-1"; // 인증 실패
			$response->code_msg = lang('lang_10349','인증 실패');
			echo json_encode($response);
			exit;
		}else if($result == 1) {
      $change_verify_yn = $this->model_email_verify->change_verify_yn($data);
      if($change_verify_yn == 0) {
        $response = new stdClass;
  			$response->code = "-1"; // 관리자에게 문의해 주세요.  ->> 인증번호는 일치하는데 인증값 변경이 실패하는 경우.
  			$response->code_msg = lang('lang_10350','관리자에게 문의해 주세요.');
  			echo json_encode($response);
  			exit;
      }else {
        $email_get = $this->model_email_verify->email_get($data); // 인증여부, 전화번호 가져오기
  			$response = new stdClass;
  			$response->code = "1000"; // 인증 성공
  			$response->code_msg = lang('lang_10351','인증 성공');
  			$response->verify_yn = $email_get->verify_yn;
  			echo json_encode($response);
  			exit;
      }
		}
  }

} // 클래스의 끝
?>
