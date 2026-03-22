<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	송민지
| Create-Date : 2021-01-15
|------------------------------------------------------------------------
*/

class Board_v_1_0_0 extends MY_Controller {
  function __construct(){
    parent::__construct();

    $this->load->model(mapping('board').'/model_board');

  }

//인덱스
	public function index(){

		$this->board_list();

	}


//
	public function board_list(){
    $category = $this->_input_check("category",array());
	  $data['board_category_idx'] = $category;
		    
    $response = new stdClass();

		$response->category = $category;
		$response->board_category_detail = $this->model_common->board_category_detail($data);

    $this->_view(mapping('board').'/view_board_list', $response);
   
	}

  public function board_list_get(){
    $page_num = $this->_input_check("page_num",array("ternary"=>'1'));
    $orderby = $this->_input_check("orderby",array());
    $category = $this->_input_check("category",array());
    $s_text = $this->_input_check("s_text",array());
    $page_size = PAGESIZE;

    $data['orderby'] = $orderby;
    $data['category'] = $category;
    $data['s_text'] = $s_text;
    $data['page_no'] = ($page_num-1)*$page_size;
    $data['page_size'] = $page_size;

    $result_list = $this->model_board->board_list($data);
    $result_list_count = $this->model_board->board_list_count($data);	
		$paging = $this->global_function->paging($result_list_count, $page_size, $page_num,'default_list_get');

    $response = new stdClass();

    $response->s_text = $s_text;
    $response->result_list = $result_list;
    $response->result_list_count = $result_list_count;
    $response->total_block = ceil($result_list_count/$page_size);
    $response->paging = $paging;

    $this->_list_view(mapping('board').'/view_board_list_get', $response);
  }


//
	public function board_detail(){
    $board_idx = $this->_input_check("board_idx",array("empty_msg"=>lang('lang_10302','키가 누락되었습니다.')));
    $category = $this->_input_check("category",array());

    $data['board_idx'] = $board_idx;
    $data['board_category_idx'] = $category;

    $this->model_board->board_read_mod_up($data);
    $result = $this->model_board->board_detail($data);
    
    if(empty($result) ){
      redirect("/".$this->current_lang."/".mapping('board')."/?category=$category");
    }else{

			$response = new stdClass();
			$response->board_category_detail = $this->model_common->board_category_detail($data); 
			$response->result = $result;
			$this->_view(mapping('board').'/view_board_detail',$response);

   }
	}

//
	public function board_reg(){
		$category = $this->_input_check("category",array());


		if(empty($this->member_idx)){
			redirect("/". $this->current_lang."/".mapping('login')."?return_url=/".mapping('board')."/board_reg&category=".$category);
			exit;
		}

		$response = new stdClass();

		$response->category = $category;

    $this->_view(mapping('board').'/view_board_reg', $response);

	}

  public function board_reg_in(){
		$category = $this->_input_check("category",array("empty_msg"=>lang('lang_10303','카테고리를 선택해 주세요.'),"focus_id"=>"category"));
		$board_img = $this->_input_check("board_img",array());		
		$title = $this->_input_check("title",array("empty_msg"=>lang('lang_10304','제목을 입력해 주세요.'),"focus_id"=>"title"));
		$contents = $this->_input_check("contents",array("empty_msg"=>lang('lang_10305','내용을 입력해 주세요.'),"focus_id"=>"contents"));
		$contents = $_POST['contents'];
		$contents_text = $this->_input_check("contents_text",array());
		$site_code = $this->_input_check("site_code",array());		
		
		$data['category'] = $category;
		$data['title'] = $title;
		$data['contents'] = $contents;
		$data['contents_text'] = $contents_text;
		$data['board_img'] = $board_img;
		$data['site_code'] = $site_code;

		$result = $this->model_board->board_reg_in($data); //공지사항 등록하기

		$response = new stdClass();

		if($result == "0") {
			$response->code = 0;
			$response->code_msg 	= lang('lang_10306','등록 실패하였습니다. 다시 시도 해주시기 바랍니다.');
		} else{
			$response->code = 1;
			$response->code_msg 	= lang('lang_10307','게시글을 등록하였습니다.');
		}
		echo json_encode($response);
		exit;
	}

//
	public function board_mod(){
    $board_idx = $this->_input_check("board_idx",array("empty_msg"=>lang('lang_10308','키가 누락되었습니다.')));

		$data['board_idx'] = $board_idx;

		$result = $this->model_board->board_detail($data);

		$response = new stdClass();
		$response->result = $result;
	
		$this->_view(mapping('board').'/view_board_mod',$response);

	}

  public function board_mod_up(){
		$board_idx = $this->_input_check("board_idx",array());
		$category = $this->_input_check("category",array("empty_msg"=>lang('lang_10309','카테고리를 선택해 주세요.'),"focus_id"=>"category"));
		$board_img = $this->_input_check("board_img",array());
		$title = $this->_input_check("title",array("empty_msg"=>lang('lang_10310','제목을 입력해 주세요.'),"focus_id"=>"title"));
		$contents = $this->_input_check("contents",array("empty_msg"=>lang('lang_10311','내용을 입력해 주세요.'),"focus_id"=>"contents"));
		$contents = $_POST['contents'];
		$contents_text = $this->_input_check("contents_text",array());

		$data['board_idx'] = $board_idx;
		$data['category'] = $category;
		$data['title'] = $title;
		$data['contents'] = $contents;
		$data['contents_text'] = $contents_text;
		$data['board_img'] = $board_img;


		$result = $this->model_board->board_mod_up($data); //공지사항 수정

		$response = new stdClass();

		if($result == "0") {
			$response->code = 0;
			$response->code_msg 	= lang('lang_10312','수정 실패하였습니다. 다시 시도 해주시기 바랍니다.');
		} else {
			$response->code = 1;
			$response->code_msg 	= lang('lang_10313','수정되었습니다.');
		}
		echo json_encode($response);
		exit;
	}

	//삭제
	public function board_del(){
		$board_idx = $this->_input_check("board_idx",array());

		$data['board_idx'] = $board_idx;

		$result = $this->model_board->board_del($data);

		$response = new stdClass();

		if($result == "0") {
			$response->code = 0;
			$response->code_msg 	= lang('lang_10314','실패하였습니다. 다시 시도 해주시기 바랍니다.');
		} else {
			$response->code = 1;
			$response->code_msg 	= lang('lang_10315','삭제 되었습니다.');
		}
		echo json_encode($response);
		exit;
	}

	// 2-1.댓글 리스트
	public function board_reply_list_get(){
		$board_idx = $this->_input_check('board_idx', array("empty_msg"=>lang('lang_10316','게시판키를 입력해주세요.'),"focus_id"=>"board_idx"));
		$owner_idx = $this->_input_check('owner_idx', array());

		$data['board_idx'] = $board_idx;
		$data['member_idx'] = $this->member_idx;

		$board_summary  = $this->model_board->board_summary($data);
		$result_list  = $this->model_board->board_comment_list($data);
		$comment_reply_array = $this->model_board->board_comment_reply_list($data);

		$response = new stdClass();

		$response->owner_idx = $owner_idx;
		$response->board_summary = $board_summary;
		$response->result_list = $result_list;
		//$response->comment_reply_array = $comment_reply_array;

		$this->_list_view(mapping('board').'/view_board_reply_list_get', $response);

	}

	// 댓글 등록
	public function board_comment_reg_in(){
		$member_idx = $this->member_idx;
		$board_idx = $this->_input_check('board_idx',array("empty_msg"=>lang('lang_10317','게시판키을 입력해주세요.'),"focus_id"=>"board_idx"));
		$reply_comment = $this->_input_check('reply_comment',array("empty_msg"=>lang('lang_10318','댓글을 입력해주세요.'),"focus_id"=>"reply_comment"));
		$board_reply_idx = $this->_input_check('board_reply_idx',array());

		if($board_reply_idx == ""){
			$depth=0;
			$reply_depth=0;
			$board_reply_idx=0;
			$parent_board_reply_idx=0;
			$grand_parent_board_reply_idx=0;
		}else{
			$data['board_reply_idx']  = $board_reply_idx;
			$check = $this->model_board->board_reply_detail($data);
			$depth=$check->next_depth;
			if($depth=="1"){
				$parent_board_reply_idx=$board_reply_idx;
				$grand_parent_board_reply_idx=$board_reply_idx;
				$reply_depth=$check->next_reply_depth;
			}else{
				$parent_board_reply_idx=$board_reply_idx;
				$grand_parent_board_reply_idx=$check->grand_parent_board_reply_idx;
				$reply_depth=$check->reply_depth;
			}

		}

		$data['member_idx'] = $member_idx;
		$data['board_idx'] = $board_idx;
		$data['reply_comment'] = $reply_comment;
		$data['parent_board_reply_idx'] = $parent_board_reply_idx;
		$data['grand_parent_board_reply_idx'] = $grand_parent_board_reply_idx;
		$data['depth']  = $depth;
		$data['reply_depth']  = $reply_depth;
		$data['board_reply_idx']  = $board_reply_idx;


		$result = $this->model_board->board_comment_reg_in($data);//# model 5. 댓글 등록

		$response = new stdClass();

		if($result < 0){
			$response->code = "-1";
			$response->code_msg = lang('lang_10319','댓글(답글)등록 실패하였습니다. 관리자에게 문의해주세요.');
		}else{
			$response->code = "1000";
			$response->code_msg = lang('lang_10320','댓글등록 성공하였습니다.');
		}

		echo json_encode($response);
		exit;
	}

	// 2-3. 댓글 수정
	public function reply_comment_mod_up(){
		header('Content-Type: application/json');
		$board_reply_idx = $this->_input_check('board_reply_idx',array());
		$reply_comment = $this->_input_check('reply_comment',array());

		$data['board_reply_idx'] = $board_reply_idx;
		$data['reply_comment'] = $reply_comment;
		$data['member_idx'] = $this->member_idx;

		$result = $this->model_board->reply_comment_mod_up($data);

		$response = new stdClass();

		if($result < 0){
			$response->code = "-1";
			$response->code_msg = lang('lang_10321','댓글삭제 처리 중 오류가 발생했습니다. 관리자에게 문의해주세요.');
		}else{
			$response->code = "1000";
			$response->code_msg = lang('lang_10322','댓글삭제 성공하였습니다.');
			$response->reply_comment =nl2br($reply_comment);
		}

		echo json_encode($response);
		exit;
	}
  

	// 2-3. 댓글 삭제
	public function reply_comment_del(){
		header('Content-Type: application/json');
		$board_reply_idx = $this->_input_check('board_reply_idx',array());

		$data['board_reply_idx'] = $board_reply_idx;
		$data['member_idx'] = $this->member_idx;

		$result = $this->model_board->reply_comment_del($data);

		$response = new stdClass();

		if($result < 0){
			$response->code = "-1";
			$response->code_msg = lang('lang_10323','댓글삭제 처리 중 오류가 발생했습니다. 관리자에게 문의해주세요.');
		}else{
			$response->code = "1000";
			$response->code_msg = lang('lang_10324','댓글삭제 성공하였습니다.');
		}

		echo json_encode($response);
		exit;
	}

} // 클래스의 끝
?>
