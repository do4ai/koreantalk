<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('error_code_msg')){

  //결과 메시지
	function error_code_msg($code){
		if(strpos($code, '-') !== false){
			//에러 메시지 -2 ~ (-1은 input_check 에서 사용 하고 있음)
			$return_msg_arr =  array(
				'-2' => '실패 하였습니다.',
				'-3' => '조회된 값이 없습니다.'
			);
			$return_msg = $return_msg_arr[$code];
		}else{
			//성공 메시지 1000~
			$return_msg_arr =  array(
				'1000' => '정상적으로 처리되었습니다.',
				'1001' => '정상적으로 로그아웃 되었습니다.',
				'2000' => '조회된 리스트가 없습니다.'
			);
			$return_msg = $return_msg_arr[$code];
		}

		return $return_msg;
	}
}