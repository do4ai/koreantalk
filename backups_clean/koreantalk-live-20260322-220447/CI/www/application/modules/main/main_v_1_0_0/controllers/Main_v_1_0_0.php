<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 메인
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

class Main_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

    $this->load->model(mapping('main').'/model_main'); 
	}

//인덱스
  public function index() {
    $this->main_list();
  }

//메인 화면
  public function main_list(){

    $result=$this->model_main->main_detail(); 
    // 서점,전자책(랜덤6),
    // 배너,
    // 추전topik 1개(main_view_yn ,랜덤1),
    // 공지사항 1개,

    $response = new stdClass();
    $response->banner_list = $result['banner_list'];
    $response->product_list = $result['product_list'];
    $response->electron_book_list = $result['electron_book_list'];
    $response->lecture_list = $result['lecture_list'];
    $response->notice_list = $result['notice_list'];

    $this->_view(mapping('main').'/view_main_list',$response);

   
  }


  //상태 체크
	public function member_state_check(){		
		$result=$this->model_main->member_detail(); //이용정지 체크
		$response = new stdClass();

		if($result == "0") {
			$response->code = "-2";
			$response->code_msg = error_code_msg('-2'); //실패 하였습니다.
		}else{
			$response->code = "1000";
			$response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    
      $response->member_state =$result->member_state;
		}

		echo json_encode($response);
		exit;

	}
 
 
}// 클래스의 끝
?>
