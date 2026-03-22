<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('mapping')){

  function mapping($str = '' ){

    $CI = get_instance();
    $CI->load->library('session');
    $version_change = $CI->session->userdata('version_change');

    if(isset($_SESSION['version_change'])){
      return $str = $version_change[$str];
    }

    if(strpos($_SERVER['REMOTE_ADDR'],'121.140.138.176')== false){
      //개발

      $return_arr =  array(
        'main' => 'main_v_1_0_0', // ------------------------------- 대시보드
        'login' => 'login_v_1_0_0', // ------------------------------- login
        'find_pw' => 'find_pw_v_1_0_0', // --------------------------- find_pw
        'find_id' => 'find_id_v_1_0_0', // --------------------------- find_id
        'join' => 'join_v_1_0_0', // --------------------------- join
        'board' => 'board_v_1_0_0', // --------------------------- order
        'product' => 'product_v_1_0_0', // --------------------------- order
        'electron_book' => 'electron_book_v_1_0_0', // --------------------------- order
        'order' => 'order_v_1_0_0', // --------------------------- order
        'lecture' => 'lecture_v_1_0_0', // --------------------------- order
        
        'mypage' => 'mypage_v_1_0_0', // --------------------------- mypage
        'member_info' => 'member_info_v_1_0_0', // --------------------------- member_info
        'my_lecture' => 'my_lecture_v_1_0_0', // --------------------------- my_pay
        'my_order' => 'my_order_v_1_0_0', // --------------------------- my_order
       
        'member_pw_change' => 'member_pw_change_v_1_0_0', // --------------------------- member_pw_change
        'member_out' => 'member_out_v_1_0_0', // --------------------------- member_out
        'notice' => 'notice_v_1_0_0', // --------------------------- 공지사항 관리
        'faq' => 'faq_v_1_0_0', // --------------------------------- FAQ 관리
        'qa' => 'qa_v_1_0_0', // ----------------------------------- QA 관리
        'terms' => 'terms_v_1_0_0', // ----------------------------- 약관 관리
        'email_verify' => 'email_verify_v_1_0_0', // ----------------------------- 이메일 인증 관리
        'excel_upload' => 'excel_upload_v_1_0_0', // ----------------------------- 핸드폰 인증 관리
  		);

    }else{
      //운영
      $return_arr =  array(
        'main' => 'main_v_1_0_0', // ------------------------------- 대시보드
        'login' => 'login_v_1_0_0', // ------------------------------- login
        'find_pw' => 'find_pw_v_1_0_0', // --------------------------- find_pw
        'find_id' => 'find_id_v_1_0_0', // --------------------------- find_id
        'join' => 'join_v_1_0_0', // --------------------------- join
        'board' => 'board_v_1_0_0', // --------------------------- order
        'product' => 'product_v_1_0_0', // --------------------------- order
        'electron_book' => 'electron_book_v_1_0_0', // --------------------------- order
        'order' => 'order_v_1_0_0', // --------------------------- order
        'lecture' => 'lecture_v_1_0_0', // --------------------------- order
        
        'mypage' => 'mypage_v_1_0_0', // --------------------------- mypage
        'member_info' => 'member_info_v_1_0_0', // --------------------------- member_info
        'my_lecture' => 'my_lecture_v_1_0_0', // --------------------------- my_pay
        'my_order' => 'my_order_v_1_0_0', // --------------------------- my_order
       
        'member_pw_change' => 'member_pw_change_v_1_0_0', // --------------------------- member_pw_change
        'member_out' => 'member_out_v_1_0_0', // --------------------------- member_out
        'notice' => 'notice_v_1_0_0', // --------------------------- 공지사항 관리
        'faq' => 'faq_v_1_0_0', // --------------------------------- FAQ 관리
        'qa' => 'qa_v_1_0_0', // ----------------------------------- QA 관리
        'terms' => 'terms_v_1_0_0', // ----------------------------- 약관 관리
        'email_verify' => 'email_verify_v_1_0_0', // ----------------------------- 이메일 인증 관리
        'excel_upload' => 'excel_upload_v_1_0_0', // ----------------------------- 핸드폰 인증 관리
  		);
    }

		return $str = $return_arr[$str];
  }
}
