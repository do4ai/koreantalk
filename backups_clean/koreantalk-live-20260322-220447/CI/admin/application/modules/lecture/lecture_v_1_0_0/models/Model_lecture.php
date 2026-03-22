<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2022-09-14
| Memo :  관리
|------------------------------------------------------------------------
*/

Class Model_lecture extends MY_Model{

	//  리스트
	public function lecture_list($data){
  
		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$lecture_name 		 = $data['lecture_name'];
		$s_date 	 = $data['s_date'];
		$e_date 	 = $data['e_date'];
		$site_code 	 = $data['site_code'];

		$sql = "SELECT
							lecture_idx,
							lecture_name,
							a.display_yn,				
							a.site_code,
							b.site_name,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') as  ins_date,
              ifnull((select count(*) from tbl_lecture_movie where del_yn='N' and lecture_idx=a.lecture_idx ),0) as movie_cnt
						FROM
							tbl_lecture as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn = 'N'
						";

		if($lecture_name != ""){
			$sql .= " AND lecture_name LIKE '%$lecture_name%' ";
		}
		if($site_code != ""){
			$sql .= " AND a.site_code = '$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		$sql .=" ORDER BY lecture_idx DESC LIMIT ?, ? ";

		return $this->query_result($sql,
                              array(
                              $page_no,
                              $page_size
                              ),
                              $data);
	}

	//  리스트 총 카운트
	public function lecture_list_count($data){

		$lecture_name 		 = $data['lecture_name'];
		$s_date 	 = $data['s_date'];
		$e_date 	 = $data['e_date'];
		$site_code 	 = $data['site_code'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_lecture as a
               join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn = 'N'
						";

		if($lecture_name != ""){
			$sql .= " AND lecture_name LIKE '%$lecture_name%' ";
		}
		if($site_code != ""){
			$sql .= " AND a.site_code = '$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		return $this->query_cnt($sql,
                            array(
                            )
                            );
	}

	//  등록
	public function lecture_reg_in($data){

		$lecture_name = $data['lecture_name'];
		$contents = $data['contents'];
		$site_code = $data['site_code'];
    $display_yn = $data['display_yn'];
    $img_path = $data['img_path'];
    $electron_book_idx = $data['electron_book_idx'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_lecture
						(
							lecture_name,
							contents,
							site_code,
              display_yn,
              img_path,
              electron_book_idx,
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
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
                array(
                $lecture_name,
                $contents,
                $site_code,
                $display_yn,
                $img_path,
                $electron_book_idx,
                ),
                $data);

		$lecture_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return $lecture_idx;
		}
	}

	//  상세
	public function lecture_detail($data){

		$lecture_idx = $data['lecture_idx'];

		$sql = "SELECT
	          	lecture_idx,
							lecture_name,
							contents,
							img_path,
							a.electron_book_idx,
							a.site_code,
              b.site_name,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') AS ins_date,
							DATE_FORMAT(a.upd_date,'%Y-%m-%d') AS upd_date,
							a.display_yn							
	        	FROM
	          	tbl_lecture as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
	        	WHERE
	           	lecture_idx = ?
							AND a.del_yn = 'N'
					";

   		return $this->query_row($sql,
                              array(
                              $lecture_idx
                              ),
                              $data);
	}

	//  수정
	public function lecture_mod_up($data){

		$lecture_idx = $data['lecture_idx'];
		$lecture_name = $data['lecture_name'];
		$contents = $data['contents'];
		$site_code = $data['site_code'];
    $display_yn = $data['display_yn'];
    $img_path = $data['img_path'];
    $electron_book_idx = $data['electron_book_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_lecture
						SET
							lecture_name = ?,
							contents = ?,
							img_path = ?,
							site_code=?,
              display_yn=?,
              electron_book_idx=?,
							upd_date = NOW()
						WHERE
							lecture_idx = ?
						";

		$this->query($sql,
                array(
                $lecture_name,
                $contents,
                $img_path,
                $site_code,
                $display_yn,
                $electron_book_idx,
                $lecture_idx
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

	//  상태 변경
	public function lecture_state_mod_up($data){

		$lecture_idx  = $data['lecture_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_lecture
						SET
							lecture_state = if(display_yn='Y','N','Y'),
							upd_date = NOW()
						WHERE
							lecture_idx = ?
						";

		$this->query($sql,
                array(        
                $lecture_idx
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

	//  삭제
	public function lecture_del($data){

		$lecture_idx = $data['lecture_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_lecture
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							lecture_idx IN ($lecture_idx)
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

	//  상세
	public function lecture_movie_list($data){

		$lecture_idx = $data['lecture_idx'];

		$sql = "SELECT
	          	lecture_category_idx,
							category_name							
	        	FROM
	          	tbl_lecture_category as a         
	        	WHERE
	           	lecture_idx = ?
							AND a.del_yn = 'N'
					";

   	$rt['result_list'] = $this->query_result($sql,
                                            array(
                                            $lecture_idx
                                            ),
                                            $data);
		$sql = "SELECT
	          	lecture_movie_idx,
	          	lecture_idx,
	          	lecture_category_idx,
							movie_name,							
							movie_time,							
							movie_url,							
							main_view_yn,							
							youtube_id							
	        	FROM
	          	tbl_lecture_movie as a         
	        	WHERE
	           	lecture_idx = ?
							AND a.del_yn = 'N'
              order by order_no asc, lecture_movie_idx asc
					";

   	$rt['movie_list'] = $this->query_result($sql,
                                            array(
                                            $lecture_idx
                                            ),
                                            $data); 
    return $rt;    
	}



  //  등록
	public function lecture_category_reg_in($data){

		$lecture_idx = $data['lecture_idx'];
		$category_name = $data['category_name'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_lecture_category
						(
							lecture_idx,
							category_name,
							del_yn,
							ins_date,
							upd_date
						) VALUES (
							?, 
							?, 
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
                array(
                $lecture_idx,
                $category_name,
                ),
                $data);

		$lecture_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return $lecture_idx;
		}
	}


  //  삭제
	public function lecture_category_del($data){

		$lecture_category_idx = $data['lecture_category_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_lecture_category
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							lecture_category_idx IN ($lecture_category_idx)
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


  //  영상등록
	public function lecture_movie_reg_in($data){

		$lecture_idx = $data['lecture_idx'];
		$lecture_category_idx = $data['lecture_category_idx'];
		$movie_name = $data['movie_name'];
		$movie_time = $data['movie_time'];
		$movie_url = $data['movie_url'];
		$youtube_id = $data['youtube_id'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_lecture_movie
						(
							lecture_idx,
							lecture_category_idx,
							movie_name,
							movie_time,
							movie_url,
							youtube_id,
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
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
                array(
                $lecture_idx,
                $lecture_category_idx,
                $movie_name,
                $movie_time,
                $movie_url,
                $youtube_id,
                ),
                $data);

		$lecture_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return $lecture_idx;
		}
	}
 
  //수정
  public function lecture_movie_mod_up($data){

		$lecture_movie_idx = $data['lecture_movie_idx'];
    $movie_name = $data['movie_name'];
		$movie_time = $data['movie_time'];
		$movie_url = $data['movie_url'];
		$youtube_id = $data['youtube_id'];

    $data_arr = explode(",",$lecture_movie_idx);

		$this->db->trans_begin(); 

    $sql = "UPDATE
              tbl_lecture_movie
            SET
              movie_name = ?,
              movie_time = ?,
              movie_url = ?,
              youtube_id = ?,
              upd_date = NOW()
            WHERE
              lecture_movie_idx =?
            ";

    $this->query($sql,
                array(
                $movie_name,
                $movie_time,
                $movie_url,
                $youtube_id,
                $lecture_movie_idx,
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

  // 순서 정렬
	public function lecture_movie_order_no_mod_up($data){

		$lecture_movie_idx = $data['lecture_movie_idx'];

    $data_arr = explode(",",$lecture_movie_idx);

		$this->db->trans_begin();

    for($i=0;$i<count($data_arr);$i++){
      $order_no =$i;
      $lecture_movie_idx =$data_arr[$i];

      $sql = "UPDATE
                tbl_lecture_movie
              SET
                order_no = ?,
                upd_date = NOW()
              WHERE
                lecture_movie_idx =?
              ";

      $this->query($sql,
                  array(
                  $order_no,
                  $lecture_movie_idx,
                  ),
                  $data);
    }            

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		  return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
	}

  //  추천
	public function lecture_movie_main_view_yn_mod_up($data){

		$lecture_movie_idx = $data['lecture_movie_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_lecture_movie
						SET
							main_view_yn = if(main_view_yn='Y','N','Y'),
							upd_date = NOW()
						WHERE
							lecture_movie_idx IN ($lecture_movie_idx)
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



  //  삭제
	public function lecture_movie_del($data){

		$lecture_movie_idx = $data['lecture_movie_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_lecture_movie
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							lecture_movie_idx IN ($lecture_movie_idx)
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




}	//클래스의 끝
?>
