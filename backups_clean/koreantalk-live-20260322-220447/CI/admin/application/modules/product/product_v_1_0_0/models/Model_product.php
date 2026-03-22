<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	-
| Create-Date : 2018-09-28
| Memo : 상품관리
|------------------------------------------------------------------------
*/

Class Model_product extends MY_Model {

	// 상품 리스트
	public function product_list($data){		
    $page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$product_name = $data['product_name'];
		$display_yn = $data['display_yn'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$product_b_category_idx = $data['product_b_category_idx'];
		$product_m_category_idx = $data['product_m_category_idx'];
		$product_s_category_idx = $data['product_s_category_idx'];

		$sql = "SELECT
							a.product_idx,
							a.product_name,
							a.product_price,
							a.display_yn,
							product_img_path,					
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') as ins_date
						FROM
							tbl_product a
						
						WHERE
							a.del_yn = 'N'
		";

		if($product_name != ""){
			$sql .=" AND a.product_name LIKE '%$product_name%' ";
		}
		if($display_yn != ""){
			$sql .=" AND a.display_yn = '$display_yn'  ";
		}		
		if($product_b_category_idx != ""){
			$sql .= "  and  product_b_category_idx ='$product_b_category_idx'  ";
		}
		if($product_m_category_idx != ""){
			$sql .= "  and product_m_category_idx ='$product_m_category_idx'  ";
		}
		if($product_s_category_idx != ""){
			$sql .= "  and product_s_category_idx ='$product_s_category_idx'  ";
		}
    if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}		

		$sql .= "  ORDER BY  product_idx desc  limit ?, ? ";

		return	$this->query_result($sql,
                                array(
                                $page_no,
                                $page_size
                                ),
                                $data);

	}

	// 상품 리스트 총 카운트
	public function product_list_count($data){

    $product_name = $data['product_name'];
		$display_yn = $data['display_yn'];
    $s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$product_b_category_idx = $data['product_b_category_idx'];
		$product_m_category_idx = $data['product_m_category_idx'];
		$product_s_category_idx = $data['product_s_category_idx'];

		$sql = "SELECT
							COUNT(*) AS cnt

						FROM
							tbl_product a						
						WHERE
							a.del_yn = 'N'
		       ";

		if($product_name != ""){
			$sql .=" AND a.product_name LIKE '%$product_name%' ";
		}
		if($display_yn != ""){
			$sql .=" AND a.display_yn = '$display_yn'  ";
		}		
		if($product_b_category_idx != ""){
			$sql .= "  and  product_b_category_idx ='$product_b_category_idx'  ";
		}
		if($product_m_category_idx != ""){
			$sql .= "  and product_m_category_idx ='$product_m_category_idx'  ";
		}
		if($product_s_category_idx != ""){
			$sql .= "  and product_s_category_idx ='$product_s_category_idx'  ";
		}
    if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}		

		return	$this->query_cnt($sql,
                            array(
                            ),
                            $data);
	}



	// 상품 상세
	public function product_detail($data){

		$product_idx = $data['product_idx'];

		$sql = "SELECT
							a.product_idx,					
							a.product_name,							
							a.product_b_category_idx,
							a.product_m_category_idx,
							a.product_s_category_idx,						
							a.product_price,
							a.product_desc,						
							a.product_contents,						
							a.product_img_path,						
							a.product_detail_img_path,						
							a.smart_store_url,						
							a.lecture_movie_idx,						
							a.author,						
							a.display_yn,				
							a.ins_date
						FROM
							tbl_product a						
						WHERE
							a.del_yn = 'N'
						AND a.product_idx = ?
					";

		return	$this->query_row($sql,
                            array(
                            $product_idx
                            ),
                            $data);
	}

 

  // 대 카테고리 리스트 
	public function category_b_list() {

		$sql = "SELECT
							category_management_idx,
							category_name,
							DATE_FORMAT(ins_date,'%Y-%m-%d') as ins_date
						FROM
							tbl_category_management
						WHERE
							del_yn = 'N'
						  AND	category_depth = '1'
						  AND	type = '1'
						  AND	state = '1'
						";


		return	$this->query_result($sql,
                                array(
                                )
                                );
	}
  
  // 카테고리 리스트 
	public function category_list($data) {

		$category_idx = $data['category_idx'];

		$sql = "SELECT
							category_management_idx,
							category_name,
							DATE_FORMAT(ins_date,'%Y-%m-%d') AS ins_date
						FROM
							tbl_category_management
						WHERE
							del_yn = 'N'
						AND		parent_category_management_idx = $category_idx
						AND		type = '1'
						AND		state = '1'
		";

		return $this->query_result($sql,
                              array(
                              ),
                              $data);
	}

	// 상품 등록
	public function product_reg_in($data){

    $product_name = $data['product_name'];
    $author = $data['author'];
		$product_b_category_idx = $data['product_b_category_idx'];
		$product_m_category_idx = $data['product_m_category_idx'];	
		$product_s_category_idx = $data['product_s_category_idx'];	
		$product_price = $data['product_price'];
		$product_contents = $data['product_contents'];		
		$product_desc = $data['product_desc'];		
		$product_img_path = $data['product_img_path'];
		$product_detail_img_path = $data['product_detail_img_path'];
		$smart_store_url = $data['smart_store_url'];
		$lecture_movie_idx = $data['lecture_movie_idx'];	
    $display_yn = $data['display_yn'];	

		$this->db->trans_begin();

    $sql = "INSERT INTO
              tbl_product
            (             
              product_name,          
              author,          
              product_b_category_idx,              
              product_m_category_idx,              
              product_s_category_idx, 
              product_price,
              product_contents,           
              product_desc,           
              product_img_path,
              product_detail_img_path,
              smart_store_url,
              lecture_movie_idx,              
              display_yn,
              del_yn,
              ins_date,
              upd_date
            ) VALUES (
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,
              ?,              
              ?,
              ?,
              ?,
              ?,
              ?,             
              ?,             
              'N',
              NOW(),
              NOW()
            )
    ";

    $this->query($sql,
                array(
                  $product_name,          
                  $author,          
                  $product_b_category_idx,              
                  $product_m_category_idx,              
                  $product_s_category_idx, 
                  $product_price,
                  $product_contents,           
                  $product_desc,           
                  $product_img_path,
                  $product_detail_img_path,
                  $smart_store_url,
                  $lecture_movie_idx,                 
                  $display_yn,
                ),
                $data);

    $product_idx = $this->db->insert_id();

    if($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      return 0;
    }else{
      $this->db->trans_commit();
      return 1;
    }
	}

	// 상품 수정
	public function product_mod_up($data){

		$product_idx = $data['product_idx'];
    $product_name = $data['product_name'];
    $author = $data['author'];
		$product_b_category_idx = $data['product_b_category_idx'];
		$product_m_category_idx = $data['product_m_category_idx'];	
		$product_s_category_idx = $data['product_s_category_idx'];	
		$product_price = $data['product_price'];
		$product_contents = $data['product_contents'];		
		$product_desc = $data['product_desc'];		
		$product_img_path = $data['product_img_path'];
		$product_detail_img_path = $data['product_detail_img_path'];
		$smart_store_url = $data['smart_store_url'];
		$lecture_movie_idx = $data['lecture_movie_idx'];			
		$display_yn = $data['display_yn'];	

		$this->db->trans_begin();

    $sql = "UPDATE
              tbl_product
            SET
              product_name=?,
              author=?,
              product_b_category_idx=?,            
              product_m_category_idx=?,             
              product_s_category_idx=?,
              product_price=?,
              product_contents=?,          
              product_desc=?,          
              product_img_path=?,
              product_detail_img_path=?,
              smart_store_url=?,
              lecture_movie_idx=?,              
              display_yn=?,
              upd_date=NOW()
            WHERE
              product_idx= ?

    ";

    $this->query($sql,
                array(
                $product_name,
                $author,
                $product_b_category_idx,             
                $product_m_category_idx,               
                $product_s_category_idx, 
                $product_price,
                $product_contents,             
                $product_desc,             
                $product_img_path,
                $product_detail_img_path,
                $smart_store_url,
                $lecture_movie_idx,                
                $display_yn,              
                $product_idx
                ),
                $data);

		if($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      return "0";
	  }else{
      $this->db->trans_commit();
      return "1";
	  }

	}

	// 상품 삭제
	public function product_del($data){
		
    $product_idx=$data['product_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_product
						SET
							del_yn = 'Y'
						WHERE
							product_idx IN($product_idx)
						";

		$this->query($sql,
								array(
								),
								$data);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
  }

	// 상품 상태 변경
	public function product_state_up($data){
		$product_idx=$data['product_idx'];
		$product_state=$data['product_state'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_product
						SET
							product_state = ?
						WHERE
							product_idx =?
		       ";
		$this->query($sql,
                array(
                $product_state,
                $product_idx,
                ),
                $data);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
  }

  
  
}	// 클래스의 끝

?>
