<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 공지사항
|------------------------------------------------------------------------
*/

class Model_notice extends MY_Model{
  // 리스트
  public function notice_list_get($data){
    $page_size=(int)$data['page_size'];
    $page_no=(int)$data['page_no'];

    $sql = "SELECT
              a.notice_idx,
              a.title,
              a.contents,
              a.del_yn,
              DATE_FORMAT(ins_date, '%Y-%m-%d') as  ins_date
            FROM
              tbl_notice a
            WHERE
              a.del_yn = 'N'
              and site_code='$this->current_lang'
              
    ";
    $sql .="ORDER BY notice_idx DESC limit ?, ?";

    return $this->query_result($sql,
                              array(
                                $page_no,
                                $page_size
                              ),$data
                            );
  }

  //카운트
  public function notice_list_count(){

    $sql = "SELECT
            count(1) as cnt
            FROM
              tbl_notice a
            WHERE
              a.del_yn = 'N'
              and site_code='$this->current_lang'
             
    ";

    return $this->query_cnt($sql,
                              array(
                              )
                            );
  }

  // 리스트
  public function notice_detail($data){
    $notice_idx=$data['notice_idx'];

    $sql = "SELECT
              a.notice_idx,
              a.title,
              a.img_path,
              a.contents,
              a.del_yn,
              a.ins_date
            FROM
              tbl_notice a
            WHERE
              a.del_yn = 'N'
              and notice_idx=?
    ";

    return $this->query_row($sql,
                              array(
                                $notice_idx
                              ),$data
                            );
  }
} // 클래스의 끝
?>
