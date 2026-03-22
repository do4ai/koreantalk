<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 패스워드 변경
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

class Member_pw_change_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

		$this->load->model(mapping('member_pw_change').'/model_member_pw_change');

    if(empty($this->member_idx)){
        redirect("/". $current_nation."/".mapping('login')."?return_url=/".mapping('member_out'));
        exit;
    }
 
	}

//인덱스
  public function index() {
    $this->member_pw_change_mod();
  }
 
//대시보드
  public function member_pw_change_mod(){ 
    $this->_view(mapping('member_pw_change').'/view_member_pw_change_mod');
  }
  
  public function member_pw_mod_up(){

		$member_pw = $this->_input_check("member_pw",array("empty_msg"=>lang('lang_10384','기존 비밀번호를 입력해주세요.'),"focus_id"=>"member_pw"));    
		$member_pw_new = $this->_input_check("member_pw_new",array("empty_msg"=>lang('lang_10385','새로운 비밀번호를 입력해주세요.'), "regular_msg" => lang('lang_10386','비밀번호는 영문, 숫자 조합으로 8~15자리로 입력해 주세요.'), "type" => "custom", "custom" => "/^(?=.*[a-zA-Z])(?=.*[0-9])[A-Za-z\d~!@#$%^&*()+|=]{8,15}$/", "focus_id"=>"member_pw_new"));
		$member_pw_new_confirm = $this->_input_check("member_pw_new_confirm",array("empty_msg"=>lang('lang_10387','새로운 비밀번호를 한 번 더 입력해주세요.'),"focus_id"=>"member_pw_new_confirm"));

		$data['member_pw'] = $member_pw;

		$response = new stdClass();

		# 현재 비밀번호 확인
		$result = $this->model_member_pw_change->member_pw_confirm($data);
		if(!empty($result)){

			# 새 비밀번호와 새 비밀번호 확인
			if($member_pw_new == $member_pw){
				$response->code = "-1";
				$response->code_msg = lang('lang_10388','새 비밀번호는 기존 비밀번호와 다르게 설정해주세요.');
				$response->focus_id = "member_pw_new";

				echo json_encode($response);
				exit;
			}

			# 새 비밀번호와 새 비밀번호 확인
			if($member_pw_new != $member_pw_new_confirm){
				$response->code = "-1";
				$response->code_msg = lang('lang_10389','새 비밀번호와 새 비밀번호 확인이 동일하지 않습니다.');
				$response->focus_id = "member_pw_new_confirm";

				echo json_encode($response);
				exit;
			}

			$data['member_pw_new'] = $member_pw_new;

			$result = $this->model_member_pw_change->member_pw_mod_up($data);//회원 비밀번호 변경

			$response = new stdClass();
      if($result == "0") {
        $response->code = "-2";
        $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
      }else{
        $response->code = "1000";
        $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
      }

			echo json_encode($response);
			exit;

		} else {
			# 현재 비밀번호가 일치하지 않는 경우
			$response->code = "0";
			$response->code_msg = lang('lang_10390','현재 비밀번호가 일치 하지 않습니다.');
			$response->focus_id = "member_pw";

			echo json_encode($response);
			exit;
		}

	}
   

}// 클래스의 끝
?>
