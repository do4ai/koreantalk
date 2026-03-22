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

class Start_popup_v_2_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

		$this->load->model(mapping('start_popup').'/model_start_popup');
	}

  // 인덱스
	public function index(){
		$this->start_popup_list();
	}

	// 시작 팝업 리스트 
	public function start_popup_list(){
		$this->_view(mapping('start_popup').'/view_start_popup_list');
	}

	// 시작 팝업 리스트 가져오기 
	public function start_popup_list_get(){
		$title = $this->_input_check('title',array());
		$state = $this->_input_check('state',array());
		$s_date = $this->_input_check('s_date',array());
		$e_date = $this->_input_check('e_date',array());
		$history_data = $this->_input_check("history_data",array());
		$page_num = $this->_input_check('page_num',array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;
		$data['title'] = $title;
		$data['state'] = $state;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;

		$result_list = $this->model_start_popup->start_popup_list($data); // 시작 팝업 리스트
		$result_list_count = $this->model_start_popup->start_popup_list_count($data); // 시작 팝업 리스트 총 카운트

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count,$page_size,$page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->history_data = $history_data;

		$this->_list_view(mapping('start_popup').'/view_start_popup_list_get',$response);
	}

	// 시작 팝업 상세
	public function start_popup_detail(){
		$start_popup_idx = $this->_input_check('start_popup_idx',array());
		$history_data = $this->_input_check("history_data",array());

		$data['start_popup_idx'] = $start_popup_idx;

		$result = $this->model_start_popup->start_popup_detail($data); // 시작 팝업 상세

		$response = new stdClass();

		$response->result = $result;
		$response->history_data = $history_data;

		$this->_view(mapping('start_popup').'/view_start_popup_detail',$response);
	}

	// 시작 팝업 수정 
	public function start_popup_mod_up(){
		$start_popup_idx = $this->_input_check('start_popup_idx',array());
		$title = $this->_input_check('title',array("empty_msg"=>"이벤트명을 입력해주세요.","focus_id"=>"title"));
		$img_url = $this->_input_check('img_url[]',array("empty_msg"=>"대표 이미지를 등록해주세요."));
		$link_url = $this->_input_check('link_url',array());
		$state = $this->_input_check('state',array("ternary" => "0"));

		$data['start_popup_idx'] = $start_popup_idx;
		$data['title'] = $title;
		$data['img_url'] = $img_url;
		$data['link_url'] = $link_url;
		$data['state'] = $state;

		$result = $this->model_start_popup->start_popup_mod_up($data); // 시작 팝업 수정 

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

	// 시작 팝업 등록 폼 
	public function start_popup_reg(){
		$this->_view(mapping('start_popup').'/view_start_popup_reg');
	}

  // 시작 팝업 등록 
	public function start_popup_reg_in(){
		$title = $this->_input_check('title',array("empty_msg"=>"이벤트명을 입력해주세요.","focus_id"=>"title"));
		$img_url = $this->_input_check('img_url[]',array("empty_msg"=>"대표 이미지를 등록해주세요."));
		$link_url = $this->_input_check('link_url',array());
		$state = $this->_input_check('state',array("ternary" => "0"));

		$data['title'] = $title;
		$data['img_url'] = $img_url;
		$data['link_url'] = $link_url;
		$data['state'] = $state;

		$result = $this->model_start_popup->start_popup_reg_in($data); // 시작 팝업 등록 

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

	// 시작 팝업 삭제 
	public function start_popup_del(){
		$start_popup_idx = $this->_input_check('start_popup_idx',array());

		$data['start_popup_idx'] = $start_popup_idx;

		$result = $this->model_start_popup->start_popup_del($data); // 시작 팝업 삭제 

		$response = new StdClass();

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

  // 시작 팝업 상태 변경 
	public function start_popup_state_mod_up(){
		$start_popup_idx = $this->_input_check("start_popup_idx",array("empty_msg"=>"키가 누락되었습니다."));
		$state = $this->_input_check("state",array("empty_msg"=>"상태 코드가 누락되었습니다."));

		$data['start_popup_idx']  = $start_popup_idx;
		$data['state'] = $state;

		$result = $this->model_start_popup->start_popup_state_mod_up($data); // 시작 팝업 상태 변경 

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

}	// 클래스의 끝
