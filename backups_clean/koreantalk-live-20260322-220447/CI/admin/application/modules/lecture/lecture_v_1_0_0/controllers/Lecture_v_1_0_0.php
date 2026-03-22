<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2023-07-21
| Memo : 관리
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

	/* 생성자 영역 */
	function __construct(){
		parent::__construct();

		$this->load->model(mapping('lecture').'/model_lecture');
	}

	/* Index */
	public function index(){
		$this->lecture_list();
	}

	// 리스트
	public function lecture_list(){
  	$site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;

		$this->_view(mapping('lecture').'/view_lecture_list',$response);
	}

	// 리스트 가져오기 
	public function lecture_list_get(){
		$site_code = $this->_input_check("site_code",array());
		$lecture_name = $this->_input_check("lecture_name",array());
		$s_date = $this->_input_check("s_date",array());
		$e_date = $this->_input_check("e_date",array());
		$history_data = $this->_input_check("history_data",array());
    $page_num = $this->_input_check("page_num",array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['site_code'] = $site_code;
		$data['lecture_name'] = $lecture_name;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['page_no'] = ($page_num-1)*$page_size;
		$data['page_size'] = $page_size;

		$result_list = $this->model_lecture->lecture_list($data); // 리스트
		$result_list_count = $this->model_lecture->lecture_list_count($data); // 리스트 총 카운트

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->page_num = $page_num;
		$response->history_data = $history_data;

		$this->_list_view(mapping('lecture').'/view_lecture_list_get', $response);
	}

	// 등록 폼
	public function lecture_reg(){
    $site_list = $this->model_common->site_list();
    $electron_book_list = $this->model_common->electron_book_list();

		$response = new stdClass();

		$response->site_list = $site_list;
		$response->electron_book_list = $electron_book_list;
  
		$this->_view(mapping('lecture').'/view_lecture_reg', $response);
	}

	// 등록
	public function lecture_reg_in(){
		$lecture_name = $this->_input_check("lecture_name",array("empty_msg"=>"동영상이름을 입력해주세요.","focus_id"=>"lecture_name"));
		$contents = $this->_input_check("contents",array("empty_msg"=>"내용을 입력해주세요.","focus_id"=>"contents"));
		$electron_book_idx = $this->_input_check("electron_book_idx",array("empty_msg"=>"연관 전자책을 선택해 주세요.","focus_id"=>"electron_book_idx"));
		$site_code = $this->_input_check("site_code",array());
    $display_yn = $this->_input_check("display_yn",array());
    $img_path = $this->_input_check("img",array());

		$data['lecture_name'] = $lecture_name;
		$data['contents'] = $contents;
		$data['site_code'] = $site_code;
    $data['display_yn'] = $display_yn;
    $data['img_path'] = $img_path;
    $data['electron_book_idx'] = $electron_book_idx;

		$result = $this->model_lecture->lecture_reg_in($data); // 등록

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}

	// 상세
	public function lecture_detail(){
		$lecture_idx = $this->_input_check("lecture_idx",array("empty_msg"=>"공지사항 키가 누락되었습니다."));
		$history_data = $this->_input_check("history_data",array());

		$data['lecture_idx'] = $lecture_idx;

		$result = $this->model_lecture->lecture_detail($data); // 상세
		$site_list = $this->model_common->site_list();
    $electron_book_list = $this->model_common->electron_book_list();

		$response = new stdClass();
		$response->site_list = $site_list;
		$response->electron_book_list = $electron_book_list;
		$response->result = $result;
		$response->history_data = $history_data;

		$this->_view(mapping('lecture').'/view_lecture_detail',$response);
	}

	// 수정
	public function lecture_mod_up(){
		$lecture_idx = $this->_input_check("lecture_idx",array("empty_msg"=>"키가 누락되었습니다."));
		$lecture_name = $this->_input_check("lecture_name",array("empty_msg"=>"동영상이름을 입력해주세요.","focus_id"=>"lecture_name"));
		$contents = $this->_input_check("contents",array("empty_msg"=>"내용을 입력해주세요.","focus_id"=>"contents"));
    $electron_book_idx = $this->_input_check("electron_book_idx",array("empty_msg"=>"연관 전자책을 선택해 주세요.","focus_id"=>"electron_book_idx"));
		$site_code = $this->_input_check("site_code",array());
    $display_yn = $this->_input_check("display_yn",array());
    $img_path = $this->_input_check("img",array());

		$data['lecture_idx'] = $lecture_idx;
		$data['lecture_name'] = $lecture_name;
		$data['contents'] = $contents;
		$data['site_code'] = $site_code;
    $data['display_yn'] = $display_yn;
    $data['img_path'] = $img_path;
    $data['electron_book_idx'] = $electron_book_idx;

		$result = $this->model_lecture->lecture_mod_up($data); // 수정

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}

	// 상태 변경
	public function lecture_state_mod_up(){
		$lecture_idx = $this->_input_check("lecture_idx",array("empty_msg"=>"공지사항 키가 누락되었습니다."));
		$lecture_state = $this->_input_check("lecture_state",array("empty_msg"=>"공지사항 상태 코드가 누락되었습니다."));

		$data['lecture_idx']  = $lecture_idx;
		$data['lecture_state'] = $lecture_state;

		$result = $this->model_lecture->lecture_state_mod_up($data); // 상태 변경

		$response = new stdClass();

    if($result == 0){
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
	public function lecture_del(){
		$lecture_idx = $this->_input_check("lecture_idx",array("empty_msg"=>"삭제 할 항목이 없습니다."));

		$data['lecture_idx'] = $lecture_idx;

		$result = $this->model_lecture->lecture_del($data); // 삭제

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}


  // 영상 ::리스트 가져오기 
	public function lecture_movie_list_get(){
		$lecture_idx = $this->_input_check("lecture_idx",array());

		$data['lecture_idx'] = $lecture_idx;

		$result = $this->model_lecture->lecture_movie_list($data); // 리스트	

		$response = new stdClass();

		$response->result = $result;

		$this->_list_view(mapping('lecture').'/view_lecture_movie_list_get', $response);
	}

  // 영상::카테고리 등록 
	public function lecture_category_reg_in(){
		$lecture_idx = $this->_input_check("lecture_idx",array("empty_msg"=>" 항목이 없습니다."));
		$category_name = $this->_input_check("category_name",array("empty_msg"=>"카테고리를  입력해 주세요.","focus_id"=>"category_name"));

		$data['lecture_idx'] = $lecture_idx;
		$data['category_name'] = $category_name;

		$result = $this->model_lecture->lecture_category_reg_in($data); // 영상::카테고리 등록 

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}

  // 영상::카테고리 삭제 
	public function lecture_category_del(){
		$lecture_category_idx = $this->_input_check("lecture_category_idx",array("empty_msg"=>"삭제 할 항목이 없습니다."));

		$data['lecture_category_idx'] = $lecture_category_idx;

		$result = $this->model_lecture->lecture_category_del($data); // 영상::카테고리 등록 

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}


  // 영상::항목 등록 
	public function lecture_movie_reg_in(){
  	$response = new stdClass();

		$lecture_idx = $this->_input_check("lecture_idx",array("empty_msg"=>"항목이 없습니다."));
		$lecture_category_idx = $this->_input_check("lecture_category_idx",array("empty_msg"=>"항목이 없습니다."));
		$movie_name = $this->_input_check("movie_name",array("empty_msg"=>"목차명을  입력해 주세요.","focus_id"=>"movie_name"));
		$movie_time = $this->_input_check("movie_time",array("empty_msg"=>"영상시간을  입력해 주세요.","focus_id"=>"movie_time"));
		$movie_url = $this->_input_check("movie_url",array("empty_msg"=>"영상url을  입력해 주세요.","focus_id"=>"movie_url"));

		$data['lecture_idx'] = $lecture_idx;
		$data['lecture_category_idx'] = $lecture_category_idx;
		$data['movie_name'] = $movie_name;
		$data['movie_time'] = $movie_time;
		$data['movie_url'] = $movie_url;	
    $data['youtube_id'] = $this->global_function->get_youtube_id($movie_url);

    if($data['youtube_id'] == ""){
      $response->code = "-1";
      $response->code_msg = "올바른 youtube 영상url을  입력해 주세요"; 
      $response->focus_id = "movie_url"; 
      echo json_encode($response);
      exit;
    }  

		$result = $this->model_lecture->lecture_movie_reg_in($data); // 영상::카테고리 등록 

	

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}


  // 영상::항목 수정 
	public function lecture_movie_mod_up(){
  	$response = new stdClass();

		$lecture_movie_idx = $this->_input_check("lecture_movie_idx",array("empty_msg"=>"항목이 없습니다."));
		$movie_name = $this->_input_check("movie_name",array("empty_msg"=>"목차명을  입력해 주세요.","focus_id"=>"_movie_name"));
		$movie_time = $this->_input_check("movie_time",array("empty_msg"=>"영상시간을  입력해 주세요.","focus_id"=>"_movie_time"));
		$movie_url = $this->_input_check("movie_url",array("empty_msg"=>"영상url을  입력해 주세요.","focus_id"=>"_movie_url"));

		$data['lecture_movie_idx'] = $lecture_movie_idx;
		$data['movie_name'] = $movie_name;
		$data['movie_time'] = $movie_time;
		$data['movie_url'] = $movie_url;	
    $data['youtube_id'] = $this->global_function->get_youtube_id($movie_url);

    if($data['youtube_id'] == ""){
      $response->code = "-1";
      $response->code_msg = "올바른 youtube 영상url을  입력해 주세요"; 
      $response->focus_id = "movie_url"; 
      echo json_encode($response);
      exit;
    }  

		$result = $this->model_lecture->lecture_movie_mod_up($data); // 영상::카테고리 등록 	

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}


 	// 순서 정렬
	public function lecture_movie_order_no_mod_up(){
		$lecture_movie_idx = $this->_input_check("lecture_movie_idx",array("empty_msg"=>"항목이 없습니다."));

		$data['lecture_movie_idx'] = $lecture_movie_idx;

		$result = $this->model_lecture->lecture_movie_order_no_mod_up($data); // 삭제

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}

  // 영상::추천 
	public function lecture_movie_main_view_yn_mod_up(){
		$lecture_movie_idx = $this->_input_check("lecture_movie_idx",array("empty_msg"=>"항목이 없습니다."));

		$data['lecture_movie_idx'] = $lecture_movie_idx;

		$result = $this->model_lecture->lecture_movie_main_view_yn_mod_up($data); // 영상::카테고리 등록 

		$response = new stdClass();

    if($result == 0){
      $response->code = "-2";
      $response->code_msg = error_code_msg('-2'); //실패 하였습니다.
    }else{
      $response->code = "1000";
      $response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
    }

    echo json_encode($response);
    exit;
	}


  // 영상::카테고리 삭제 
	public function lecture_movie_del(){
		$lecture_movie_idx = $this->_input_check("lecture_movie_idx",array("empty_msg"=>"삭제 할 항목이 없습니다."));

		$data['lecture_movie_idx'] = $lecture_movie_idx;

		$result = $this->model_lecture->lecture_movie_del($data); // 영상::카테고리 등록 

		$response = new stdClass();

    if($result == 0){
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
