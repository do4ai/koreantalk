<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo :  정기결제내역
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

class My_order_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

   	$this->load->model(mapping('my_order').'/model_order');

    if(empty($this->member_idx)){
      redirect("/". $current_nation."/".mapping('login')."?return_url=/".mapping('my_order'));
      exit;
    }
 
 
	}

//인덱스
  public function index() {
    $this->my_order_list();
  }
 
//대시보드
  public function my_order_list(){ 
    $this->_view(mapping('my_order').'/view_my_order_list');
  }

	// 리스트 가져오기
  public function order_list_get(){
		$s_text = $this->_input_check("s_text",array());
		$page_num = $this->_input_check('page_num ',array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['s_text'] = $s_text;
		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;

		$result_list = $this->model_order->order_list($data); //리스트 가져오기
		$result_list_count = $this->model_order->order_list_count($data); //리스트 카운트 가져오기
		$no = $result_list_count-($page_size*($page_num-1));
        $paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;

	  $this->_list_view(mapping('my_order').'/view_my_order_list_get',$response);
  }

  // 다운로드
	public function file_download(){

		$product_auth_code = $this->_input_check('product_auth_code',array());
	
		$data['product_auth_code'] = $product_auth_code;
	
		$result = $this->model_order->order_detail($data);	
	
		if(!empty($result)){
			$this->model_order->file_download_up($data);	
	
			$this->load->helper('download');
	
			$db_file =$result->pdf_url;
			$name =$result->pdf_org_name;
	
			$temp_file = explode("media",$db_file);
			$filepath = '/home/koreantalk/media'.$temp_file[1];
	
			$data = file_get_contents($filepath); 
	
			force_download($name, $data);		
	
		}
	}

   

}// 클래스의 끝
?>
