<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : faq
|------------------------------------------------------------------------
*/

class Model_faq extends MY_Model{

// 리스트
public function faq_list($data){
  $page_size=(int)$data['page_size'];
  $page_no=(int)$data['page_no'];

  $sql = "SELECT
            a.faq_idx,
            a.title,
            a.contents,
            a.del_yn,
            a.ins_date,
            a.faq_category_idx,
            b.faq_category_name
          FROM
            tbl_faq a
            LEFT JOIN tbl_faq_category b ON b.faq_category_idx = a.faq_category_idx
          WHERE
            a.del_yn = 'N'
  ";
  $sql .="ORDER BY faq_idx DESC limit ?, ?";

  return $this->query_result($sql,
                            array(
                              $page_no,
                              $page_size
                            ),$data
                          );
}

//카운트
public function faq_list_count(){

  $sql = "SELECT
          count(1) as cnt
          FROM
            tbl_faq a
          LEFT JOIN tbl_faq_category b ON b.faq_category_idx = a.faq_category_idx
          WHERE
            a.del_yn = 'N'

  ";

  return $this->query_cnt($sql,
                            array(
                            )
                          );
}








} // 클래스의 끝
?>
