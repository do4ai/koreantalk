<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 회원 탈퇴
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

class Member_out_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

    $this->load->model(mapping('member_out').'/model_member_out'); 

    if(empty($this->member_idx)){
        redirect("/". $current_nation."/".mapping('login')."?return_url=/".mapping('member_out'));
        exit;
    }
 
	}

//인덱스
  public function index() {
    $this->member_out_list();
  }
 
//대시보드
  public function member_out_list(){ 
    $this->_view(mapping('member_out').'/view_member_out_list');

  }

  //회원탈퇴 
  public function member_out_mod_up(){

		$member_leave_reason= $this->_input_check("member_leave_reason",array("empty_msg" => lang('lang_10383','탈퇴 사유를 입력해주세요.'), "focus_id" => "member_leave_reason"));
	
		$data['member_leave_reason'] =$member_leave_reason;

		$response = new stdClass();

		$result = $this->model_member_out->member_out_mod_up($data);

		if($result == "0") {
			$response->code = "-2";
			$response->code_msg = error_code_msg('-2'); //실패 하였습니다.
		}else{
			$response->code = "1000";
			$response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
		}

		echo json_encode($response);
		exit;
	}

   

}// 클래스의 끝
?>
