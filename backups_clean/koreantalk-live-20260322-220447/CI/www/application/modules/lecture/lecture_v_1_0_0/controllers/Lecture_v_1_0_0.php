<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2023-07-21
| Memo : 공지사항
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

class Lecture_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

    $this->load->model(mapping('lecture').'/model_lecture');
 
	}

	// 인덱스
	public function index() {
	$this->lecture_list();
	}

	// 공지사항 - 리스트
	public function lecture_list(){

		$response = new stdClass(); 

		$this->_view(mapping('lecture').'/view_lecture_list',$response);

	}

	// 공지사항 -  
	public function lecture_detail(){
		$lecture_idx = $this->_input_check("lecture_idx",array());
		$lecture_category_idx = $this->_input_check("lecture_category_idx",array());


		$data['lecture_idx'] = $lecture_idx;
		$data['lecture_category_idx'] = $lecture_category_idx;

		$rt = $this->model_lecture->lecture_detail($data); //공지사항 리스트 가져오기

		$response = new stdClass();

		$response->lecture_idx = $lecture_idx;
		$response->lecture_category_idx = $lecture_category_idx;
		$response->result = $rt['result'];
		$response->result_list = $rt['result_list'];
		$response->ebook_info = $rt['ebook_info'];
				
		$this->_view(mapping('lecture').'/view_lecture_detail',$response);

	}


	// 동영상 리스트
	public function lecture_movie_list_get(){
		$lecture_category_idx = $this->_input_check("lecture_category_idx",array());
		
		$data['lecture_category_idx'] = $lecture_category_idx;

		$result_list = $this->model_lecture->lecture_movie_list_get($data); // 리스트 가져오기

		$response = new stdClass();

		$response->result_list = $result_list;

		$this->_list_view(mapping('lecture').'/view_lecture_movie_list_get',$response);
	} 

	//동영상 시청
	public function member_watched_movie_reg_in(){
		$lecture_movie_idx = $this->_input_check("lecture_movie_idx",array());

		$data['lecture_movie_idx'] = $lecture_movie_idx;

		$result = $this->model_lecture->member_watched_movie_reg_in($data); // 1:1 질문 등록하기

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


	// 동영상 리스트
	public function youtube_play(){
		$youtube_id = $this->_input_check("youtube_id",array());

		$response = new stdClass();

		$response->youtube_id = $youtube_id;

		$this->_list_view(mapping('lecture').'/view_youtube_play',$response);
	} 



}// 클래스의 끝
?>
