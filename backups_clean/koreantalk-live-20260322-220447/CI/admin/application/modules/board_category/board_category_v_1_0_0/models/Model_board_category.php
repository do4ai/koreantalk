<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2021-11-03
| Memo : 게시판 카테고리
|------------------------------------------------------------------------
*/

Class Model_board_category extends MY_Model{

	//  리스트
	public function board_category_list($data){
		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$category_name = $data['category_name'];
		$s_date=$data['s_date'];
		$e_date=$data['e_date'];

    $sql = "SELECT
							a.board_category_idx,
							a.category_name,
							ifnull((select count(*) from tbl_board where del_yn='N' and category=a.board_category_idx),0) as board_cnt,
							a.ins_date
						FROM
							tbl_board_category a				
						WHERE
							a.del_yn = 'N'
		";

		if($category_name != ""){
			$sql .= " AND a.category_name LIKE '%$category_name%' ";
		}
		if($s_date !=""){
			$sql .="AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date !=""){
			$sql .="AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		$sql .=" ORDER BY board_category_idx DESC LIMIT ?, ? ";

		return $this->query_result($sql,
															 array(
															 $page_no,
															 $page_size
															 ),
															 $data);
	}

	// 리스트 총 카운트
	public function board_category_list_count($data){

		$category_name = $data['category_name'];
		$s_date=$data['s_date'];
		$e_date=$data['e_date'];

		$sql = "SELECT
							COUNT(*) AS cnt
							FROM
							tbl_board_category a				
						WHERE
							a.del_yn = 'N'
		";

		if($category_name != ""){
			$sql .= " AND a.category_name LIKE '%$category_name%' ";
		}
		if($s_date !=""){
			$sql .="AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date !=""){
			$sql .="AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		return $this->query_cnt($sql,
														array(
														),
														$data);
	}


	
	// 메인 상세
	public function board_category_detail($data){
	  $board_category_idx = $data['board_category_idx'];

	  $sql="SELECT
            a.board_category_idx,
						a.category_name,
						ifnull((select count(*) from tbl_board where del_yn='N' and category=a.board_category_idx),0) as board_cnt,
						a.ins_date
          FROM
            tbl_board_category as a
          WHERE
            board_category_idx = ?
						AND del_yn = 'N'
    ";

    return	$this->query_row($sql,
														array(
														$board_category_idx
														),
														$data
														);

	}

	//추가
	public function board_category_reg_in($data){
		$category_name = $data['category_name'];

		$sql = "INSERT INTO
							tbl_board_category
							(
								category_name,
								del_yn,			
								ins_date,
								upd_date
							) VALUES (
								?,
					      'N', 
								NOW(),
								NOW()
							)
		";

		$this->query($sql,
								array(
								$category_name,
								),
								$data
								);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return "1";
		}

	}

	// 수정
	public function board_category_mod_up($data){
		$board_category_idx = $data['board_category_idx'];
		$category_name = $data['category_name'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board_category
						SET
						  category_name         = ?,
							upd_date      = NOW()
						WHERE
							board_category_idx = ?
					";

		$this->query($sql,
								 array(
									$category_name,							
									$board_category_idx
								),
								$data
								);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}

	}


	// 삭제
	public function board_category_del($data){
		$board_category_idx = $data['board_category_idx'];

		$this->db->trans_begin();

		$sql = " 	UPDATE
									tbl_board_category
							SET
									del_yn = 'Y',
									upd_date = NOW()
							WHERE
									board_category_idx IN ($board_category_idx)
		";
		
		$this->query($sql,
								array(

								),
								$data
								);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}

	}


	// 상태변경
	public function board_category_state_mod_up($data){
		$board_category_idx = $data['board_category_idx'];
		$board_category_state = $data['board_category_state'];

		$this->db->trans_begin();

		$sql = " 	UPDATE
									tbl_board_category
							SET
									board_category_state =?,
									upd_date = NOW()
							WHERE
									board_category_idx =?
						";
		$this->query($sql,
									array(
	                $board_category_state,
	                $board_category_idx
									),
									$data
								);

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
