<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 박수인
| Create-Date : 2021-11-03
| Memo : 커뮤니티 관리
|------------------------------------------------------------------------
*/

Class Model_board extends MY_Model{

	// 커뮤니티 리스트
	public function board_list($data){
		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$title = $data['title'];
		$contents = $data['contents'];
		$member_nickname = $data['member_nickname'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$category = $data['category'];
		$display_yn = $data['display_yn'];
		$site_code = $data['site_code'];

		$sql = "SELECT
							a.board_idx,
							a.site_code,
							c.site_name,
							FN_AES_DECRYPT(b.member_name) as member_nickname,
							FN_AES_DECRYPT(b.member_name) AS member_name,
							a.title,
							a.category,							
							a.like_cnt,
							a.contents,
							a.view_cnt,
							a.reply_cnt,
							a.display_yn,
							a.order_no,
							a.ins_date,
							a.upd_date,
							a.del_yn
						FROM
							tbl_board a
							left JOIN tbl_member b ON b.member_idx = a.member_idx AND b.del_yn = 'N'
							JOIN tbl_site c ON c.site_code = a.site_code AND c.del_yn = 'N'
						WHERE
							a.del_yn = 'N'
							AND a.notice_yn = 'N'
		";

		if($title != ""){
			$sql .= " AND a.title LIKE '%$title%' ";
		}
		if($contents != ""){
			$sql .= " AND a.contents LIKE '%$contents%' ";
		}
		if($member_nickname != ""){
			$sql .= " AND FN_AES_DECRYPT(b.member_name) LIKE '%$member_nickname%' ";
		}
		if($display_yn != ""){
			$sql .= " AND a.display_yn = '$display_yn' ";
		}

		if($category != ""){
			$sql .= " AND a.category IN ('$category') ";
		}
		if($s_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') >= '$s_date' ";
		}
		if($e_date != ""){
			$sql .= " AND DATE_FORMAT(a.ins_date, '%Y-%m-%d') <= '$e_date' ";
		}
		
		$sql .= " ORDER BY a.ins_date DESC LIMIT ?, ?";
	 



		return $this->query_result($sql,
															 array(
															 $page_no,
															 $page_size
															 ),
															 $data);
	}

	// 커뮤니티 리스트 총 카운트
	public function board_list_count($data){

		$title = $data['title'];
		$contents = $data['contents'];
		$member_nickname = $data['member_nickname'];
		$s_date = $data['s_date'];
		$e_date = $data['e_date'];
		$category = $data['category'];
		$display_yn = $data['display_yn'];
		$board_type = $data['board_type'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_board a
							left JOIN tbl_member b ON b.member_idx = a.member_idx AND b.del_yn = 'N'
              JOIN tbl_site c ON c.site_code = a.site_code AND c.del_yn = 'N'
						WHERE
							a.del_yn = 'N'
							AND a.notice_yn = 'N'
		";

		if($title != ""){
			$sql .= " AND a.title LIKE '%$title%' ";
		}
		if($contents != ""){
			$sql .= " AND a.contents LIKE '%$contents%' ";
		}
		if($member_nickname != ""){
			$sql .= " AND FN_AES_DECRYPT(b.member_name) LIKE '%$member_nickname%' ";
		}
		if($display_yn != ""){
			$sql .= " AND a.display_yn = '$display_yn' ";
		}
		if($board_type != ""){
			$sql .= " AND a.board_type ='$board_type' ";
		}
		if($category != ""){
			$sql .= " AND a.category IN ($category) ";
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

	// 상태 변경
	public function board_state_mod_up($data){

		$board_idx  = $data['board_idx'];
		$display_yn = $data['display_yn'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET
							display_yn = ?,
							upd_date = NOW()
						WHERE
							board_idx = ?
						";

		$this->query($sql,
								 array(
								 $display_yn,
								 $board_idx
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


	// 순서변경
	public function board_order_no_mod_up($data){
		$board_idx = $data['board_idx'];
		$order_no = $data['order_no'];

		$this->db->trans_begin();

		$sql = " 	UPDATE
									tbl_board
							SET
									order_no =?,
									upd_date = NOW()
							WHERE
									board_idx =?
		";
		
		$this->query($sql,
									array(
									$order_no,
									$board_idx
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


	public function board_reg_in($data){

		$title 		= $data['title'];
		$contents = $data['contents'];
		$board_img 			= $data['board_img'];
		$board_img_detail 			= $data['board_img_detail'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_board
						(
							board_type,
							title,
							contents,
							board_img,
							board_img_detail,
							del_yn,
							ins_date,
							upd_date
						) VALUES (
							1,
							?, -- title
							?, -- contents
							?, -- img_path
							?, -- img_path
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,array(
								 $title,
								 $contents,
								 $board_img,
								 $board_img_detail,
								 ),$data
							 );

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
	}


	public function board_mod_up($data){
		$board_idx 		= $data['board_idx'];
		$title 		= $data['title'];
		$contents = $data['contents'];
		$board_img 			= $data['board_img'];
		$board_img_detail 			= $data['board_img_detail'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET
							title =?,
							contents =?,
							board_img =?,
							board_img_detail =?,
							upd_date = NOW()
						WHERE
							board_idx = ?
						";

		$this->query($sql,
								 array(
								 $title,
								 $contents,
								 $board_img,
								 $board_img_detail,
								 $board_idx,
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



	// 커뮤니티 상세
	public function board_detail($data){

		$board_idx = $data['board_idx'];

		$sql = "SELECT
							a.board_idx,
							a.board_type,
							FN_AES_DECRYPT(b.member_name) as  member_nickname,
							a.title,
							a.contents,
							a.category,
							c.category_name,
							a.like_cnt,
							a.view_cnt,
							a.scrap_cnt,
							a.reply_cnt,
						  a.report_cnt,
							a.display_yn,
							a.ins_date,
							a.upd_date,
							a.url_link,							
							a.board_img
						FROM
							tbl_board a
							left JOIN tbl_member b ON b.member_idx = a.member_idx AND b.del_yn = 'N'
							left JOIN tbl_board_category c ON c.board_category_idx = a.category AND c.del_yn = 'N'

						WHERE
							a.del_yn = 'N'
							and board_idx=?
		";

   	return $this->query_row($sql,
														array(
														$board_idx
														),
														$data);
	}

	// 2. 포토게시판댓글 상세
  public function board_reply_detail($data){
    $board_reply_idx = $data['board_reply_idx'];

    $sql = "SELECT
              a.board_idx,
              a.member_idx,
              a.parent_board_reply_idx,
              a.grand_parent_board_reply_idx,
              a.reply_depth,
              (a.reply_depth+1) as next_depth ,
              ifnull((select max(reply_depth)+1 from tbl_board_reply where depth=1 and parent_board_reply_idx='$board_reply_idx'),1) as next_reply_depth
            FROM
              tbl_board_reply a
            WHERE
              board_reply_idx = ?
      ";

      return $this->query_row($sql,
                              array(
                              $board_reply_idx
                              ),
                              $data
                              );
  }


	// 5. 포토게시판댓글 등록
	public function	board_comment_reg_in($data){
		$member_idx     = $data['member_idx'];
		$board_idx     = $data['board_idx'];
		$reply_comment = $data['reply_comment'];
		$parent_board_reply_idx        = $data['parent_board_reply_idx'];
		$grand_parent_board_reply_idx        = $data['grand_parent_board_reply_idx'];
		$depth         = $data['depth'];
		$reply_depth         = $data['reply_depth'];

		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_board_reply
							(
								member_idx,
								board_idx,
								reply_comment,
								grand_parent_board_reply_idx,
								parent_board_reply_idx,
								depth,
								reply_depth,
								del_yn,
								ins_date,
								upd_date
							) values (
								?, -- admin_idx
								?, -- board_idx
								?, -- board_idx
								?, -- reply_comment
								?, --
								?, -- depth
								?, -- depth
								'N',
								NOW(),
								NOW()
							)
			";

			$this->query($sql,
									array(
									$member_idx,
									$board_idx,
									$reply_comment,
									$grand_parent_board_reply_idx,
									$parent_board_reply_idx,
									$depth,
									$reply_depth,
									),
									$data
									);

			$sql = "UPDATE
								tbl_board
							SET
								reply_cnt = reply_cnt+1,
								reply_date = NOW(),
								upd_date = NOW()
							WHERE
								board_idx = ?
							";

			$this->query($sql,
									array(
									$board_idx
									),
									$data
									);



		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "-1";
		}else{
			$this->db->trans_commit();
			return "1000";
		}
	}



	// 커뮤니티 댓글 리스트
	public function reply_list($data){
		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$board_idx = $data['board_idx'];
	  $member_nickname = $data['member_nickname'];
		$orderby = $data['orderby'];

		$sql = "SELECT
							a.board_reply_idx,
							FN_AES_DECRYPT(b.member_name) as member_nickname,
							FN_AES_DECRYPT(d.member_name) as parent_member_nickname,
							a.img_path,
							a.report_cnt,
							a.reply_comment,
							a.parent_board_reply_idx,
							a.depth,
							a.ins_date,
							a.upd_date,
							a.del_yn,
							a.display_yn
						FROM
							tbl_board_reply a
							JOIN tbl_member b ON b.member_idx = a.member_idx AND b.del_yn = 'N'
							LEFT JOIN tbl_board_reply c ON c.board_reply_idx=a.parent_board_reply_idx  AND 	c.del_yn = 'N'
							LEFT JOIN tbl_member d ON d.member_idx = c.member_idx AND d.del_yn = 'N'
						WHERE a.del_yn = 'N'
							AND a.board_idx = $board_idx
		";
		if($member_nickname != ""){
			$sql .= " AND FN_AES_DECRYPT(b.member_name) LIKE '%$member_nickname%' ";
		}
		if($orderby != ""){
			if($orderby=="0"){
					$sql .= " ORDER BY a.report_cnt DESC LIMIT ?, ?";
			}
			if($orderby=="1"){
					$sql .= " ORDER BY a.report_cnt asc LIMIT ?, ?";
			}
			if($orderby=="2"){
					$sql .= " ORDER BY a.board_reply_idx desc LIMIT ?, ?";
			}
			if($orderby=="3"){
					$sql .= " ORDER BY a.board_reply_idx asc LIMIT ?, ?";
			}
		}

		return $this->query_result($sql,
															 array(
															 $page_no,
															 $page_size
															 ),
															 $data);
	}

	// 댓글 답글 리스트 count
	public function reply_list_count($data){

		$board_idx = $data['board_idx'];
		$member_nickname = $data['member_nickname'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_board_reply a
							JOIN tbl_member b ON b.member_idx = a.member_idx AND b.del_yn = 'N'
							LEFT JOIN tbl_board_reply c ON c.board_reply_idx=a.parent_board_reply_idx  AND 	c.del_yn = 'N'
							LEFT JOIN tbl_member d ON d.member_idx = c.member_idx AND d.del_yn = 'N'
						WHERE a.del_yn = 'N'
							AND a.board_idx = $board_idx
		";
		if($member_nickname != ""){
			$sql .= " AND  FN_AES_DECRYPT(b.member_name) LIKE '%$member_nickname%' ";
		}

		return $this->query_cnt($sql,
														array(
														),
														$data);
	}


	  // 2. 원글 체크
		public function board_summary($data){
			$board_idx = $data['board_idx'];
	
			$sql = "SELECT
								a.board_idx,
								a.member_idx,
								a.title,
								a.reply_cnt,
								a.like_cnt,
								a.scrap_cnt
							FROM
								tbl_board a
							WHERE
								a.del_yn = 'N'
								AND board_idx = ?
	
				";
	
				return $this->query_row($sql,
																array(
																$board_idx
																),
																$data
																);
		}
	
	
	
		
		// 2. 포토게시판댓글 상세
		public function board_reply_summary($data){
			$board_reply_idx = $data['board_reply_idx'];
	
			$sql = "SELECT
								a.board_idx,
								a.member_idx,
				      	a.reply_comment as title,
								a.parent_board_reply_idx,
								a.grand_parent_board_reply_idx,
								a.reply_depth,
								a.like_cnt,     
								a.help_cnt
							FROM
								tbl_board_reply a
							WHERE
								board_reply_idx = ?
				";
	
				return $this->query_row($sql,
																array(
																$board_reply_idx
																),
																$data
																);
		}

	// 노출여부 상태 변경
	public function board_reply_display_mod_up($data){

		$board_reply_idx  = $data['board_reply_idx'];
		$display_yn  = $data['display_yn'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board_reply
						SET
							display_yn = if(display_yn='Y','N','Y'),
							upd_date = NOW()
						WHERE
							board_reply_idx = ?
						";

		$this->query($sql,
								 array(						
								 $board_reply_idx
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

	public function board_display_yn_mod_up($data){

		$board_idx  = $data['board_idx'];
		$display_yn = $data['display_yn'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET
							display_yn = ?,
							upd_date = NOW()
						WHERE
							board_idx = ?
						";

		$this->query($sql,
								 array(
								 $display_yn,
								 $board_idx
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

	//board_del
	public function board_del($data){

		$board_idx  = $data['board_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							board_idx = ?
						";

		$this->query($sql,
								 array(
								 $board_idx
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
