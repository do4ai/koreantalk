<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 카테고리 관리
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

class Category_management_v_1_0_0 extends MY_Controller{

	function __construct(){

		parent::__construct();

		$this->load->model(mapping('category_management').'/model_category_management');

		define("CATE_TYPE",	"1");//카테고리 TYPE

	}

//인덱스
	public function index(){

		$this->category_management();
	}

// 카테고리 리스트 관리
	public function category_management(){
		$first_cate_list = [];
		$second_cate_list = [];
		$third_cate_list = [];

		// 1뎁스 카테고리 조회
		$data["category_depth"] = "1";
		$data["type"] = CATE_TYPE;
		$data["parent_category_management_idx"] = "";
		$first_cate_list = $this->model_category_management->category_management_list($data); // 카테고리 리스트 

		// 2뎁스 카테고리 조회, 1뎁스 카테고리가 하나 이상 있을 때만 조회
		if($first_cate_list){
			$data["category_depth"] = "2";
			$data["parent_category_management_idx"] = $first_cate_list[0]->category_management_idx;
			$second_cate_list = $this->model_category_management->category_management_list($data); // 카테고리 리스트 

			if($second_cate_list){
				$data["category_depth"] = "3";
				$data["parent_category_management_idx"] = $second_cate_list[0]->category_management_idx;
				$third_cate_list = $this->model_category_management->category_management_list($data); // 카테고리 리스트 
			}
		}

		$response = new stdClass();

		$response->first_cate_list = $first_cate_list;
		$response->second_cate_list = $second_cate_list;
		$response->third_cate_list = $third_cate_list;

		$this->_view(mapping('category_management').'/view_category_management',$response);
	}

  // 카테고리 리스트
	public function category_management_list(){
		$parent_category_management_idx = $this->_input_check('parent_category_management_idx',array());
		$category_depth = $this->_input_check('category_depth',array());

		$data["parent_category_management_idx"] = $parent_category_management_idx;
		$data["category_depth"] = $category_depth;
		$data["type"] = CATE_TYPE;

		$category_management_list = $this->model_category_management->category_management_list($data); // 카테고리 리스트

		echo json_encode(array('category_management_list'=>$category_management_list));
	}

  // 카테고리 등록
	public function category_management_reg_in(){
		$parent_category_management_idx = $this->_input_check('parent_category_management_idx',array());
		$category_depth = $this->_input_check('category_depth',array());
		$category_name = $this->_input_check('category_name',array());

		$data["parent_category_management_idx"] = $parent_category_management_idx;
		$data["category_depth"] = $category_depth;
		$data["category_name"] = $category_name;
		$data["type"] = CATE_TYPE;

		$result = $this->model_category_management->category_management_reg_in($data); // 카테고리 등록

		if($result == 0) {
			echo json_encode(array('code' => 0, 'msg' => '해당 카테고리가 이미 있습니다.'));
			exit;
		} else {
			echo json_encode(array('code' => 1, 'category_management_idx' => $result));
			exit;
		}
	}

  // 카테고리 삭제
	public function category_management_del(){
		$category_management_idx = $this->_input_check('category_management_idx',array());

		$data["category_management_idx"] = $category_management_idx;

		$result = $this->model_category_management->category_management_del($data); // 카테고리 삭제

		if($result == 0) {
			echo json_encode(array('code' => 0, 'msg' => '삭제에 실패하였습니다. 관리자에게 문의하세요'));
			exit;
		} else {
			echo json_encode(array('code' => 1));
			exit;
		}
	}

  // 카테고리 수정
	public function category_management_mod_up(){
		$category_management_idx = $this->_input_check('category_management_idx',array());
		$category_name = $this->_input_check('category_name',array());

		$data["category_management_idx"] = $category_management_idx;
		$data["category_name"] = $category_name;

		$result = $this->model_category_management->category_management_mod_up($data); // 카테고리 수정

		if($result == 0) {
			echo json_encode(array('code' => 0, 'msg' => '수정에 실패하였습니다. 관리자에게 문의하세요'));
			exit;
		} else {
			echo json_encode(array('code' => 1));
			exit;
		}
	}

  // 카테고리  활성 / 활성화
	public function category_state_up(){
		$category_management_idx = $this->_input_check('category_management_idx',array());

		$data["category_management_idx"] = $category_management_idx;

		$result = $this->model_category_management->category_state_up($data); // 카테고리  활성 / 활성화

		if($result == 0) {
			echo json_encode(array('code' => 0, 'msg' => '수정에 실패하였습니다. 관리자에게 문의하세요'));
			exit;
		} else {
			echo json_encode(array('code' => 1));
			exit;
		}
	}

  // 카테고리 순서 변경
	public function category_order_set(){
		$first_cate_list_idx = $this->_input_check('first_cate_list_idx',array());
		$second_cate_list_idx = $this->_input_check('second_cate_list_idx',array());
		$third_cate_list_idx = $this->_input_check('third_cate_list_idx',array());
		$select_depth = $this->_input_check('select_depth',array());
		$parent_category_management_idx = $this->_input_check('parent_category_management_idx',array());

		$data["first_cate_list_idx"] = $first_cate_list_idx;
		$data["second_cate_list_idx"] = $second_cate_list_idx;
		$data["third_cate_list_idx"] = $third_cate_list_idx;
		$data["select_depth"] = $select_depth;
		$data["parent_category_management_idx"] = $parent_category_management_idx;

		$result = $this->model_category_management->category_order_set($data); // 카테고리 순서 변경

		if($result == 0) {
			echo json_encode(array('code' => 0, 'msg' => '수정에 실패하였습니다. 관리자에게 문의하세요'));
			exit;
		} else {
			echo json_encode(array('code' => 1));
			exit;
		}
	}
}
