<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 배너관리
|------------------------------------------------------------------------
*/

Class Model_banner extends MY_Model {

  //배너 리스트 
	public function banner_list($data) {  
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$title = $data['title'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$state = $data['state'];
		$site_code = $data['site_code'];

		$sql = "SELECT
							banner_idx,
							title,
              a.site_code,
              b.site_name,
							contents,
							state,
							a.ins_date
						FROM
							tbl_banner as a 
               join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn = 'N'							
		";
		if($title !=""){
			$sql .= " AND title  LIKE '%$title%' ";
		}
		if($state !=""){
			$sql .= " AND state  = '$state' ";
		}
  	if($site_code !=""){
			$sql .= " AND a.site_code  = '$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}

		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}
		$sql.=" ORDER BY ins_date DESC limit ?, ?";

	  return	$this->query_result($sql
																,array(
																$page_no,
																$page_size
																),
																$data);

	}

  //배너 리스트 총 카운트
	public function banner_list_count($data) {

		$title = $data['title'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$state = $data['state'];
		$site_code = $data['site_code'];

		$sql = "SELECT
					    count(*) as cnt
						FROM
							tbl_banner as a 
               join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn = 'N'							
		";
		if($title !=""){
			$sql .= " AND title  LIKE '%$title%' ";
		}
		if($state !=""){
			$sql .= " AND state  = '$state' ";
		}
  	if($site_code !=""){
			$sql .= " AND a.site_code  = '$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}

		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		return $this->query_cnt($sql,
														array(
														),
                            $data);

	}

	// 배너 상세
	public function banner_detail($data){
	  $banner_idx = $data['banner_idx'];

	  $sql="SELECT
          	banner_idx,
						banner_type,
						title,
						site_code,
						contents,
						banner_s_date,
						banner_e_date,
						img_path,
						link_url,
						state
          FROM
            tbl_banner
          WHERE
            banner_idx = ?
						AND del_yn = 'N'
        ";

    return	$this->query_row($sql,
														array(
														$banner_idx
														),
														$data);

	}

	//배너 등록
	public function banner_reg_in($data){
  
		$title = $data['title'];
		$contents = $data['contents'];
		$link_url = $data['link_url'];
		$img_path = $data['img_path'];
	  $state = $data['state'];
	  $banner_type = $data['banner_type'];
    $site_code = $data['site_code'];

		$sql = "INSERT INTO
							tbl_banner
							(
              	title,
								contents,
								link_url,
								img_path,
								state,
                site_code,				
								ins_date,
								upd_date
							) VALUES (								
                ?, 
								?,
								?,
								?,
								?,
								?,								
								NOW(),
								NOW()
							)
					";

		$this->query($sql,
								array(
              	$title,
								$contents,
								$link_url,
								$img_path,
								$state,
								$site_code,
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

	// 배너 이미지 수정
	public function banner_mod_up($data){

		$banner_idx = $data['banner_idx'];
		$title = $data['title'];
		$contents = $data['contents'];
		$link_url = $data['link_url'];
		$img_path = $data['img_path'];
		$img_width = $data['img_width'];
		$img_height = $data['img_height'];
		$state = $data['state'];
    $site_code = $data['site_code'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_banner
						SET
							title = ?,
							contents = ?,
							link_url = ?,
							img_path = ?,
							site_code = ?,
							state = ?,
							upd_date = NOW()
						WHERE
							banner_idx = ?
					";

		$this->query($sql,
                array(
                $title,
                $contents,
                $link_url,
                $img_path,
                $site_code,
                $state,
                $banner_idx
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

	// 배너 삭제
	public function banner_del($data){

		$banner_idx = $data['banner_idx'];

		$this->db->trans_begin();

		$sql = " 	UPDATE
								tbl_banner
							SET
								del_yn = 'Y',
								upd_date = NOW()
							WHERE
								banner_idx IN ($banner_idx)
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

}
?>
