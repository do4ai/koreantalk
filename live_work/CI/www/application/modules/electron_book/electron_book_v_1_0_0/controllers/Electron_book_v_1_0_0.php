<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2023-07-21
| Memo : 
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

class Electron_book_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

    $this->load->model(mapping('electron_book').'/model_electron_book');
 
	}

	private function study_book_map(){
		return array(
			10 => array(
				'label' => 'TOPIK 2 Grammar/Vocabulary',
				'file_path' => '/home/koreantalk/book-pdf/20260323/topik2-grammar-vocabulary.pdf',
				'viewer_mode' => 'grammar',
				'viewer_template' => 'viewer-grammar.php',
			),
		);
	}

	private function study_detail_url($electron_book_idx){
		$lang = !empty($this->current_lang) ? $this->current_lang : 'us';
		return "/".$lang."/".mapping('electron_book')."/electron_book_detail?electron_book_idx=".$electron_book_idx;
	}

	// 인덱스
	public function index() {
	$this->electron_book_list();
	}

	//  - 리스트
	public function electron_book_list(){

		$response = new stdClass(); 

		$this->_view(mapping('electron_book').'/view_electron_book_list',$response);

	}


	public function electron_book_list_get(){

		$page_num = $this->_input_check('page_num ',array("ternary"=>'1'));
		$s_text = $this->_input_check("s_text",array());
		$page_size = PAGESIZE;

		$data['page_size'] = $page_size;
		$data['s_text'] = $s_text;
		$data['page_no'] = ($page_num-1)*$page_size;

		$result_list = $this->model_electron_book->electron_book_list_get($data); // 리스트 가져오기
		$result_list_count = $this->model_electron_book->electron_book_list_count($data); //  리스트 카운트 가져오기
		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;

		$this->_list_view(mapping('electron_book').'/view_electron_book_list_get',$response);
	}

	//  -  
	public function electron_book_detail(){
		$electron_book_idx = $this->_input_check("electron_book_idx",array());

		$data['electron_book_idx'] = $electron_book_idx;

		$rt = $this->model_electron_book->electron_book_detail($data); // 리스트 가져오기

		$response = new stdClass();

		$response->result = $rt['result'];
		$response->movie_info = $rt['movie_info'];
		$response->study_enabled = !empty($rt['result']) && isset($this->study_book_map()[(int)$rt['result']->electron_book_idx]);
		
		$this->_view(mapping('electron_book').'/view_electron_book_detail',$response);

	}


	// 구매하기 홈-  
	public function product_payment(){
		$electron_book_idx = $this->_input_check("electron_book_idx",array());

		$data['electron_book_idx'] = $electron_book_idx;

		$rt = $this->model_electron_book->electron_book_detail($data); // 리스트 가져오기

		$response = new stdClass();

		$response->result = $rt['result'];
		$response->terms_list = $this->model_electron_book->terms_list();
	

		$this->_view(mapping('electron_book').'/view_product_payment',$response);
	}

	// 학습하기 뷰어
	public function study_viewer(){
		$electron_book_idx = $this->_input_check("electron_book_idx",array());

		$data['electron_book_idx'] = $electron_book_idx;
		$rt = $this->model_electron_book->electron_book_detail($data);
		$result = $rt['result'];

		if(empty($result)){
			show_404();
			return;
		}

		$book_map = $this->study_book_map();
		$mapped_book = isset($book_map[$result->electron_book_idx]) ? $book_map[$result->electron_book_idx] : array();

		if(empty($mapped_book)){
			show_404();
			return;
		}

		if(empty($this->member_idx)){
			$lang = !empty($this->current_lang) ? $this->current_lang : 'us';
			redirect("/".$lang."/".mapping('login')."?return_url=/".$lang."/".mapping('electron_book')."/study_viewer?electron_book_idx=".$electron_book_idx);
			exit;
		}

		if((int)$result->my_buy_cnt < 1){
			$fallback_url = $this->study_detail_url($electron_book_idx);
			$alert_message = "구매한 회원만 학습하기를 이용할 수 있습니다.";
			$alert_message = str_replace(array("\\", "'"), array("\\\\", "\\'"), $alert_message);
			$fallback_url = str_replace(array("\\", "'"), array("\\\\", "\\'"), $fallback_url);

			echo "<script>
				alert('".$alert_message."');
				if (document.referrer && document.referrer.indexOf(window.location.origin) === 0 && document.referrer !== window.location.href) {
					window.location.href = document.referrer;
				} else {
					window.location.href = '".$fallback_url."';
				}
			</script>";
			exit;
		}

		$response = new stdClass();

		$pdf_url = "/".$this->current_lang."/".mapping('electron_book')."/study_pdf?electron_book_idx=".$result->electron_book_idx;
		$response->asset_base_url = "/book-viewer";
		$response->viewer_template = !empty($mapped_book['viewer_template']) ? $mapped_book['viewer_template'] : 'viewer-speaking.php';
		$response->viewer_bootstrap = array(
			'title' => $result->product_name,
			'mode' => !empty($mapped_book['viewer_mode']) ? $mapped_book['viewer_mode'] : 'speaking',
			'electron_book_idx' => $result->electron_book_idx,
			'product_auth_code' => '',
			'pdf_url' => $pdf_url,
			'book_map_label' => !empty($mapped_book['label']) ? $mapped_book['label'] : '',
		);

		$this->load->view(mapping('electron_book').'/view_study_viewer', $response);
	}

	public function study_pdf(){
		$electron_book_idx = $this->_input_check("electron_book_idx",array());

		$data['electron_book_idx'] = $electron_book_idx;
		$rt = $this->model_electron_book->electron_book_detail($data);
		$result = $rt['result'];

		if(empty($result)){
			show_404();
			return;
		}

		$book_map = $this->study_book_map();
		$mapped_book = isset($book_map[$result->electron_book_idx]) ? $book_map[$result->electron_book_idx] : null;

		if(empty($mapped_book) || empty($mapped_book['file_path']) || !file_exists($mapped_book['file_path'])){
			show_404();
			return;
		}

		if(empty($this->member_idx)){
			header("HTTP/1.1 403 Forbidden");
			exit;
		}

		if((int)$result->my_buy_cnt < 1){
			header("HTTP/1.1 403 Forbidden");
			exit;
		}

		header('Content-Type: application/pdf');
		header('Content-Length: '.filesize($mapped_book['file_path']));
		header('Content-Disposition: inline; filename="'.basename($mapped_book['file_path']).'"');
		readfile($mapped_book['file_path']);
		exit;
	}

	public function terms_detail(){

		$type = $this->_input_check("type",array());
	
		$data['type'] = $type;

		$result=$this->model_electron_book->terms_detail($data);//약관 상세 보기

		$response = new stdClass();

		if(empty($result)){
			$response->code = "0";
			$response->code_msg = lang('lang_10334','정보를 불러오지 못했습니다. 잠시 후 다시 시도해주세요.');

		}else{
			$response->code = "1000";
			$response->code_msg = lang('lang_10335','성공');
			$response->title = $result->title;
			$response->contents = $result->contents;
		}

		echo json_encode($response);
		exit;
	  }


	//주문번호 세팅
	function set_reserve_no($str,$length) {
		$characters  = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$rendom_str = "";
		$loopNum = $length;
		while ($loopNum--) {
			$rendom_str .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		return $str.$rendom_str;
	}
 

	// 구매하기
	public function order_reg_in(){
		$electron_book_idx = $this->_input_check("electron_book_idx",array());

		$data['order_number'] = $this->set_reserve_no("S_".date('ymd'),9);
		$data['product_auth_code'] = $this->set_reserve_no("P_".date('ymd'),100);
		$data['member_idx'] = $this->member_idx;
		$data['electron_book_idx'] = $electron_book_idx;

		$result = $this->model_electron_book->order_reg_in($data); 

		$response = new stdClass();

		if($result == "0") {
			$response->code = "-2";
			$response->code_msg 	=  error_code_msg('-2'); //실패 하였습니다.
		} else {
			$response->code ="1000";
			$response->order_number =$data['order_number'];
			$response->code_msg 	=  error_code_msg('1000');   
		}

		echo json_encode($response);
		exit;
	}

	// 구매후   
	public function product_payment_complete(){
		$order_number = $this->_input_check("order_number",array());
        $data['order_number'] = $order_number;
        
		$result = $this->model_electron_book->order_detail($data); 

		$response = new stdClass();

		$response->result = $result;

		$this->_view(mapping('electron_book').'/view_product_payment_complete',$response);

	}


}// 클래스의 끝
?>
