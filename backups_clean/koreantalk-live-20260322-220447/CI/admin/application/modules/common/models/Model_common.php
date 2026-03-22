<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 공통 영역 관리
|------------------------------------------------------------------------   
*/

Class Model_common extends MY_Model {

	// 지역 시도 리스트
	public function city_list() {

		$sql = "SELECT
							city_cd,
							city_name,
							id_cd
						FROM
							tbl_city_cd
						ORDER BY order_no ASC
				  ";

		return $this->query_result($sql,
                              array(
                              )
                              );
	}

	// 구군 리스트
	public function region_list($data) {

		$city_cd = $data['city_cd'];

		$sql = "SELECT
							region_cd,
							region_name,
							city_cd
						FROM
							tbl_region_cd
						WHERE
							city_cd = ?
						ORDER BY order_no ASC
				  ";

		return $this->query_result($sql,
                              array(
                              $city_cd
                              ),
                              $data);

	}

  // smtp 조회
  public function smtp_detail2(){

    $sql = "SELECT
              smtp_email_idx,
              smtp_host,
              smtp_user,
              smtp_pass,
              smtp_port,
              from_email,
              from_name
            FROM
              tbl_smtp_email
            WHERE
              del_yn = 'N'
            ORDER BY last_send_date ASC,smtp_email_idx ASC LIMIT 1
           ";

  return $this->query_row($sql,
                          array(
                          )
                          );
  }

  // 마지막 stmp 발송일 수정
  public function smtp_last_date_mod_up($data){

		$smtp_email_idx = $data['smtp_email_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_smtp_email
						SET
              send_cnt = CASE WHEN DATE_FORMAT(last_send_date,'%Y%m%d') <> DATE_FORMAT(NOW(),'%Y%m%d') THEN 1 ELSE send_cnt+1 END,
              last_send_date	= NOW(),
							upd_date = NOW()
						WHERE
							smtp_email_idx = ?
						";

		$this->query($sql,
                array(
                $smtp_email_idx
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

  //사이트 구분 
	public function site_list() {

		$sql = "SELECT
							site_code,
							site_name
						FROM
							tbl_site
						WHERE
							del_yn = 'N'
						order by site_idx asc
		";

		return	$this->query_result($sql,array());
	}

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
							category_name
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


  
  // 전자책 리스트  
	public function electron_book_list() {

		$sql = " SELECT 	
              electron_book_idx,
              product_name, 
              author, 
              product_price, 	
              buy_cnt	 
            FROM 
            tbl_electron_book AS a
            WHERE del_yn='N'
            AND a.display_yn='Y'
		";

		return	$this->query_result($sql,array());
	}
	
	



}
?>
