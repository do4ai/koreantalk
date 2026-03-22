<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :  -
| Create-Date : 2017-07-24
| Memo : 로그 아웃
|------------------------------------------------------------------------
*/

class Logout extends MY_Controller {

	public function index() {
		$this->load->helper('url');	
		$this->load->library('session');
		
	
		$this->session->sess_destroy();
		redirect('/');
		//header("Location: /");
	}



}
?>