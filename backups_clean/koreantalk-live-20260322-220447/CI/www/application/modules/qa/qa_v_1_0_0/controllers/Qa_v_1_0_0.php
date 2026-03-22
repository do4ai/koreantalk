<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2023-07-21
| Memo : qa
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

class Qa_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

    $this->load->model(mapping('qa').'/model_qa');

    if(empty($this->member_idx)){
        redirect("/". $this->current_lang."/".mapping('login')."?return_url=/".mapping('qa'));
        exit;
    }
 
	}

	// 인덱스
  public function index() {
    $this->qa_list();
  }

  // qa 리스트
  public function qa_list(){
		$this->_view(mapping('qa').'/view_qa_list');
 
  }

	// qa 리스트
  public function qa_list_get(){
		$page_num = $this->_input_check('page_num ',array("ternary"=>'1'));
		$page_size = PAGESIZE;
	
		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;

		$result_list = $this->model_qa->qa_list($data); // 리스트 가져오기
		$result_list_count = $this->model_qa->qa_list_count($data); //  리스트 카운트 가져오기
		$no = $result_list_count-($page_size*($page_num-1));
    $paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;

	  $this->_list_view(mapping('qa').'/view_qa_list_get',$response);
  }  
 
	// qa 리스트
  public function qa_reg(){
		$this->_view(mapping('qa').'/view_qa_reg');
 
  }
 
	// qa 리스트
  public function qa_detail(){
 		$qa_idx = $this->_input_check("qa_idx",array());

    $data['qa_idx'] = $qa_idx;

    $result = $this->model_qa->qa_detail($data);
    if(empty($result)){
      redirect("/". $current_nation."/".mapping('qa'));
    }else{
      $response = new stdClass();

      $response->result = $result;

	  	$this->_view(mapping('qa').'/view_qa_detail', $response);
    }
 
  }


  	// 1:1 질문 등록하기
  public function qa_reg_in(){
		$qa_title = $this->_input_check("qa_title",array("empty_msg"=>lang('lang_10400','제목을 입력해 주세요.'),"focus_id"=>"qa_title"));
		$qa_contents = $this->_input_check("qa_contents",array("empty_msg"=>lang('lang_10401','내용을 입력해 주세요.'),"focus_id"=>"qa_contents"));
 
    $data['qa_title'] = $qa_title;
		$data['qa_contents'] = $qa_contents;

		$result = $this->model_qa->qa_reg_in($data); // 1:1 질문 등록하기

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

  // 삭제
  public function qa_del(){
		$qa_idx = $this->_input_check("qa_idx",array());

		$data['qa_idx'] = $qa_idx;

		$result = $this->model_qa->qa_del($data);

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
 

}// 클래스의 끝
?>
