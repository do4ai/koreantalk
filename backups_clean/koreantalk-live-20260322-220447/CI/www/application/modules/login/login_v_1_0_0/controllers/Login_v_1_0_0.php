<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 로그인
|------------------------------------------------------------------------

_input_check 가이드
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

class Login_v_1_0_0 extends MY_Controller {
  function __construct(){
    parent::__construct();

    $this->load->model(mapping('login').'/model_login');

  }

	public function index(){
    $this->login_list();
	}


// 
  public function login_list() {
		$return_url = $this->_input_check("return_url",array());

		$response = new stdClass();
		$response->return_url = $return_url;

		$this->_view2(mapping('login').'/view_login_list',$response);

  }

	// 로그인
	public function login_action_member(){
		$member_id = $this->_input_check("member_id",array("empty_msg"=>lang('lang_10375','아이디를 입력해주세요.'),"focus_id"=>"member_id"));
		$member_pw = $this->_input_check("member_pw",array("empty_msg"=>lang('lang_10376','패스워드를 입력해주세요.'),"focus_id"=>"member_pw"));
    $gcm_key = $this->_input_check("gcm_key",array());
    $device_os = $this->_input_check("device_os",array());

		$data['member_id'] = $member_id;
		$data['member_pw'] = $member_pw;

		$response = new stdClass();

		$result = $this->model_login->login_action_member($data); // 로그인

		if(!empty($result)){

			if($result->del_yn  == "Y" ){
		  	$response->code = -2;
				$response->code_msg = lang('lang_10377','사용할 수 없는 ID 입니다. 관리자에 문의 바랍니다.');

				echo json_encode($response);
				exit;
			}


			$response->code = "1000";
			$response->code_msg = lang('lang_10378','로그인 되었습니다.');

			$response->member_idx =  $result->member_idx;

			$member_data = array(
				"member_idx" => $result->member_idx,
			);
			$this->session->set_userdata($member_data);
    
      set_cookie('member_idx', $result->member_idx, 3600*24*365);

		}else{
			$response->code = "-2";
			$response->code_msg = lang('lang_10379','회원정보가 일치 하지 않습니다. 다시 확인 하여 주세요.');
		}

		echo json_encode($response);
		exit;
  }

}// 클래스의 끝
?>
