<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 회원 관리
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

class Member_v_1_0_0 extends MY_Controller{

	function __construct(){
		parent::__construct();

		$this->load->model(mapping('member').'/model_member');
	}

 	// 인덱스
	public function index(){
		$this->member_list();
	}

	// 회원 리스트
	public function member_list(){
		$this->_view(mapping('member').'/view_member_list');
	}

	// 회원 리스트 가져오기
	public function member_list_get(){
		$member_id = $this->_input_check('member_id',array());
		$member_name = $this->_input_check('member_name',array());
		$member_phone = $this->_input_check('member_phone',array());
		$site_name = $this->_input_check('site_name',array());
		$member_nickname = $this->_input_check('member_nickname',array());
		$s_date = $this->_input_check('s_date',array());
		$e_date = $this->_input_check('e_date',array());
		$del_yn = $this->_input_check('del_yn',array());
		$history_data = $this->_input_check('history_data',array());
		$page_num = $this->_input_check('page_num',array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['member_id'] = $member_id;
		$data['member_name'] = $member_name;
		$data['member_phone'] = $member_phone;
		$data['site_name'] = $site_name;
		$data['member_nickname'] = $member_nickname;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['del_yn'] = $del_yn;
		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;

		$result_list = $this->model_member->member_list($data); // 회원 리스트
		$result_list_count = $this->model_member->member_list_count($data); // 회원 리스트 카운트

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->history_data = $history_data;

		$this->_list_view(mapping('member').'/view_member_list_get',$response);
	}

	// 회원 리스트 엑셀
	public function member_list_excel(){
		$member_id = $this->_input_check("member_id",array());
		$member_name = $this->_input_check("member_name",array());
		$member_phone = $this->_input_check("member_phone",array());
		$member_nickname = $this->_input_check('member_nickname',array());
		$s_date = $this->_input_check("s_date",array());
		$e_date = $this->_input_check("e_date",array());
		$del_yn = $this->_input_check("del_yn",array());

		$data['member_id'] = $member_id;
		$data['member_name'] = $member_name;
		$data['member_phone'] = $member_phone;
		$data['member_nickname'] = $member_nickname;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['del_yn'] = $del_yn;

		$result_list = $this->model_member->member_list_excel($data);	// 회원 리스트 엑셀
		$no = COUNT($result_list);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->no = $no;

		$this->_list_view(mapping('member').'/view_member_list_excel',$response);
	}

	// 회원 상세보기
	public function member_detail(){
		$member_idx = $this->_input_check("member_idx",array());
		$history_data = $this->_input_check("history_data",array());

		$data['member_idx'] = $member_idx;

		$result = $this->model_member->member_detail($data); // 회원 상세보기

		if($member_idx == '' || empty($result) ){
			redirect("/".mapping('member'));
		}else{
			$response = new stdClass();

			$response->result = $result;
			$response->history_data = $history_data;

			$this->_view(mapping('member').'/view_member_detail',$response);
		}
	}


  // 회원 정보 수정
	public function member_mod_up() {
		$member_idx = $this->_input_check('member_idx',array());
		// $member_name = $this->_input_check('member_name',array());
		// $member_phone = $this->_input_check('member_phone',array());
		// $corp_name = $this->_input_check('corp_name',array());
		// $corp_reg_no = $this->_input_check('corp_reg_no',array());
		$del_yn = $this->_input_check('del_yn',array());

		$data['member_idx'] = $member_idx;
		// $data['member_name'] = $member_name;
		// $data['member_phone'] = $member_phone;
		// $data['corp_name'] = $corp_name;
		// $data['corp_reg_no'] = $corp_reg_no;
		$data['del_yn'] = $del_yn;

		$result = $this->model_member->member_mod_up($data); // 회원 정보 수정

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

}	// 클래스의 끝
