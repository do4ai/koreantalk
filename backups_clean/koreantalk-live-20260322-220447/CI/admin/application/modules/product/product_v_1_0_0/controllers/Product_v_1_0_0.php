<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	-
| Create-Date : 2018-09-28
| Memo : 상품관리
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

class Product_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

		$this->load->model(mapping('product').'/model_product');
	}


  // 인덱스
	public function index(){
		$this->product_list();
	}


	// 상품관리 리스트
	public function product_list(){
		$category_b_list = $this->model_product->category_b_list();  

		$response = new stdClass();

		$response->category_b_list = $category_b_list;

		$this->_view(mapping('product').'/view_product_list',$response);
	}

	// 상품관리 리스트 가져오기 
	public function product_list_get(){
		$product_name = $this->_input_check("product_name",array());
		$display_yn = $this->_input_check("display_yn",array());	
		$product_b_category_idx = $this->_input_check("product_b_category_idx",array());
		$product_m_category_idx = $this->_input_check("product_m_category_idx",array());
		$product_s_category_idx = $this->_input_check("product_s_category_idx",array()); 
    $s_date = $this->_input_check('s_date',array());
		$e_date = $this->_input_check('e_date',array());
		$history_data = $this->_input_check("history_data",array()); 
		$page_num = $this->_input_check("page_num",array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['product_name'] = $product_name;
		$data['display_yn'] = $display_yn;	
		$data['s_date'] = $s_date;	
		$data['e_date'] = $e_date;			
		$data['product_b_category_idx'] = $product_b_category_idx;
		$data['product_m_category_idx'] = $product_m_category_idx;
		$data['product_s_category_idx'] = $product_s_category_idx;
		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;

		$result_list = $this->model_product->product_list($data); // 상품관리 리스트
    $result_list_count = $this->model_product->product_list_count($data); //  상품관리 리스트 총 카운트

		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;
		$response->history_data = $history_data;

    $this->_list_view(mapping('product').'/view_product_list_get', $response);
	}


	// 상품관리 리스트 엑셀 다운로드
	public function product_list_excel(){

		$product_name = $this->_input_check("product_name",array());
		$product_state = $this->_input_check("product_state",array());
		$product_display = $this->_input_check("product_display",array());
		$brand_idx = $this->_input_check("brand_idx",array());
		$reg_order = $this->_input_check("reg_order",array());
		$recommend_order = $this->_input_check("recommend_order",array());
		$product_b_category_idx = $this->_input_check("product_b_category_idx",array());
		$product_m_category_idx = $this->_input_check("product_m_category_idx",array());
		$product_s_category_idx = $this->_input_check("product_s_category_idx",array());

		$data['product_name'] = $product_name;
		$data['product_state'] = $product_state;
		$data['product_display'] = $product_display;
		$data['brand_idx'] = $brand_idx;
		$data['reg_order'] = $reg_order;
		$data['recommend_order'] = $recommend_order;
		$data['product_b_category_idx'] = $product_b_category_idx;
		$data['product_m_category_idx'] = $product_m_category_idx;
		$data['product_s_category_idx'] = $product_s_category_idx;

		$result_list = $this->model_product->product_list_excel($data); // 상품관리 리스트 엑셀 다운로드

		$response = new stdClass();

	  $response->result_list = $result_list;

    $this->_list_view(mapping('product').'/view_product_list_excel', $response);
	}

 

	// 상품 상세
	public function product_detail(){
		$product_idx = $this->_input_check("product_idx",array());
    $history_data = $this->_input_check("history_data",array()); 

		$data['product_idx'] = $product_idx;

		$result = $this->model_product->product_detail($data); // 상품 상세
    $lecture_movie_list = $this->model_common->lecture_movie_list();  	

		$response = new stdClass();

		$response->result = $result;
		$response->lecture_movie_list = $lecture_movie_list;
		$response->history_data = $history_data;

		$this->_view(mapping('product').'/view_product_detail',$response);
	}



	// 상품 등록 폼
	public function product_reg(){
		$category_list = $this->model_product->category_b_list(); 
		$lecture_movie_list = $this->model_common->lecture_movie_list();  	    

		$response = new stdClass();
		$response->product_code = "P".time();
		$response->category_list = $category_list;	
		$response->lecture_movie_list = $lecture_movie_list;	

		$this->_view(mapping('product').'/view_product_reg',$response);
	}

  // 상품 등록
	public function product_reg_in(){
    $response = new stdClass();
		
    $display_yn = $this->_input_check("display_yn",array());
		$product_b_category_idx = $this->_input_check("product_b_category_idx",array());
		$product_m_category_idx = $this->_input_check("product_m_category_idx",array());
		$product_s_category_idx = $this->_input_check("product_s_category_idx",array());
		$product_name = $this->_input_check("product_name",array("empty_msg"=>"상품명을 입력해주세요","focus_id"=>"product_name"));
		$author = $this->_input_check("author",array("empty_msg"=>"저자를 입력해주세요","focus_id"=>"author"));
		$product_price = $this->_input_check("product_price",array("empty_msg"=>"판매가를 입력해주세요","focus_id"=>"product_price"));
	  $smart_store_url = $this->_input_check("smart_store_url",array("empty_msg"=>"스마트 스토어 url를 입력해주세요","focus_id"=>"smart_store_url"));
	  $lecture_movie_idx = $this->_input_check("lecture_movie_idx",array("empty_msg"=>"연관 TOPICK 영상을 선택해 주세요","focus_id"=>"lecture_movie_idx"));
    $product_desc = $this->_input_check("product_desc",array("empty_msg"=>"간략설명을 입력해주세요","focus_id"=>"product_desc"));
    $product_img_path = $this->_input_check("product_img_path",array("empty_msg"=>"대표 이미지를 입력해주세요"));
		$product_detail_img_path = $this->_input_check("product_detail_img_path",array("empty_msg"=>"상세 이미지을 입력해주세요"));
    $product_contents = $this->_input_check("product_contents",array("empty_msg"=>"상품설명을 입력해주세요."));
		$product_contents= $this->input->post("product_contents");

		$data['display_yn'] = $display_yn;
		$data['product_b_category_idx'] = $product_b_category_idx;
		$data['product_m_category_idx'] = $product_m_category_idx;
		$data['product_s_category_idx'] = $product_s_category_idx;
		$data['product_img_path'] = $product_img_path;
		$data['product_detail_img_path'] = $product_detail_img_path;
		$data['product_name'] = $product_name;
		$data['author'] = $author;
		$data['product_price'] = $product_price;
		$data['product_contents'] = $product_contents;
		$data['product_desc'] = $product_desc;
		$data['smart_store_url'] = $smart_store_url;
		$data['lecture_movie_idx'] = $lecture_movie_idx;	

		$result = $this->model_product->product_reg_in($data); // 상품 등록 		

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

  // 상품 삭제
	public function product_del(){
		$product_idx = $this->_input_check("product_idx",array());

		$data['product_idx'] = $product_idx;

		$result = $this->model_product->product_del($data);

		$response = new stdClass;

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

  
	// 상품 수정 폼
	public function product_mod(){
    $product_idx = $this->_input_check("product_idx",array());
		$history_data = $this->_input_check("history_data",array());

		$data['product_idx'] = $product_idx;
		
		$category_list = $this->model_product->category_b_list();  
		$result = $this->model_product->product_detail($data); // 상품 상세
	
		$response = new stdClass();
		$response->category_list = $category_list;	
		$response->result = $result;
		$response->history_data = $history_data;

		$this->_view(mapping('product').'/view_product_mod',$response);
	}


	// 상품 수정 
	public function product_mod_up(){
  	$response = new stdClass;
		
    $product_idx = $this->_input_check("product_idx",array());
		$display_yn = $this->_input_check("display_yn",array());
		$product_b_category_idx = $this->_input_check("product_b_category_idx",array());
		$product_m_category_idx = $this->_input_check("product_m_category_idx",array());
		$product_s_category_idx = $this->_input_check("product_s_category_idx",array());

		$product_name = $this->_input_check("product_name",array("empty_msg"=>"상품명을 입력해주세요","focus_id"=>"product_name"));
    $author = $this->_input_check("author",array("empty_msg"=>"저자를 입력해주세요","focus_id"=>"author"));
		$product_price = $this->_input_check("product_price",array("empty_msg"=>"판매가를 입력해주세요","focus_id"=>"product_price"));

	  $smart_store_url = $this->_input_check("smart_store_url",array("empty_msg"=>"스마트 스토어 url를 입력해주세요","focus_id"=>"smart_store_url"));
	  $lecture_movie_idx = $this->_input_check("lecture_movie_idx",array("empty_msg"=>"연관 TOPICK 영상을 선택해 주세요","focus_id"=>"lecture_movie_idx"));
	  $product_desc = $this->_input_check("product_desc",array("empty_msg"=>"간략설명을 입력해주세요","focus_id"=>"product_desc"));
   	$product_img_path = $this->_input_check("product_img_path",array("empty_msg"=>"대표 이미지를 입력해주세요"));
		$product_detail_img_path = $this->_input_check("product_detail_img_path",array("empty_msg"=>"상세 이미지을 입력해주세요"));
    $product_contents = $this->_input_check("product_contents",array("empty_msg"=>"상품설명을 입력해주세요."));
		$product_contents= $this->input->post("product_contents");

		$data['product_idx'] = $product_idx;
		$data['display_yn'] = $display_yn;
		$data['product_b_category_idx'] = $product_b_category_idx;
		$data['product_m_category_idx'] = $product_m_category_idx;
		$data['product_s_category_idx'] = $product_s_category_idx;		
		$data['product_img_path'] = $product_img_path;
		$data['product_detail_img_path'] = $product_detail_img_path;
		$data['product_name'] = $product_name;
		$data['author'] = $author;
		$data['product_price'] = $product_price;
		$data['product_contents'] = $product_contents;
		$data['product_desc'] = $product_desc;
		$data['smart_store_url'] = $smart_store_url;
		$data['lecture_movie_idx'] = $lecture_movie_idx;


		$result = $this->model_product->product_mod_up($data);	// 상품 수정 
		
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

 

  // 카테고리 리스트
	public function category_list(){
		$category_idx = $this->_input_check("category_idx",array());

    $temp = explode("^",$category_idx);

		$data['category_idx'] = $temp[0];

		$category_list = $this->model_product->category_list($data); // 카테고리 리스트 

		echo json_encode($category_list);
	}

	


}
