<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 메인
|------------------------------------------------------------------------
*/

Class Model_main extends MY_Model {

	public function main_detail() {
		$result = array();

		//@@ 배너 
		$sql = "SELECT
							banner_idx,
							title,							
							img_path,					
							link_url
						FROM
							tbl_banner 
						WHERE	del_yn='N'
							AND state=1	
              AND site_code = ?
							ORDER BY ins_date ASC
		";

		$result['banner_list']=$this->query_result($sql,array($this->current_lang));

		//@@ 01 - 상품
		$sql = "SELECT 	
              product_idx, 	 
              product_name, 
              product_price, 
              product_img_path, 
              display_yn	 
            FROM 
            tbl_product AS a
            WHERE del_yn='N'
            AND display_yn='Y'
            ORDER BY RAND() LIMIT 5				
		";
		$result['product_list']=$this->query_result($sql,array());

		//@@ 02 - 전자책
		$sql = "SELECT 	
              electron_book_idx, 	 
              product_name, 
              product_price, 
              product_img_path, 
              display_yn	 
            FROM 
            tbl_electron_book AS a
            WHERE del_yn='N'
            AND display_yn='Y'
            AND site_code='$this->current_lang'
            ORDER BY RAND() LIMIT 5		
		";
		$result['electron_book_list']=$this->query_result($sql,array());

    //@@ 03 - 추전topik 1개(main_view_yn ,랜덤1)
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
            JOIN tbl_lecture AS b ON b.lecture_idx=a.lecture_idx AND b.del_yn='N' and b.site_code='$this->current_lang'
            WHERE a.del_yn='N'
            AND a.main_view_yn='Y'
            
            ORDER BY RAND() LIMIT 1
		";
		$result['lecture_list']=$this->query_result($sql,array());

    //@@ 04 - notice
    $sql = "SELECT 	
              notice_idx, 
              img_path,	 
              title, 
              ins_date	 
            FROM 
            tbl_notice AS a
            WHERE del_yn='N'
            AND display_yn='Y'
            ORDER BY notice_idx desc LIMIT 1		
		";
		$result['notice_list']=$this->query_result($sql,array());

		return $result;
	}



  //회원 상세 보기
  public function member_detail(){

    $sql = "SELECT
							a.member_idx,
							FN_AES_DECRYPT(a.member_id) AS member_id,
							FN_AES_DECRYPT(a.member_name) AS member_name,
							FN_AES_DECRYPT(a.member_phone) AS member_phone,
							a.del_yn,
							a.ins_date,
							a.upd_date
						FROM
							tbl_member a
						WHERE
							a.member_idx = ?
          ";
    return $this->query_row($sql,
                            array(
                            $this->member_idx
                            ));
  }



}	// 클래스의 끝
?>
