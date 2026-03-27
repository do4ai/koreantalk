<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 서점
|------------------------------------------------------------------------
*/

class Model_electron_book extends MY_Model{
	//약관 리스트
	public function terms_list() {

		$sql = "SELECT
							terms_management_idx,
							title,
							type,
							member_type,
							contents,
							upd_date
						FROM
							tbl_terms_management
						WHERE type<2
            and site_code='$this->current_lang'
							
          	";

  	return $this->query_result($sql,
                                array(
																)
                              );

	}

	//상세
  public function terms_detail($data){

    $type = $data['type'];

    $sql = "SELECT
              terms_management_idx,
              title,
              contents
            FROM
              tbl_terms_management
            WHERE  type =?
    ";

    return $this->query_row($sql,
                            array(
                            $type
                            )
                            );
  }



  // 리스트
  public function electron_book_list_get($data){
    $page_size=(int)$data['page_size'];
    $page_no=(int)$data['page_no'];

    $s_text=$data['s_text'];

    $sql = "SELECT
              a.electron_book_idx,
              a.product_name,
              a.author,
              a.product_img_path,
              a.product_price,       
              a.product_desc as product_contents,
              a.del_yn,
              ifnull((select count(*) from tbl_order where del_yn='N' and product_idx=a.electron_book_idx and member_idx='$this->member_idx'),0) as my_buy_cnt,
              DATE_FORMAT(ins_date, '%Y-%m-%d') as  ins_date
            FROM
              tbl_electron_book a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              and a.site_code='$this->current_lang'
              
    ";
		if($s_text !=""){
			$sql .= " AND ( product_name like '%$s_text%' or  product_contents like '%$s_text%' ) ";
		}

    $sql .="ORDER BY electron_book_idx DESC limit ?, ?";

    return $this->query_result($sql,
                              array(
                                $page_no,
                                $page_size
                              ),$data
                            );
  }

  //카운트
  public function electron_book_list_count($data){
    $s_text=$data['s_text'];

    $sql = "SELECT
            count(1) as cnt
            FROM
              tbl_electron_book a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              and a.site_code='$this->current_lang'
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
  public function electron_book_detail($data){
    $electron_book_idx=$data['electron_book_idx'];

    $rt= array();

    $sql = "SELECT
              electron_book_idx,
              author,
              a.product_name,
              a.author,
              a.product_img_path,
              a.product_detail_img_path,
              a.product_price,       
              a.product_desc,
              a.product_contents,
              lecture_movie_idx,
              ifnull((select count(*) from tbl_order where del_yn='N' and product_idx=a.electron_book_idx and member_idx='$this->member_idx'),0) as my_buy_cnt,
              a.ins_date
            FROM
              tbl_electron_book a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              and electron_book_idx=?
    ";

    $result=$this->query_row($sql,
                                    array(
                                      $electron_book_idx
                                    ),$data
                                  );
    $rt['result'] =$result;

    if(empty($result)){
      $rt['movie_info'] = null;
      return $rt;
    }

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


   //인수증 작성
	public function order_reg_in($data) {

		$order_number = $data['order_number'];
		$product_auth_code = $data['product_auth_code'];
		$electron_book_idx = $data['electron_book_idx'];

		$this->db->trans_begin();

		$sql = " INSERT INTO
							tbl_order
							( 
                site_code, 
                order_number, 
                order_date,
                member_idx,                
                order_id, 
                order_name, 
                order_phone, 
                order_email, 
                product_idx,
                product_auth_code,
                product_name,
                product_price,
                product_img_path,
								ins_date,
								upd_date
							)
							SELECT             
              
              	?,
								?,
								NOW(), 														
								a.member_idx,								
                a.member_id,							
                a.member_name,							
                a.member_phone,							
                a.member_id,							
                b.electron_book_idx,
                ?,            
                b.product_name,
                b.product_price,
                b.product_img_path,
                NOW(), 
								NOW() 
							 FROM
							  tbl_member AS a,
                 (select electron_book_idx,product_name,product_price,product_img_path from tbl_electron_book where electron_book_idx=?) as b               
							 WHERE 
                a.member_idx = ?
		";

		$this->query($sql,array(
														$this->current_lang,
														$order_number,
														$product_auth_code,
		                        $electron_book_idx,
                            $this->member_idx
														));
 
    if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		} else {
			$this->db->trans_commit();
		  return "1";
		}

	}

  // 상세
  public function order_detail($data){
    $order_number=$data['order_number'];

    $sql = "SELECT
              a.order_number,
              a.order_date,
              a.product_name,           
              a.product_img_path,   
              a.ins_date
            FROM
            tbl_order a
            WHERE
              a.del_yn = 'N'      
              and order_number=?
    ";

    return $this->query_row($sql,
                                    array(
                                      $order_number
                                    ),$data
                                  );
   

                                                              
  }

   
} // 클래스의 끝
?>
