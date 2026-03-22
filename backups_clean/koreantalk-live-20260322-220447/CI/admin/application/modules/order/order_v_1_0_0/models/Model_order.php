<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2022-12-11
| Memo : 주문 관리
|------------------------------------------------------------------------
*/

Class Model_order extends MY_Model {

	// 주문 리스트 가져오기
	public function order_list($data) {
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$site_code = $data['site_code'];
		$order_id = $data['order_id'];
		$order_name = $data['order_name'];
		$order_number = $data['order_number'];
		$product_name = $data['product_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$order_state = $data['order_state'];	

		$sql = "SELECT
							order_idx,							
							order_number,
              member_idx,
							FN_AES_DECRYPT(order_id) AS order_id,
							FN_AES_DECRYPT(order_name) AS order_name,
							FN_AES_DECRYPT(order_phone) AS order_phone,
							order_state,
							product_name,
              product_img_path,
              product_price,
							a.site_code,
							b.site_name,						
							a.ins_date
						FROM
							tbl_order  as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn ='N'
						";

		if($order_id != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_id) LIKE '%$order_id%' ";
		}
		if($order_name != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_name) LIKE '%$order_name%' ";
		}
		if($order_number != ""){
  		$sql .= " AND order_number LIKE '%$order_number%' ";
		}
    if($product_name != ""){
  		$sql .= " AND product_name LIKE '%$product_name%' ";
		}
    if($order_state != ""){
  		$sql .= " AND a.order_state IN ($order_state) ";
		}
		if($site_code != ""){
  		$sql .= " AND a.site_code ='$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}

		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		$sql .= " ORDER BY a.ins_date DESC LIMIT ?, ? ";

		return $this->query_result($sql,
															 array(
															 $page_no,
															 $page_size
															 ),
															 $data);
	}

	// 주문 리스트 엑셀
	public function order_list_excel($data) {

		$corp_id = $data['corp_id'];
		$corp_name = $data['corp_name'];
		$order_id = $data['order_id'];
		$order_name = $data['order_name'];
		$order_corp_name = $data['order_corp_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$order_state = $data['order_state'];	
		$order_type = $data['order_type'];	

		$sql = "SELECT
							order_idx,
							order_number,
							FN_AES_DECRYPT(order_id) AS order_id,
							FN_AES_DECRYPT(order_name) AS order_name,
							order_corp_name,
							FN_AES_DECRYPT(corp_id) AS corp_id,
							corp_name,
							order_state,
							order_type,
							request_type,
							receiver_addr,
							receiver_addr_detail,
							order_state,
							state_date_0,
							ins_date
						FROM
							tbl_order a
						WHERE
							a.del_yn ='N'
						";

		if($corp_id != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.corp_id) LIKE '%$corp_id%' ";
		}
		if($corp_name != ""){
  		$sql .= " AND corp_name LIKE '%$corp_name%' ";
		}
		if($order_id != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_id) LIKE '%$order_id%' ";
		}
		if($order_name != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_name) LIKE '%$order_name%' ";
		}
		if($order_corp_name != ""){
  		$sql .= " AND order_corp_name LIKE '%$order_corp_name%' ";
		}
		if($order_state != ""){
  		$sql .= " AND a.order_state IN ($order_state) ";
		}
		//request_type
		if($order_type != ""){
  		$sql .= " AND a.order_type = '$order_type' ";
		}

		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}

		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}

		$sql .= " ORDER BY a.ins_date";

		return $this->query_result($sql,
															 array(
															 ),
															 $data);
	}

	// 주문 리스트 가져오기
	public function order_list_sum($data) {
		$site_code = $data['site_code'];
		$order_id = $data['order_id'];
		$order_name = $data['order_name'];
		$order_number = $data['order_number'];
		$product_name = $data['product_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$order_state = $data['order_state'];	

		$sql = "SELECT
							sum(product_price) as total
						FROM
							tbl_order  as a
            
						WHERE
							a.del_yn ='N'
						";

		if($order_id != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_id) LIKE '%$order_id%' ";
		}
		if($order_name != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_name) LIKE '%$order_name%' ";
		}
		if($order_number != ""){
  		$sql .= " AND order_number LIKE '%$order_number%' ";
		}
    if($product_name != ""){
  		$sql .= " AND product_name LIKE '%$product_name%' ";
		}
    if($order_state != ""){
  		$sql .= " AND a.order_state IN ($order_state) ";
		}
		if($site_code != ""){
  		$sql .= " AND a.site_code ='$site_code' ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}

		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}	

		return $this->query_row($sql,
															 array(
														
															 ),
															 $data);
	}

	// 주문 리스트 총 카운트
	public function order_list_count($data) {
		$site_code = $data['site_code'];
		$order_id = $data['order_id'];
		$order_name = $data['order_name'];
		$order_number = $data['order_number'];
		$product_name = $data['product_name'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$order_state = $data['order_state'];	

		$sql = "SELECT
							COUNT(1) AS cnt
						FROM
							tbl_order  as a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn ='N'
						";

		if($order_id != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_id) LIKE '%$order_id%' ";
		}
		if($order_name != ""){
  		$sql .= " AND FN_AES_DECRYPT(a.order_name) LIKE '%$order_name%' ";
		}
		if($order_number != ""){
  		$sql .= " AND order_number LIKE '%$order_number%' ";
		}
    if($product_name != ""){
  		$sql .= " AND product_name LIKE '%$product_name%' ";
		}
    if($order_state != ""){
  		$sql .= " AND a.order_state IN ($order_state) ";
		}
		if($site_code != ""){
  		$sql .= " AND a.site_code ='$site_code' ";
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

	
	// 리뷰 상세
	public function order_detail($data){

		$order_idx = $data['order_idx'];

		$rt = array();

		$sql = "SELECT
							order_idx,
              a.site_code,
              b.site_name,
							order_number, 
							order_date,
							order_state,
							member_idx, 
							FN_AES_DECRYPT(order_id) AS order_id,
							FN_AES_DECRYPT(order_name) AS order_name,
              order_msg,
              product_idx, 
              product_auth_code, 
              product_name, 
              product_price, 
              product_img_path,              
							a.ins_date
						FROM
							tbl_order a
              join tbl_site as b on b.site_code=a.site_code and b.del_yn='N'
						WHERE
							a.del_yn ='N'
              AND
                a.order_idx = ?
						";

		$rt['result']= $this->query_row($sql,
																				array(
																				$order_idx
																				),
																				$data);

		
    return $rt;                                         																	
	}

	// 주문 내역 체크
	public function order_check($data){

		$order_idx = $data['order_idx'];

		$sql = "SELECT
							a.order_idx,
							b.order_number as payment_order_number,
							b.pg_tid,
							b.pg_price
						FROM
							tbl_order AS a
							JOIN tbl_payment AS b ON a.order_idx = b.order_idx
						WHERE
							a.order_idx = ?
						AND
							a.del_yn = 'N'
		";

		return $this->query_row($sql,
                            array(
                            $order_idx
                            ),
                            $data);

	}

	// 주문 수정
	public function order_mod_up($data){

		$order_idx = $data['order_idx'];
		$order_state = $data['order_state'];

		$this->db->trans_begin();

		if($order_state == '3' || $order_state == '1'){

			$sql = "UPDATE
							tbl_order
						SET
							order_state = ?,
							upd_date = NOW()
						WHERE
							order_idx = ?
						";

			$this->query($sql,
									array(
									$order_state,
									$order_idx
									),
									$data);
									
		}

		if($order_state == '2'){

			$sql = "UPDATE
							tbl_order
						SET
							order_state = ?,
							order_end_date = NOW(),
							upd_date = NOW()
						WHERE
							order_idx = ?
						";

			$this->query($sql,
									array(
									$order_state,
									$order_idx
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
	
}	// 클래스의 끝
?>
