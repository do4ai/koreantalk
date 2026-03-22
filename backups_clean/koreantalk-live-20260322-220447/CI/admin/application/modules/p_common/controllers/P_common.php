<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2016-06-14
| Memo : 프로젝트 공통 기능
|------------------------------------------------------------------------
*/

class P_common extends MY_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('p_common/model_p_common');
	}

  // 카테고리 리스트 
	public function  category_list(){
		header('Content-Type: application/json');

		$parent_category_management_idx = $this->_input_check("parent_category_management_idx",array());
		$type = $this->_input_check("type",array());

		$data['parent_category_management_idx'] = $parent_category_management_idx;
		$data['type'] = $type;

		$result_list = $this->model_p_common->category_list($data);

		echo json_encode($result_list);
	}

  // 시도 리스트 
	public function city_list(){
    header('Content-Type: application/json');

		$result_list = $this->model_p_common->city_list(); // 시도 리스트

		echo json_encode($result_list);
	}

  // 시구군 리스트 
	public function  region_list(){
		header('Content-Type: application/json');

		$city_code = $this->_input_check("city_code",array());

		$data['city_code'] = $city_code;

		$result_list = $this->model_p_common->region_list($data); //시구군 리스트 

		echo json_encode($result_list);
	}

  // 읍면동 리스트
	public function  dong_list(){
		header('Content-Type: application/json');
		$region_code = $this->_input_check("region_code",array());

		$data['region_code'] = $region_code;

		$result_list = $this->model_p_common->dong_list($data); // 읍면동 리스트 

		echo json_encode($result_list);
	}



}
