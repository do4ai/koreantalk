<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2018-11-05
| Memo : 카테고리 관리
|------------------------------------------------------------------------
*/

Class Model_category_management extends MY_Model{

  // 카테고리 목록
	public function category_management_list($data){
	
  	$type = $data["type"];
		$where_condition = array();
	
  	$sql = "SELECT
							category_management_idx,
							parent_category_management_idx,
							category_depth,
							type,
							state,
							category_name
						FROM
							tbl_category_management
						WHERE
							del_yn = 'N'
						";
		if($type != ""){
			$sql .= " AND type = ? ";
			array_push($where_condition, $type);
		}
		$sql .= "ORDER BY order_no ASC";

		$result = $this->query_result($sql, $where_condition);

		return $result;
	}

  // 카테고리 등록
	public function category_management_reg_in($data){
		
    $type = $data['type'];
		$category_name = $data['category_name'];

		$sql = "SELECT
							category_name
						FROM
							tbl_category_management
						WHERE
							category_name = ?
							AND type = ?
						";

		$result = $this->query_result($sql, 
                                  array(
                                  $category_name,
                                  $type
                                  ),
                                  $data);

		if( count($result) > 0 ){
			return "0";
		}

		$sql = "SELECT
							MAX(order_no) as cnt
						FROM
							tbl_category_management
						WHERE
						  type = ?
					 ";

		$last_order_no = $this->query_cnt($sql, 
                                      array(
                                      $type
                                      ),
                                      $data);
		$this->db->trans_begin();

		$sql="INSERT INTO
						tbl_category_management
					(
						type,
						category_name,
						order_no,
						del_yn,
						ins_date,
						upd_date
					)VALUES(
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
                $type,
                $category_name,
                $last_order_no+1
                ),
                $data);

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$insert_id = $this->db->insert_id();
			$this->db->trans_commit();
		}

		return $insert_id;
	}

  // 카테고리 삭제
	public function category_management_del($data){
	
  	$category_management_idx = $data['category_management_idx'];

		$this->db->trans_begin();

		$sql="UPDATE
						tbl_category_management
					SET
						del_yn = 'Y'
					WHERE
						category_management_idx = ?
					";

		$this->query($sql,
                array(
                $category_management_idx
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

  // 카테고리 수정
	public function category_management_mod_up($data){

		$category_management_idx = $data['category_management_idx'];
		$category_name = $data['category_name'];

		$this->db->trans_begin();

		$sql="UPDATE
						tbl_category_management
					SET
						category_name = ?
					WHERE
						category_management_idx = ?
					";

		$this->query($sql,
                array(
                $category_name,
                $category_management_idx
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

  // 카테고리  활성 / 활성화
	public function category_state_up($data){

		$category_management_idx = $data['category_management_idx'];

		$this->db->trans_begin();

		$sql="UPDATE
						tbl_category_management
					SET 
            state= IF(state = 0,'1','0')
					WHERE
						category_management_idx = ?
					";

		$this->query($sql,
                array(
                $category_management_idx
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

  // 카테고리 순서 변경
	public function category_order_set($data){

		$first_cate_list_idx = $data['first_cate_list_idx'];
		$second_cate_list_idx = $data["second_cate_list_idx"];
		$third_cate_list_idx = $data["third_cate_list_idx"];
		$select_depth = $data["select_depth"];
		$parent_category_management_idx = $data["parent_category_management_idx"];

		$this->db->trans_begin();
		if($select_depth == 0){
			for ($i=0; $i < count($first_cate_list_idx) ; $i++) {
				$sql="UPDATE
								tbl_category_management
							SET
								order_no = ?
							WHERE
								category_management_idx = ?
								AND type = ?
							";

				$this->query($sql,
                    array(
                    $i+1,
                    $first_cate_list_idx[$i],
                    $select_depth
                    ),
                    $data);
			}
	  }else if($select_depth == 1){
			for ($i=0; $i < count($second_cate_list_idx) ; $i++) {
				$sql="UPDATE
								tbl_category_management
							SET
								order_no = ?
							WHERE
								category_management_idx = ?
								AND type = ?
							";

				$this->query($sql,
                    array(
                    $i+1,
                    $second_cate_list_idx[$i],
                    $select_depth
                    ),
                    $data);
			}
		}else if($select_depth == 2){
			for ($i=0; $i < count($third_cate_list_idx) ; $i++) {
				$sql="UPDATE
								tbl_category_management
							SET
								order_no = ?
							WHERE
								category_management_idx = ?
								AND type = ?
							";

				$this->query($sql,
                    array(
                    $i+1,
                    $third_cate_list_idx[$i],
                    $select_depth
                    ),
                    $data);
			}
		}
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
