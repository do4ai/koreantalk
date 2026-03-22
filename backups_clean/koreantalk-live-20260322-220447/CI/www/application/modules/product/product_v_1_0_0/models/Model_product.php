<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 서점
|------------------------------------------------------------------------
*/

class Model_product extends MY_Model{
  // 리스트
  public function product_list_get($data){
    $page_size=(int)$data['page_size'];
    $page_no=(int)$data['page_no'];

    $s_text=$data['s_text'];

    $sql = "SELECT
              a.product_idx,
              a.author,
              a.product_name,
              a.product_price,
              a.product_img_path,
              a.smart_store_url,
              a.product_desc as product_contents,
              a.del_yn,
              DATE_FORMAT(ins_date, '%Y-%m-%d') as  ins_date
            FROM
              tbl_product a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              
    ";
		if($s_text !=""){
			$sql .= " AND ( product_name like '%$s_text%' or  product_contents like '%$s_text%' ) ";
		}

    $sql .="ORDER BY product_idx DESC limit ?, ?";

    return $this->query_result($sql,
                              array(
                                $page_no,
                                $page_size
                              ),$data
                            );
  }

  //카운트
  public function product_list_count($data){
    $s_text=$data['s_text'];

    $sql = "SELECT
            count(1) as cnt
            FROM
              tbl_product a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
             
    ";
    if($s_text !=""){
			$sql .= " AND ( product_name like '%$s_text%' or  product_contents like '%$s_text%' ) ";
		}

    return $this->query_cnt($sql,
                              array(
                              )
                            );
  }

  // 리스트
  public function product_detail($data){
    $product_idx=$data['product_idx'];

    $rt= array();

    $sql = "SELECT
              product_idx,
              author,
              product_name, 
              product_price, 
              product_img_path, 
              product_detail_img_path, 
              product_desc, 
              product_contents, 
              smart_store_url, 	
              lecture_movie_idx,
              a.ins_date
            FROM
              tbl_product a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              and product_idx=?
    ";

    $result=$this->query_row($sql,
                                    array(
                                      $product_idx
                                    ),$data
                                  );
    $rt['result'] =$result;  

    $sql = "SELECT 	
              a.lecture_movie_idx, 
              a.lecture_idx, 
              a.lecture_category_idx,
              b.site_code,
              b.lecture_name,
              b.contents,
              b.img_path	 
            FROM 
            tbl_lecture_movie  AS a
            JOIN tbl_lecture AS b ON b.lecture_idx=a.lecture_idx AND b.del_yn='N' 
            WHERE a.del_yn='N'
             and a.lecture_movie_idx=?
    ";

    $rt['movie_info']=$this->query_row($sql,
                                    array(
                                      $result->lecture_movie_idx
                                    ),$data
                                  ); 

    return $rt;                                                           
  }
   
} // 클래스의 끝
?>
