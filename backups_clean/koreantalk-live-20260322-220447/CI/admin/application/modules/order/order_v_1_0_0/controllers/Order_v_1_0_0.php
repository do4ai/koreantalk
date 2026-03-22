<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2022-12-11
| Memo : 주문 관리
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

class Order_v_1_0_0 extends MY_Controller{

	// 생성자 영역
	function __construct(){
		parent::__construct();   

		$this->load->model(mapping('order').'/model_order');
	}

	// Index
	public function index(){
		$this->order_list();
	}

	// 주문 리스트
	public function order_list(){
      $site_list = $this->model_common->site_list();

		$response = new stdClass();

		$response->site_list = $site_list;
  		
		$this->_view(mapping('order').'/view_order_list', $response);
	}

	// 주문 리스트 가져오기
	public function order_list_get(){
		$site_code = $this->_input_check("site_code",array());		
		$order_id = $this->_input_check("order_id",array());
		$order_name = $this->_input_check("order_name",array());
		$order_number = $this->_input_check("order_number",array());
		$product_name = $this->_input_check("product_name",array());
		$s_date = $this->_input_check("s_date",array());
		$e_date = $this->_input_check("e_date",array());
		$order_state = $this->_input_check("order_state",array());		
		$history_data = $this->_input_check("history_data",array());
		$page_num = $this->_input_check('page_num ',array("ternary"=>'1'));
		$page_size = PAGESIZE;

		$data['site_code'] = $site_code;
		$data['order_id'] = $order_id;
		$data['order_name'] = $order_name;
		$data['order_number'] = $order_number;
		$data['product_name'] = $product_name;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['order_state'] = $order_state;		
		$data['page_no'] = ($page_num-1)*$page_size;
		$data['page_size'] = $page_size;

		$result_list = $this->model_order->order_list($data);	// 주문 리스트 
		$result_list_count = $this->model_order->order_list_count($data); // 주문 리스트 총 카운트
		$order_list_sum = $this->model_order->order_list_sum($data); // 주문 리스트 총 합계
		
		$no = $result_list_count-($page_size*($page_num-1));
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num);

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->order_list_sum = $order_list_sum;
		$response->no = $no;
		$response->paging = $paging;
		$response->history_data = $history_data;

		$this->_list_view(mapping('order').'/view_order_list_get', $response);
	}

	// 주문 리스트 엑셀
	public function order_list_excel(){
		$corp_id = $this->_input_check("corp_id",array());
		$corp_name = $this->_input_check("corp_name",array());
		$order_id = $this->_input_check("order_id",array());
		$order_name = $this->_input_check("order_name",array());
		$order_corp_name = $this->_input_check("order_corp_name",array());
		$s_date = $this->_input_check("s_date",array());
		$e_date = $this->_input_check("e_date",array());
		$order_state = $this->_input_check("order_state",array());
		$order_type = $this->_input_check("order_type",array());

		$data['corp_id'] = $corp_id;
		$data['corp_name'] = $corp_name;
		$data['order_id'] = $order_id;
		$data['order_name'] = $order_name;
		$data['order_corp_name'] = $order_corp_name;
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;
		$data['order_state'] = $order_state;
		$data['order_type'] = $order_type;

		$result_list = $this->model_order->order_list_excel($data);	// 주문 리스트 엑셀

		$response = new stdClass();

		$response->result_list = $result_list;

		$this->_list_view(mapping('order').'/view_order_list_excel', $response);
	}


   // PDF 파일 다운로드
  public function download_pdf() {
    $order_idx = $this->_input_check("order_idx",array());
    
    // TCPDF 인스턴스를 생성합니다.
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);   

    $pdf -> SetHeaderData(
                        "",
                        "0", 
                        "요따체크 거래명세표",
                        "",
                        array(0, 64, 255),
                        array(0, 64, 128)
                       );

    $pdf -> setFooterData(array(0, 64, 0), array(0, 64, 128));
    $pdf -> setHeaderFont(Array("nanumgothic", "", PDF_FONT_SIZE_MAIN));
    $pdf -> setFooterFont(Array("nanumgothic", "", PDF_FONT_SIZE_DATA));
    $pdf -> SetDefaultMonospacedFont("nanumgothic");
    $pdf -> SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
    $pdf -> SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf -> SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf -> SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf -> setImageScale(PDF_IMAGE_SCALE_RATIO);

    // 한글 폰트를 등록합니다.
    $pdf->SetFont('nanumgothic', '12', '', '');    


		 $response = new stdClass();	  
     $data_arr =explode(",",$order_idx);    
    
    for($y=0;$y<count($data_arr);$y++){
       //데이타 세팅        
       $data['order_idx'] = $data_arr[$y];  
       $rt = $this->model_order->order_detail($data);
       $response->result = $rt['result'];    
		   $result_list = $rt['result_list'];    
       
       $total_cnt=count($result_list);
       $i=0;
       $j=0;
		   $data_array = array();
       foreach($result_list as $row){
          $x =$i; 
        
          $data_array[$x]['product_name'] = $row->product_name;
          $data_array[$x]['product_ea'] = $row->product_ea;
          $data_array[$x]['product_standard'] = $row->product_standard;
          $data_array[$x]['product_price'] = $row->product_price;
          $data_array[$x]['tot_product_price'] = $row->tot_product_price;
          $data_array[$x]['etc'] = $row->etc;        
	    	 
          $i++;
          $j++;

          if( ($i % 10) ==0 ||    $j ==  $total_cnt){
            $response->data_array = $data_array;  
    
            $pdf->AddPage(); 
   
            $html = $this->load->view(mapping('order').'/view_order_list_pdf', $response, true);
            $pdf->writeHTML($html, true, false, true, false, ''); 

            $i=0;  
            $data_array = array();        
          }        
       }    
    }
  
    $pdf->Output('거래명세서.pdf', 'D');//D
  }

	// 주문 상세
	public function order_detail(){
		$order_idx = $this->_input_check("order_idx",array("empty_msg"=>"order 코드 누락"));
		$history_data = $this->_input_check("history_data",array());

		$data['order_idx'] = $order_idx;

		$rt = $this->model_order->order_detail($data); // 주문 상세
		
		$response = new stdClass();
		
		$response->result = $rt['result'];
		$response->history_data = $history_data;
		
		$this->_view(mapping('order').'/view_order_detail', $response);
	}


  //다운로드
  public function file_download(){

		$order_idx = $this->_input_check('order_idx',array());
	
		$data['order_idx'] = $order_idx;
	
		$rt = $this->model_order->order_detail($data);	
    $result =$rt['result'];
	
		if(!empty($result)){
	
			$this->load->helper('download');
	
			$db_file =$result->request_contents;
			$name =$result->orig_name;
			
			$temp_file = explode("media",$db_file);
			$filepath = '/home/yodda/media'.$temp_file[1];
	
			$data = file_get_contents($filepath); // Read the file's contents
	
			force_download($name, $data);
	
		}
	}

	
	// 주문 수정
	public function order_mod_up(){
		$order_idx = $this->_input_check("order_idx",array("empty_msg"=>"키가 누락되었습니다."));
		$order_state = $this->_input_check("order_state",array());
		
		$data['order_idx'] = $order_idx;
		$data['order_state'] = $order_state;
		
		if ($order_state=='3') {
			
			// 취소
			$order_check = $this->model_order->order_check($data); // 주문 내역 체크
			$pg = $this->pg_function->pg_cancel($order_check->payment_order_number, $order_check->pg_tid, $order_check->pg_price, "12시간 이전 멘티 취소");
			if($pg['pg_result'] !="Y"){
			  $response = new stdClass();
			  $response->code = "-1";
			  $response->code_msg = "[실패사유]".$pg['message'];
			  echo json_encode($response);
			  exit;
			}
		}

		$result = $this->model_order->order_mod_up($data); // 주문 수정

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

}	// 클래스의 끝
