<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 담당자관리
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

class Member_info_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

    $this->load->model(mapping('member_info').'/model_member_info'); 

    if(empty($this->member_idx)){
        redirect("/".mapping('login')."?return_url=/".$this->current_lang."/".mapping('member_info'));
        exit;
    }
    

 
	}

//인덱스
  public function index() {
    $this->member_info_mod();
  }


//대시보드
  public function member_info_mod(){ 			 

    $data['member_idx'] = $this->member_idx;
    
    $result = $this->model_member_info->member_info_detail($data); ; // 리스트 카운트

		$response = new stdClass();

		$response->result = $result;

    $this->_view(mapping('member_info').'/view_member_info_mod',$response);

  }


  // 수정
	public function member_mod_up(){
		$member_phone	= $this->_input_check('member_phone',array("empty_msg"=>lang('lang_10380','휴대폰번호를 입력해주세요.'),"focus_id"=>"member_phone"));		
		$member_nickname = $this->_input_check('member_nickname',array("empty_msg"=>lang('lang_10381','외국어 한국명이름을 입력해주세요.'),"focus_id"=>"member_nickname"));		
		$current_lang = $this->_input_check('current_lang',array("empty_msg"=>lang('lang_10382','서비스 언어를 입력해주세요.'),"focus_id"=>"current_lang"));		
		
    $data['member_idx'] = $this->member_idx;
		$data['member_nickname'] = $member_nickname;		
		$data['current_lang'] = $current_lang; 
		$data['member_phone'] = $member_phone; 

		$response = new stdClass();

		$result = $this->model_member_info->member_info_mod_up($data);  // 담당자 수정

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
