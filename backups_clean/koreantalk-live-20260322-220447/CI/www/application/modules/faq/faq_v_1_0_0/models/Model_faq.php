<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2023-07-21
| Memo : FAQ
|------------------------------------------------------------------------
*/

class Model_faq extends MY_Model{

// 리스트
public function faq_list(){

  $sql = "SELECT
            a.faq_idx,
            a.title,
            a.contents,
            a.del_yn,
            a.ins_date        
          FROM
            tbl_faq a       
          WHERE
            a.del_yn = 'N'
            and a.site_code ='$this->current_lang'
           
  ";
  $sql .="ORDER BY faq_idx DESC ";

  return $this->query_result($sql,
                            array(
                          
                            )
                          );
}


//카운트
public function faq_list_count(){

  $sql = "SELECT
          count(1) as cnt
          FROM
            tbl_faq a          
          WHERE
            a.del_yn = 'N'
            and a.site_code ='$this->current_lang'
           

  ";

  return $this->query_cnt($sql,
                            array(
                            )
                          );
}








} // 클래스의 끝
?>
