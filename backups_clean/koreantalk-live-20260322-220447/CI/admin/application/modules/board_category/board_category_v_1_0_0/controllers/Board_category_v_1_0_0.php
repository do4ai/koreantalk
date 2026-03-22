<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 김용덕
| Create-Date : 2023-11-03
| Memo : 게시판 카테고리 관리
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

class Board_category_v_1_0_0 extends MY_Controller{

	// 생성자 영역 
	function __construct(){
		parent::__construct();
		$this->load->model(mapping('board_category').'/model_board_category');
	}

	// Index 
	public function index(){
		$this->board_category_list();
	}

	//  리스트
	public function board_category_list(){
		$this->_view(mapping('board_category').'/view_board_category_list');
	}

	//  리스트 가져오기
	public function board_category_list_get(){		
		$category_name = $this->_input_check('category_name',array());
		$s_date = $this->_input_check('s_date',array());
		$e_date = $this->_input_check('e_date',array());
		$history_data = $this->_input_check("history_data",array());
    $page_num = $this->_input_check("page_num",array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['category_name'] = $category_name;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['page_no'] = ($page_num-1)*$page_size;
		$data['page_size'] = $page_size;

		$result_list = $this->model_board_category->board_category_list($data);
		$result_list_count = $this->model_board_category->board_category_list_count($data);

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->page_num = $page_num;
		$response->history_data = $history_data;

		$this->_list_view(mapping('board_category').'/view_board_category_list_get', $response);
	}

	//  등록폼
	public function board_category_reg(){
		$response = new stdClass();

		$this->_view(mapping('board_category').'/view_board_category_reg',$response);

	}

	//   등록
	public function board_category_reg_in(){
		$category_name = $this->_input_check('category_name',array("empty_msg"=>"카테고리명을 입력해주세요.","focus_id"=>"category_name"));

		$data['category_name'] = $category_name;

		$result = $this->model_board_category->board_category_reg_in($data);

		$response = new StdClass();
		if($result == 0){
			$response->code     = "0";
			$response->code_msg = "실패";
		}else{
			$response->code     = "1000";
			$response->code_msg = "등록되었습니다.";
		}

		echo json_encode($response);
		exit;
	}

	//   수정폼
	public function board_category_mod(){
		$board_category_idx = $this->_input_check('board_category_idx',array());
		$history_data = $this->_input_check("history_data",array());
		$data['board_category_idx'] = $board_category_idx;

		$result = $this->model_board_category->board_category_detail($data);

		$response = new stdClass();

		$response->result = $result;
		$response->history_data = $history_data;

		$this->_view(mapping('board_category').'/view_board_category_mod',$response);
	}

	//  수정
	public function board_category_mod_up(){
		$board_category_idx = $this->_input_check('board_category_idx ',array());
		$category_name = $this->_input_check('category_name',array("empty_msg"=>"카테고리명을 입력해주세요.","focus_id"=>"category_name"));

		$data['category_name'] = $category_name;
		$data['board_category_idx'] = $board_category_idx;


		# model 3.   수정
		$result = $this->model_board_category->board_category_mod_up($data);
		$response = new StdClass();
		if($result == "0"){
			$response->code     = "0";
			$response->code_msg = "실패";

		}else{
			$response->code     = "1000";
			$response->code_msg = "수정되었습니다.";
		}

		echo json_encode($response);
		exit;

	}


	// 삭제
	public function board_category_del(){
		$board_category_idx = $this->_input_check('board_category_idx ',array());

		$data['board_category_idx'] = $board_category_idx;

		# model. 2-1  삭제
		$result = $this->model_board_category->board_category_del($data);

		$response = new StdClass();

		if($result == "0"){
			$response->code     = "0";
			$response->code_msg = "실패";

		}else{
			$response->code = "1000";
			$response->code_msg = "삭제되었습니다.";

		}
		echo json_encode($response);
		exit;
	}


	// 상태변경
	public function board_category_state_mod_up(){
		$board_category_idx = $this->_input_check('board_category_idx ',array());
		$board_category_state = $this->_input_check('board_category_state ',array());

		$data['board_category_idx'] = $board_category_idx;
		$data['board_category_state'] = $board_category_state;

		# model. 2-1  삭제
		$result = $this->model_board_category->board_category_state_mod_up($data);

		$response = new StdClass();

		if($result == "0"){
			$response->code     = "0";
			$response->code_msg = "상태변경 실패하였습니다.";

		}else{
			$response->code = "1000";
			$response->code_msg = "상태변경 하였습니다.";

		}
		echo json_encode($response);
		exit;
	}



}
