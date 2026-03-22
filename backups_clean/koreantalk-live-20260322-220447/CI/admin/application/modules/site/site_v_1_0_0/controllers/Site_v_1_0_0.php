<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2023-01-01
| Memo :사이트 관리
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

class Site_v_1_0_0 extends MY_Controller{

	/* 생성자 영역 */
	function __construct(){
		parent::__construct();
		$this->load->model(mapping('site').'/model_site');
	}

	/* Index */
	public function index(){
		$this->site_list();
	}

	//사이트 리스트
	public function site_list(){
		$this->_view(mapping('site').'/view_site_list');
	}

	//사이트 리스트 가져오기
	public function site_list_get(){
		$site_name = $this->_input_check("site_name",array());
		$s_date = $this->_input_check("s_date",array());
		$e_date = $this->_input_check("e_date",array());
		$history_data = $this->_input_check("history_data",array());
    $page_num = $this->_input_check("page_num",array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['site_name'] = $site_name;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['page_no'] = ($page_num-1)*$page_size;
		$data['page_size'] = $page_size;

		$result_list = $this->model_site->site_list($data); //사이트 리스트site_name
		$result_list_count = $this->model_site->site_list_count($data); //공지사항 리스트 총 카운트

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->page_num = $page_num;
		$response->history_data = $history_data;

		$this->_list_view(mapping('site').'/view_site_list_get', $response);
	}

	//사이트 등록 폼
	public function site_reg(){
		$this->_view(mapping('site').'/view_site_reg',array());
	}

	//사이트 등록
	public function site_reg_in(){
		$site_code = $this->_input_check("site_code",array("empty_msg"=>"사이트 코드을 입력해주세요.","focus_id"=>"site_code"));
		$site_name = $this->_input_check("site_name",array("empty_msg"=>"사이트 명을 입력해주세요.","focus_id"=>"site_name"));

		$data['site_name'] = $site_name;
		$data['site_code'] = $site_code;

		$result = $this->model_site->site_reg_in($data); //사이트 등록

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

	//사이트 상세
	public function site_detail(){
		$site_idx = $this->_input_check("site_idx",array("empty_msg"=>"공지사항 키가 누락되었습니다."));
    $history_data = $this->_input_check("history_data",array());

		$data['site_idx'] = $site_idx;

		$result = $this->model_site->site_detail($data); //사이트 상세

		$response = new stdClass();

		$response->result = $result;
		$response->history_data = $history_data;

		$this->_view(mapping('site').'/view_site_detail',$response);
	}

	//사이트 수정
	public function site_mod_up(){
		$site_idx = $this->_input_check("site_idx",array());
		$site_code = $this->_input_check("site_code",array("empty_msg"=>"사이트 코드을 입력해주세요.","focus_id"=>"site_code"));
		$site_name = $this->_input_check("site_name",array("empty_msg"=>"사이트 명을 입력해주세요.","focus_id"=>"site_name"));
    $menu = $this->_input_check("menu",array());
    $lang_contents = $this->_input_check("lang_contents",array());

		$data['site_idx'] = $site_idx;
		$data['site_name'] = $site_name;
		$data['site_code'] = $site_code;
		$data['menu'] = $menu;
		$data['lang_contents'] = $lang_contents;

		$result = $this->model_site->site_mod_up($data); //사이트 수정

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


	//사이트 삭제
	public function site_del(){
		$site_idx = $this->_input_check("site_idx",array("empty_msg"=>"삭제 할 항목이 없습니다."));

		$data['site_idx'] = $site_idx;

		$result = $this->model_site->site_del($data); //사이트 삭제

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
