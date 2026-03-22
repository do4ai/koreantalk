<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : -
| Create-Date : 2023-07-21
| Memo : 상품 결제 내역
|------------------------------------------------------------------------
*/

Class Model_order extends MY_Model {

	// 주문 리스트 가져오기
	public function order_list($data) {
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$s_text = $data['s_text'];

		$sql = "SELECT 	
					a.order_idx, 
					a.order_number, 
					a.order_state, 
					a.order_date,
					a.product_idx, 
					a.product_auth_code, 
					a.product_name, 
					a.product_price, 
					a.product_img_path, 
					b.product_contents,	 
					a.download_cnt, 
					a.ins_date, 
					b.upd_date              
				FROM 
				tbl_order  AS a
				JOIN tbl_electron_book AS b ON b.electron_book_idx =a.product_idx	
				WHERE a.del_yn='N'
				and a.member_idx='$this->member_idx'
	
						";
		if($s_text != ""){
  		$sql .= " AND ( a.product_name LIKE '%$s_text%' or   b.product_contents LIKE '%$s_text%') ";
		}

		$sql .= " ORDER BY a.ins_date DESC LIMIT ?, ? ";

		return $this->query_result($sql,
															 array(
															 $page_no,
															 $page_size
															 ),
															 $data);
	}

	
	// 주문 리스트 총 카운트
	public function order_list_count($data) {
		$s_text = $data['s_text'];

		$sql = "SELECT
							COUNT(1) AS cnt
            FROM 
            tbl_order  AS a
            JOIN tbl_electron_book AS b ON b.electron_book_idx =a.product_idx	
            WHERE a.del_yn='N'
            and a.member_idx='$this->member_idx'
		";
		if($s_text != ""){
  		$sql .= " AND ( a.product_name LIKE '%$s_text%' or   b.product_contents LIKE '%$s_text%') ";
		}


		return $this->query_cnt($sql,
														array(
														),
														$data);
	}


  // 
	public function order_detail($data) {

		$product_auth_code = $data['product_auth_code'];

		$sql = "SELECT
							b.pdf_org_name,
							b.pdf_url,
							a.product_auth_code,
              a.product_idx
            FROM 
              tbl_order  AS a
            JOIN tbl_electron_book AS b ON b.electron_book_idx =a.product_idx	
            WHERE a.del_yn='N'
            and a.product_auth_code=?
            and a.member_idx='$this->member_idx'
		";
	
		return $this->query_row($sql,
														array(
                            $product_auth_code
														),
														$data);
	}


	// 6. QA 삭제
	public function file_download_up($data){

		$product_auth_code = $data['product_auth_code'];

		$this->db->trans_begin();

		$sql = "UPDATE
					tbl_order
				SET
					download_cnt = download_cnt+1,
					upd_date = NOW()
				WHERE
					product_auth_code = ?
			";

		$this->query($sql,array(
									$product_auth_code
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

	

	
}	// 클래스의 끝
?>
