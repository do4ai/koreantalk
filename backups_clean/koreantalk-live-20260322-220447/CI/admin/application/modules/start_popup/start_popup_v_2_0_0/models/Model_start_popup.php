<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	-
| Create-Date : 2019-07-11
| Memo : 시작 팝업 관리
|------------------------------------------------------------------------
*/

Class Model_start_popup extends MY_Model {

	// 시작 팝업 리스트
	public function start_popup_list($data) {

		$page_size = (int)$data['page_size'];
    $page_no = (int)$data['page_no'];
		$title = $data['title'];
		$state = $data['state'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];

		$sql = "SELECT
							a.start_popup_idx,
							a.title,
							DATE_FORMAT(a.ins_date,'%Y.%m.%d') as  ins_date,
							a.state
						FROM
							tbl_start_popup a
						WHERE
							a.del_yn = 'N'
          	";

    if($title !=""){
      $sql .="AND a.title LIKE '%$title%' ";
    }
    if($state !=""){
      $sql .="AND a.state LIKE '%$state%' ";
    }
		if($s_date != ""){
			$sql .=" AND DATE_FORMAT(a.ins_date,'%Y-%m-%d') >= '$s_date'";
		}
		if($e_date != ""){
			$sql .=" AND DATE_FORMAT(a.ins_date,'%Y-%m-%d') <= '$e_date'";
		}

		$sql .= " ORDER BY a.start_popup_idx DESC limit ?,?";

  	return $this->query_result($sql,
                              array(
                              $page_no,
                              $page_size
                              ),
                              $data);
	}

  // 시작 팝업 리스트 총 카운트
  public function start_popup_list_count($data) {

		$title = $data['title'];
		$state = $data['state'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];

		$sql = "SELECT
							COUNT(1) cnt
						FROM
							tbl_start_popup a
						WHERE
							a.del_yn = 'N'
          	";

		if($title !=""){
      $sql .="AND a.title LIKE '%$title%' ";
    }
    if($state !=""){
      $sql .="AND a.state LIKE '%$state%' ";
    }
		if($s_date != ""){
			$sql .=" AND DATE_FORMAT(a.ins_date,'%Y-%m-%d') >= '$s_date'";
		}
		if($e_date != ""){
			$sql .=" AND DATE_FORMAT(a.ins_date,'%Y-%m-%d') <= '$e_date'";
		}

  	return $this->query_cnt($sql,
                            array(
                            ),
                            $data);
  }

  // 시작 팝업 상태 변경 
	public function start_popup_state_mod_up($data){

		$start_popup_idx  = $data['start_popup_idx'];
		$state = $data['state'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_start_popup
						SET
							state = ?,
							upd_date = NOW()
						WHERE
							start_popup_idx = ?
						";

		$this->query($sql,
                array(
                $state,
                $start_popup_idx
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

	// 시작 팝업 상세
	public function start_popup_detail($data) {
		
    $start_popup_idx = $data['start_popup_idx'];

		$sql = "SELECT
							a.start_popup_idx,
							a.title,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d') as  ins_date,
							a.img_path,
							a.link_url,
							a.state
						FROM
							tbl_start_popup a
						WHERE
							a.del_yn = 'N'
						AND a.start_popup_idx = ?
						";

		return $this->query_row($sql, 
                            array(
                            $start_popup_idx
                            ),
                            $data);
	}

	// 시작 팝업 수정 
  public function start_popup_mod_up($data){

		$start_popup_idx = $data['start_popup_idx'];
		$title = $data['title'];
		$img_url = $data['img_url'];
		$link_url = $data['link_url'];
		$state = $data['state'];

		$this->db->trans_begin();

			$sql = "UPDATE
								tbl_start_popup
							SET
								title = ?,
								img_url = ?,
								link_url = ?,
								state = ?
							WHERE
								start_popup_idx = ?
							";
			$this->query($sql,
                  array(
                  $title,
                  $img_url,
                  $link_url,
                  $state,
                  $start_popup_idx
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

	// 시작 팝업 등록 
  public function start_popup_reg_in($data){
		
    $title = $data['title'];
		$img_url = $data['img_url'];
		$link_url = $data['link_url'];
		$state = $data['state'];

		$this->db->trans_begin();

			$sql = "INSERT INTO
								tbl_start_popup
							(
								title,
								img_url,
								link_url,
								state,
								del_yn,
								ins_date,
								upd_date
							) VALUES (
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
                  $title,
                  $img_url,
                  $link_url,
                  $state
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

	// 시작 팝업 삭제 
	public function start_popup_del($data){

		$start_popup_idx = $data['start_popup_idx'];

		$this->db->trans_begin();

		$sql = " 	UPDATE
								tbl_start_popup
							SET
								del_yn = 'Y',
								upd_date = NOW()
							WHERE
								start_popup_idx IN ($start_popup_idx)
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
