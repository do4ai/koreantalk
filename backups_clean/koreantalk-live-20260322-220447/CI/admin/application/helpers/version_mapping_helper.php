<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('mapping')){

  function mapping($str = '' ){

    $CI = get_instance();
    $CI->load->library('session');
    $version_change = $CI->session->userdata('version_change');

    if(isset($_SESSION['version_change'])){
      return $str = $version_change[$str];
    }

    if($_SERVER['REMOTE_ADDR'] == '211.118.222.232'){
      //개발

      $return_arr =  array(
        'main' => 'main_v_1_0_0', // 대시보드   
        'member' => 'member_v_1_0_0', // 회원 관리       
        'category_management' => 'category_management_v_1_0_0', // 카테고리 관리       
        'product' => 'product_v_1_0_0', // 상품 관리     
        'order' => 'order_v_1_0_0', // 일반상품 결제       
        'board' => 'board_v_1_0_0', // 자유게시판 관리       
        'board_category' => 'board_category_v_1_0_0', // 자유게시판 관리       
        'notice' => 'notice_v_1_0_0', // 공지사항 관리
        'faq' => 'faq_v_1_0_0', // FAQ 관리
        'qa' => 'qa_v_1_0_0', // QA 관리       
        'banner' => 'banner_v_1_0_0', // 배너 관리  		
        'terms' => 'terms_v_1_0_0', // 약관 관리
        'suboperator' => 'suboperator_v_1_0_0', // 관리자 관리
        'electron_book' => 'electron_book_v_1_0_0', //전차책관리
        'lecture' => 'lecture_v_1_0_0', // 교육영상관리
        'site' => 'site_v_1_0_0', // 사이트 관리
        'board_notice' => 'board_notice_v_1_0_0', // 커뮤니티 공지사항 관리
        
  		);

    }else{
      //운영
      $return_arr =  array(
        'main' => 'main_v_1_0_0', // 대시보드   
        'member' => 'member_v_1_0_0', // 회원 관리       
        'category_management' => 'category_management_v_1_0_0', // 카테고리 관리       
        'product' => 'product_v_1_0_0', // 상품 관리     
        'order' => 'order_v_1_0_0', // 일반상품 결제       
        'board' => 'board_v_1_0_0', // 자유게시판 관리       
        'board_category' => 'board_category_v_1_0_0', // 자유게시판 관리       
        'notice' => 'notice_v_1_0_0', // 공지사항 관리
        'faq' => 'faq_v_1_0_0', // FAQ 관리
        'qa' => 'qa_v_1_0_0', // QA 관리       
        'banner' => 'banner_v_1_0_0', // 배너 관리  		
        'terms' => 'terms_v_1_0_0', // 약관 관리
        'suboperator' => 'suboperator_v_1_0_0', // 관리자 관리
        'electron_book' => 'electron_book_v_1_0_0', //전차책관리
        'lecture' => 'lecture_v_1_0_0', // 교육영상관리
        'site' => 'site_v_1_0_0', // 사이트 관리
        'board_notice' => 'board_notice_v_1_0_0', // 커뮤니티 공지사항 관리
  		);
    }

		return $str = $return_arr[$str];
  }
}
