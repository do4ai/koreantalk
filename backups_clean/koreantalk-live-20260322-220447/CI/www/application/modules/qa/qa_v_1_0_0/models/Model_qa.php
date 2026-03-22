<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	심정민
| Create-Date : 2021-09-03
| Memo : 1:1 문의
|------------------------------------------------------------------------
*/

Class Model_qa extends MY_Model {

	// 1:1 질문 리스트 가져오기
	public function qa_list($data) {
		$page_size = (int)$data['page_size'];
		$page_no = (int)$data['page_no'];

		$sql = "SELECT
							qa_idx,
							qa_title,
							qa_contents,
							ins_date,
							reply_yn,
							del_yn
						FROM
							tbl_qa as a
						WHERE
							del_yn ='N'
						AND member_idx = ?
						and a.site_code ='$this->current_lang'
						";

		$sql .= " ORDER BY qa_idx DESC LIMIT ?, ? ";

		return $this->query_result($sql,array(
															 $this->member_idx,
															 $page_no,
															 $page_size
															 ),$data
													 	 );
	}

	// 1:1 질문 리스트 개수
	public function qa_list_count($data){

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_qa as a
						WHERE
							del_yn ='N'
							AND member_idx = ?
              and site_code ='$this->current_lang'
						";

		return	$this->query_cnt($sql,
														array(
														$this->member_idx
														),
														$data
													);
	}

	// 1:1 질문 상세보기
	public function qa_detail($data){

		$qa_idx = $data['qa_idx'];

		$sql = "SELECT
							a.qa_idx,
							a.qa_title,
							a.qa_contents,							
							a.reply_yn,
							DATE_FORMAT(a.ins_date, '%Y-%m-%d %H:%i') as ins_date,
							DATE_FORMAT(a.reply_date, '%Y-%m-%d %H:%i') as reply_date,
							a.reply_contents
						FROM
							tbl_qa a
						WHERE
							a.del_yn = 'N'
							AND a.qa_idx = ?
						";

		return $this->query_row($sql,array(
														$qa_idx
														),$data
													);
	}



	// 1:1 질문 등록
	public function qa_reg_in($data){
		$qa_title = $data['qa_title'];
		$qa_contents = $data['qa_contents'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_qa
						(
							site_code,
							member_idx,
							qa_title,
							qa_contents,				
							ins_date,
							upd_date
						) VALUES (
							?,
							?,
							?,
							?,					
							NOW(),
							NOW()
						)
						";

		$this->query($sql,array(
                            $this->current_lang,
                            $this->member_idx,
                            $qa_title,
                            $qa_contents
                          ),$data
                      );

		$qa_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return $qa_idx;
		}

	}

	// 6. QA 삭제
	public function qa_del($data){

		$qa_idx = $data['qa_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_qa
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							qa_idx = ?
					";

		$this->query($sql,array(
						 		 $qa_idx
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
