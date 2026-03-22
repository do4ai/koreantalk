<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	송민지
| Create-Date : 2023
|------------------------------------------------------------------------
*/

class Find_pw_v_0_0_0 extends MY_Controller {
  function __construct(){
    parent::__construct();

  }

//인덱스
	public function index(){

		$this->find_pw_list();

	}

// find
	public function find_pw_list(){

		$this->_view(mapping('find_pw').'/view_find_pw_list');
	}

} // 클래스의 끝
?>
