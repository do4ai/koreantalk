<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	-
| Create-Date : 2019-07-11
| Memo : 시작 팝업 관리
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

class Start_popup_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

		$this->load->model(mapping('start_popup').'/model_start_popup');

	}

  // 인덱스
	public function index(){
		$this->start_popup_detail();
	}

  // 시작팝업 수정 폼
	public function start_popup_detail(){
		$result=$this->model_start_popup->start_popup_detail(); //시작팝업 상세

		$response = new stdClass();

		$response->result = $result;

		$this->_view(mapping('start_popup').'/view_start_popup_detail',$response);
	}

  // 시작팝업 수정
	public function start_popup_mod_up(){
		$title = $this->_input_check("title",array("empty_msg"=>"제목을 입력해주세요.","focus_id"=>"title"));
		$contents = $this->_input_check("contents",array("empty_msg"=>"내용을 입력해주세요.","focus_id"=>"contents"));
		$img_path = $this->_input_check("img_path",array());
		$start_date = $this->_input_check("start_date",array("empty_msg"=>"시작일자를 입력해주세요.","focus_id"=>"start_date"));
		$end_date = $this->_input_check("end_date",array("empty_msg"=>"마감일자를 입력해주세요.","focus_id"=>"end_date"));
		$link_url = $this->_input_check("link_url",array());
		$state = $this->_input_check("state",array());

		$data['title']=$title;
		$data['contents']=$contents;
		$data['img_path']=$img_path;
		$data['start_date']=$start_date;
		$data['end_date']=$end_date;
		$data['link_url']=$link_url;
		$data['state']=$state;

		$result=$this->model_start_popup->start_popup_mod_up($data);//시작팝업 수정

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
	}

}
