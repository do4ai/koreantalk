<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 김옥훈
| Create-Date : 2016-02-29
| Memo : 공통 기능
|------------------------------------------------------------------------
*/


Class Model_common extends MY_Model {

  //게시판 카테고리  
	public function board_category_list() {

		$sql = "SELECT
							board_category_idx,
							category_name
						FROM
							tbl_board_category
						WHERE
							del_yn = 'N'
						order by board_category_idx asc
		";

		return	$this->query_result($sql,array());
	}


  //게시판 카테고리  상세
	public function board_category_detail($data) {
    
    $board_category_idx =$data['board_category_idx'];

		$sql = "SELECT
							board_category_idx,
							category_name,
							category_name_eng
						FROM
							tbl_board_category
						WHERE
							del_yn = 'N'
              and board_category_idx='$board_category_idx'
						
		";

		return	$this->query_row($sql,array());
	}


  //영상 리스트  
	public function lecture_movie_list() {

		$sql = "SELECT 
              a.lecture_movie_idx,
              a.lecture_idx,
              a.lecture_category_idx,
              a.movie_name,
              a.movie_time,
              b.category_name,
              c.lecture_name
            FROM tbl_lecture_movie AS a
              JOIN tbl_lecture_category AS b ON b.lecture_category_idx=a.lecture_category_idx AND b.del_yn='N'
              JOIN tbl_lecture AS c ON c.lecture_idx=a.lecture_idx AND c.del_yn='N'
            WHERE a.del_yn='N'
            ORDER BY  c.lecture_name , b.category_name,a.movie_name
		";

		return	$this->query_result($sql,array());
	}


	//영상 카테고리 리스트  
	public function lecture_category_list() {

		$sql = "SELECT 
					ta.lecture_idx,
					ta.lecture_category_idx,
					ta.cnt,
					tb.category_name				
				FROM (				
					SELECT 
					lecture_idx,
					lecture_category_idx,
					COUNT(*) AS cnt
					FROM tbl_lecture_movie
					WHERE del_yn='N'
					GROUP BY  lecture_idx,lecture_category_idx
				)AS ta
				JOIN tbl_lecture_category AS tb ON tb.lecture_category_idx =ta.lecture_category_idx  AND tb.del_yn='N'
				WHERE 1=1
		";

		return	$this->query_result($sql,array());
	}


	//영상 카테고리 리스트  
	public function lecture_list() {

		$sql = "SELECT 
							a.lecture_idx,
							a.lecture_name,
							(select lecture_category_idx from tbl_lecture_category where lecture_idx = a.lecture_idx and del_yn ='N' order by lecture_category_idx ASC limit 1 ) as lecture_category_idx
						FROM 
							tbl_lecture as a
						WHERE 
							a.del_yn='N'
							AND a.display_yn='Y'
							AND a.site_code='$this->current_lang'		
			";

		return	$this->query_result($sql,array());
	}

	
	//책 리스트  
	public function book_list() {

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
    $sql .="ORDER BY product_idx DESC ";

    return $this->query_result($sql,
                              array(
                              )
                            );
	}

	//전자책 리스트  
	public function electron_book_list() {

    $sql = "SELECT
              a.electron_book_idx,
              a.product_name,
              a.author,
              a.product_img_path,
              a.product_price,       
              a.product_desc as product_contents,
              a.del_yn,
              DATE_FORMAT(ins_date, '%Y-%m-%d') as  ins_date
            FROM
              tbl_electron_book a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              and a.site_code='$this->current_lang'
              
    ";
    $sql .="ORDER BY electron_book_idx DESC";

    return $this->query_result($sql,
                              array(
                              )
                            );
	}
	
	
	
	

}
?>
