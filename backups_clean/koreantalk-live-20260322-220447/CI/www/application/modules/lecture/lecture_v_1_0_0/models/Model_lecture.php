<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	
| Create-Date : 2023-07-21
| Memo : 공지사항
|------------------------------------------------------------------------
*/

class Model_lecture extends MY_Model{
  // 리스트
  public function lecture_movie_list_get($data){
    $lecture_category_idx=$data['lecture_category_idx'];

    $sql = "SELECT
              a.lecture_movie_idx,
              a.movie_name,
              a.movie_time,
              a.youtube_id,
              a.del_yn,
              DATE_FORMAT(ins_date, '%Y-%m-%d') as  ins_date
            FROM
              tbl_lecture_movie a
            WHERE
              a.del_yn = 'N'
              and a.lecture_category_idx=?
            ORDER BY order_no asc  
              
    ";

    return $this->query_result($sql,
                              array(
                                $lecture_category_idx,                              
                              ),$data
                            );
  }

  	// 6.  영상보기
	public function member_watched_movie_reg_in($data){

		$lecture_movie_idx = $data['lecture_movie_idx'];

		$this->db->trans_begin();

		$sql = "INSERT INTO tbl_member_watched_movie
						(
						member_idx,
						lecture_movie_idx,						
						ins_date,
						upd_date
						)
						VALUES
						(
						?,
						?,						
						NOW(),
						NOW()
						)
						ON DUPLICATE KEY UPDATE member_idx=?,lecture_movie_idx=?,upd_date=NOW()
		";

		$this->query($sql,array(
									$this->member_idx,
									$lecture_movie_idx,
									$this->member_idx,
									$lecture_movie_idx,
								 ),$data
							 );

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1000";
		}

	}


  // 리스트
  public function lecture_detail($data){
    $lecture_idx=$data['lecture_idx'];

    $sql = "SELECT
              a.lecture_idx,
              a.lecture_name,
              a.img_path,
              a.contents,
              a.electron_book_idx,
              a.del_yn,
              a.ins_date
            FROM
              tbl_lecture a
            WHERE
              a.del_yn = 'N'
              and lecture_idx=?
    ";

    $rt['result']= $this->query_row($sql,
                              array(
                                $lecture_idx
                              ),$data
                            );

    $sql = "SELECT
              electron_book_idx,
              author,
              a.product_name,
              a.author,
              a.product_img_path,
              a.product_price,       
              a.product_contents,  
              a.ins_date
            FROM
              tbl_electron_book a
            WHERE
              a.del_yn = 'N'
              and a.display_yn='Y'
              and electron_book_idx=?
    ";

    $rt['ebook_info']= $this->query_row($sql,
                              array(
                                $rt['result']->electron_book_idx
                              ),$data
                            );                        

    $sql = "SELECT
              a.lecture_category_idx,
              a.category_name,
              a.del_yn,
              a.ins_date
            FROM
              tbl_lecture_category a
            WHERE
              a.del_yn = 'N'
              and lecture_idx=?
    ";

    $rt['result_list']= $this->query_result($sql,
                              array(
                                $lecture_idx
                              ),$data
                            ); 
    return $rt;                                                 
  }





} // 클래스의 끝
?>
