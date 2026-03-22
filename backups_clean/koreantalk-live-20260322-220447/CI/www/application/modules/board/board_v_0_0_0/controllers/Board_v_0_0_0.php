<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	송민지
| Create-Date : 2021-01-15
|------------------------------------------------------------------------
*/

class Board_v_0_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct();

	}

//인덱스
  public function index() {

    $this->board_list();
  }

//메인 화면
  public function board_list(){

		$paging = $this->global_function->paging(100, 10, 1); 
		
		$response = new stdClass();
 
		$response->paging = $paging; 

		$this->_view(mapping('board').'/view_board_list',$response);
  }

//메인 화면
  public function board_detail(){
		$this->_view(mapping('board').'/view_board_detail');
  }

//메인 화면
  public function board_reg(){
		$this->_view(mapping('board').'/view_board_reg');
  }

//메인 화면
  public function board_mod(){
		$this->_view(mapping('board').'/view_board_mod');
  }
}// 클래스의 끝
?>
