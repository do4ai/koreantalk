<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 배너관리
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

class Banner_v_1_0_0 extends MY_Controller{

	/* 생성자 영역 */
	function __construct(){

		parent::__construct();

		$this->load->model(mapping('banner').'/model_banner');
	}

	//Index
	public function index(){
		$this->banner_list();
	}

	// 배너 리스트
	public function banner_list(){
    $category = $this->_input_check('category',array());
    $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;
		$response->category = $category;
		$this->_view(mapping('banner').'/view_banner_list',$response);
	}


	// 배너 리스트 가져오기 
	public function banner_list_get(){
		$title = $this->_input_check('title',array());
		$s_date = $this->_input_check('s_date',array());
		$e_date = $this->_input_check('e_date',array());
		$state = $this->_input_check('state',array());
		$site_code = $this->_input_check('site_code',array());
		$banner_type = $this->_input_check('banner_type',array());
		$category = $this->_input_check('category',array());
		$history_data = $this->_input_check('history_data',array());
		$page_num = $this->_input_check('page_num ',array("ternary"=>'1'));
		$page_size  = PAGESIZE;

		$data['title'] = $title;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['state'] = $state;
		$data['site_code'] = $site_code;
		$data['category'] = $category;
		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;

		$result_list = $this->model_banner->banner_list($data);// 배너 리스트 
    $result_list_count = $this->model_banner->banner_list_count($data);// 배너 리스트 총 카운트

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->banner_type = $banner_type;
		$response->history_data = $history_data;

    $this->_list_view(mapping('banner').'/view_banner_list_get',$response);
	}

	// 배너 등록 폼
	public function banner_reg(){
  	$banner_type = $this->_input_check('banner_type',array());
    $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->banner_type = $banner_type;
		$response->site_list = $site_list;

		$this->_view(mapping('banner').'/view_banner_reg',$response);
	}

	// 메인 배너 등록
	public function banner_reg_in(){
		$title = $this->_input_check('title',array("empty_msg"=>"배너명을 입력해주세요.","focus_id"=>"title"));
		$contents = $this->_input_check('contents',array());
		$link_url = $this->_input_check('link_url',array("regular_msg" => "url 형식으로 입력해 주세요.","type" => "url"));
		$img_path = $this->_input_check('img_path',array("empty_msg"=>"배너 이미지를 등록해 주세요."));
		$img_width = $this->_input_check('img_width',array());
		$img_height = $this->_input_check('img_height',array());
		$state = $this->_input_check('state',array());
		$banner_type = $this->_input_check('banner_type',array());
		$site_code = $this->_input_check('site_code',array());

		$data['title'] = $title;
		$data['contents'] = $contents;
		$data['link_url'] = $link_url;
		$data['img_path'] = $img_path;
		$data['img_width'] = $img_width;
		$data['img_height'] = $img_height;
		$data['state'] = $state;
		$data['banner_type'] = $banner_type;
		$data['site_code'] = $site_code;

		$result = $this->model_banner->banner_reg_in($data); // 메인 배너 등록
 		
    $response = new StdClass();

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

	// 배너 상세
	public function banner_detail(){
		$banner_idx = $this->_input_check('banner_idx',array());
		$history_data = $this->_input_check('history_data',array());

		$data['banner_idx'] = $banner_idx;

		$result = $this->model_banner->banner_detail($data); // 배너 상세
    $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;
		$response->result = $result;
		$response->history_data = $history_data;

		$this->_view(mapping('banner').'/view_banner_detail',$response);
	}

	// 배너 수정
	public function banner_mod_up(){
		$banner_idx = $this->_input_check('banner_idx ',array());
		$title = $this->_input_check('title',array("empty_msg"=>"배너 제목을 입력해주세요.","focus_id"=>"title"));
		$contents = $this->_input_check('contents ',array());
		$link_url = $this->_input_check('link_url ',array("regular_msg" => "url 형식으로 입력해 주세요.","type" => "url"));
		$img_path = $this->_input_check('img_path',array("empty_msg"=>"배너 이미지를 등록해주세요."));
		$img_width = $this->_input_check('img_width',array());
		$img_height = $this->_input_check('img_height',array());
		$state = $this->_input_check('state ',array());
		$site_code = $this->_input_check('site_code ',array());

		$response = new StdClass();

		$data['banner_idx'] = $banner_idx;
		$data['title'] = $title;
		$data['contents'] = $contents;
		$data['link_url'] = $link_url;
		$data['img_path'] = $img_path;
		$data['img_width'] = $img_width;
		$data['img_height'] = $img_height;
		$data['state'] = $state;
		$data['site_code'] = $site_code;

		$result = $this->model_banner->banner_mod_up($data); // 배너 수정

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

	// 배너 삭제
	public function banner_del(){
		$banner_idx = $this->_input_check('banner_idx ',array());

		$data['banner_idx'] = $banner_idx;

		$result = $this->model_banner->banner_del($data); // 배너 삭제

		$response = new StdClass();

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
