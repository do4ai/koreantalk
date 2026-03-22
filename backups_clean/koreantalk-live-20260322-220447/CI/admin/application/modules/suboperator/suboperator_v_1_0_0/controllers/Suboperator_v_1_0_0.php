<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	김용덕
| Create-Date : 2016-03-18
| Memo : 관리자관리
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

class Suboperator_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

		//모델
		$this->load->model(mapping('suboperator').'/model_suboperator');

	}


//인덱스
	public function index(){
		$this->suboperator_list();
	}


//리스트
	public function suboperator_list(){
    $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;
		$this->_view(mapping('suboperator').'/view_suboperator_list',$response);
	}

//관리자 리스트
	public function suboperator_list_get(){
		$admin_name = $this->_input_check("admin_name",array());
		$admin_id = $this->_input_check("admin_id",array());
		$site_code = $this->_input_check("site_code",array());
		$admin_id = $this->_input_check("admin_id",array());
		$page_num = $this->_input_check("page_num",array("ternary"=>'1'));
		$page_size=PAGESIZE;

		$data['admin_name']=$admin_name;
		$data['admin_id']=$admin_id;
		$data['site_code']=$site_code;
		$data['page_size']=$page_size;
		$data['page_no']=($page_num-1)*$page_size;

		$result_list=$this->model_suboperator->suboperator_list($data);//관리자 리스트
		$result_list_count=$this->model_suboperator->suboperator_list_count($data);//관리자 리스트 카운트
		$no=$result_list_count-($page_size*($page_num-1));
		$paging=$this->global_function->paging($result_list_count,$page_size,$page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->no = $no;
		$response->paging = $paging;
		$response->result_list_count = $result_list_count;

		$this->_list_view(mapping('suboperator').'/view_suboperator_list_get',$response);
	}

//등록폼
	public function suboperator_reg(){
    $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;
		$this->_view(mapping('suboperator').'/view_suboperator_reg',$response);
	}

//등록저장
	public function suboperator_reg_in(){
		$admin_id	= $this->_input_check("admin_id",array("empty_msg"=>"id를 입력해주세요.","focus_id"=>"admin_id"));
		$admin_name	= $this->_input_check("admin_name",array("empty_msg"=>"관리자명을 입력해주세요.","focus_id"=>"admin_name"));
		$admin_phone = $this->_input_check("admin_phone",array("empty_msg"=>"전화번호를 입력해주세요.","focus_id"=>"admin_phone"));
		$admin_pw	= $this->_input_check("admin_pw",array("empty_msg"=>"비밀번호를 입력해주세요.","focus_id"=>"admin_pw"));
		$admin_right = $this->_input_check("admin_right",array());
		$site_code = $this->_input_check("site_code",array());

		$data['admin_id'] = $admin_id;
		$data['admin_name'] = $admin_name;
		$data['admin_phone'] = $admin_phone;
		$data['admin_pw'] = $admin_pw;
		$data['admin_right']=$admin_right;
		$data['site_code']=$site_code;

		$response = new stdClass();

		$id_check=$this->model_suboperator->id_check($data); //사용 가능 아이디 체크
		
		if(!empty($id_check)){
			$response->code = '-1';
			$response->code_msg 	= "이미 등록된 아이디 입니다.";
		
			echo json_encode($response);
			exit;
		}

		$result=$this->model_suboperator->suboperator_reg_in($data);//관리자 등록

		if($result == "0") {
			$response->code = 0;
			$response->code_msg 	= "등록 실패하였습니다. 다시 시도 해주시기 바랍니다.";
		} else{
			$response->code = 1;
			$response->code_msg 	= "등록 성공하였습니다.";
		}
		echo json_encode($response);
		exit;
	}


	//수정폼
	public function suboperator_mod(){
		$admin_idx = $this->_input_check("admin_idx",array());

		$data['admin_idx'] =$admin_idx;
		$result=$this->model_suboperator->suboperator_detail($data);//관리자 보기
    $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;
		$response->result = $result;

		$this->_view(mapping('suboperator').'/view_suboperator_mod',$response);
	}

	// 관리자 삭제
	public function suboperator_del(){
		$admin_idx = $this->_input_check("admin_idx",array());

		$data['admin_idx'] = $admin_idx;

		$result = $this->model_suboperator->suboperator_del($data); // 관리자 삭제

		$response = new stdClass();

		if($result == '1'){
			$response->code = '1';
			$response->code_msg = '삭제 되었습니다.';
		} else {
			$response->code = '0';
			$response->code_msg = '삭제 실패 했습니다.';
		}

		echo json_encode($response);
		exit;
	}

	//수정저장
	public function suboperator_mod_up(){
		$admin_idx = $this->_input_check("admin_idx",array());
		$admin_name	= $this->_input_check("admin_name",array("empty_msg"=>"성명을 입력해주세요.","focus_id"=>"admin_name"));
		$admin_phone = $this->_input_check("admin_phone",array("empty_msg"=>"전화번호를 입력해주세요.","focus_id"=>"admin_phone", "type"=>"tel1"));
		$admin_pw	= $this->_input_check("admin_pw",array());
		$admin_right = $this->_input_check("admin_right",array());
		$site_code = $this->_input_check("site_code",array());

		$data['admin_idx']=$admin_idx;
		$data['admin_name']=$admin_name;
		$data['admin_phone']=$admin_phone;
		$data['admin_pw']=$this->global_function->trimStr($admin_pw);
		$data['admin_right']=$admin_right;
		$data['site_code']=$site_code;

		$result=$this->model_suboperator->suboperator_mod_up($data);//관리자 등록

		$response = new stdClass();
		if($result == "0") {
			$response->code = 0;
			$response->code_msg 	= "수정 실패하였습니다. 다시 시도 해주시기 바랍니다.";
		} else{
			$response->code = 1;
			$response->code_msg 	= "수정 성공하였습니다.";
		}
		echo json_encode($response);
		exit;

	}

}
