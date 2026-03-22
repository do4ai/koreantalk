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
    $site_code = $data['site_code'];

		$sql = "SELECT
							a.electron_book_idx,
							a.product_name,
							a.site_code,
							b.site_name,
							a.product_price,
							a.display_yn,
							product_img_path,					
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') as ins_date
						FROM
							tbl_electron_book a				
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'		
						WHERE
							a.del_yn = 'N'
		";

		if($product_name != ""){
			$sql .=" AND a.product_name LIKE '%$product_name%' ";
		}
		if($display_yn != ""){
			$sql .=" AND a.display_yn = '$display_yn'  ";
		}
    if($site_code != ""){
			$sql .=" AND a.site_code = '$site_code'  ";
		}
    if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}		

		$sql .= "  ORDER BY  electron_book_idx desc  limit ?, ? ";

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
		$site_code = $data['site_code'];

		$sql = "SELECT
							COUNT(*) AS cnt

						FROM
							tbl_electron_book a	
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'						
						WHERE
							a.del_yn = 'N'
		       ";

		if($product_name != ""){
			$sql .=" AND a.product_name LIKE '%$product_name%' ";
		}
		if($display_yn != ""){
			$sql .=" AND a.display_yn = '$display_yn'  ";
		}
    if($site_code != ""){
			$sql .=" AND a.site_code = '$site_code'  ";
		}
    if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}				


		return	$this->query_cnt($sql,
                            array(
                            ),
                            $data);
	}

	

	// 상품 상세
	public function product_detail($data){

		$electron_book_idx = $data['electron_book_idx'];

		$sql = "SELECT
							a.electron_book_idx,					
							a.site_code,
							a.product_name,
							a.author,
							a.product_price,
							a.product_contents,						
							a.product_desc,						
							a.product_img_path,						
							a.product_detail_img_path,						
							a.pdf_url,						
							a.pdf_org_name,						
							a.lecture_movie_idx,					
								
							a.display_yn,				
							a.ins_date
						FROM
							tbl_electron_book a						
						WHERE
							a.del_yn = 'N'
						AND a.electron_book_idx = ?
					";

		return	$this->query_row($sql,
                            array(
                            $electron_book_idx
                            ),
                            $data);
	}

 

  

	// 상품 등록
	public function product_reg_in($data){
    $site_code = $data['site_code'];
    $product_name = $data['product_name'];
    $author = $data['author'];
		$product_price = $data['product_price'];
		$product_contents = $data['product_contents'];		
		$product_desc = $data['product_desc'];		
		$product_img_path = $data['product_img_path'];
		$product_detail_img_path = $data['product_detail_img_path'];
		$pdf_url = $data['pdf_url'];
		$pdf_org_name = $data['pdf_org_name'];
		$lecture_movie_idx = $data['lecture_movie_idx'];			
    $display_yn = $data['display_yn'];	

		$this->db->trans_begin();

    $sql = "INSERT INTO
              tbl_electron_book
            (             
              site_code, 
              product_name, 
              author, 
              product_price,
              product_contents,           
              product_desc,           
              product_img_path,
              product_detail_img_path,
              pdf_url,
              pdf_org_name,
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
              'N',
              NOW(),
              NOW()
            )
    ";

    $this->query($sql,
                array(
                  $site_code,  
                  $product_name,  
                  $author,  
                  $product_price,
                  $product_contents,           
                  $product_desc,           
                  $product_img_path,
                  $product_detail_img_path,
                  $pdf_url,
                  $pdf_org_name,
                  $lecture_movie_idx,                  
                  $display_yn,
                ),
                $data);

    $electron_book_idx = $this->db->insert_id();

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

		$electron_book_idx = $data['electron_book_idx'];
    $site_code = $data['site_code'];
    $product_name = $data['product_name'];
    $author = $data['author'];
		$product_price = $data['product_price'];
		$product_contents = $data['product_contents'];		
		$product_desc = $data['product_desc'];		
		$product_img_path = $data['product_img_path'];
		$product_detail_img_path = $data['product_detail_img_path'];
		$pdf_url = $data['pdf_url'];
		$lecture_movie_idx = $data['lecture_movie_idx'];			
		$pdf_org_name = $data['pdf_org_name'];	
		$display_yn = $data['display_yn'];	

		$this->db->trans_begin();

    $sql = "UPDATE
              tbl_electron_book
            SET
              site_code=?,
              product_name=?,
              author=?,
              product_price=?,
              product_contents=?,          
              product_desc=?,          
              product_img_path=?,
              product_detail_img_path=?,
              pdf_url=?,
              pdf_org_name=?,
              lecture_movie_idx=?,              
              display_yn=?,
              upd_date=NOW()
            WHERE
              electron_book_idx= ?

    ";

    $this->query($sql,
                array(
                $site_code,
                $product_name,
                $author,
                $product_price,
                $product_contents,             
                $product_desc,             
                $product_img_path,
                $product_detail_img_path,
                $pdf_url,
                $pdf_org_name,
                $lecture_movie_idx,                
                $display_yn,              
                $electron_book_idx
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
		
    $electron_book_idx=$data['electron_book_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_electron_book
						SET
							del_yn = 'Y'
						WHERE
							electron_book_idx IN($electron_book_idx)
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

	
  
}	// 클래스의 끝

?>
