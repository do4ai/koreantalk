<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 심정민
| Create-Date : 2021-10-18
| Memo :
|------------------------------------------------------------------------
*/

Class Model_board extends MY_Model{
	// 리스트
	public function board_list($data){

		$page_size = (int)$data['page_size'];
		$page_no 	 = (int)$data['page_no'];

		$orderby 	 = $data['orderby'];
		$category 	 = $data['category'];
		$s_text 	 = $data['s_text'];

		$sql = "SELECT
							a.board_idx,
							a.title,
							a.category,
							a.view_cnt,
							a.reply_cnt,
							a.title,
							a.contents,
							a.contents_text,
							a.board_img,
							a.notice_yn,
              DATE_FORMAT(a.ins_date,'%Y-%m-%d') as ins_date,														
							a.member_idx,
              FN_AES_DECRYPT(b.member_name) AS member_name
						FROM
							tbl_board as a
							left join tbl_member as b on b.member_idx=a.member_idx and b.del_yn='N'							
						WHERE
							a.del_yn = 'N'
							AND a.display_yn='Y'
							AND a.category='$category'
							AND a.site_code='$this->current_lang'
		";

		if($s_text !=""){
			$sql .= " AND ( title like '%$s_text%' or  contents like '%$s_text%' ) ";
		}

		if($orderby !=""){
			if($orderby =="0"){
				$sql .=" ORDER BY a.board_idx DESC LIMIT ?, ? ";
			}
			if($orderby =="1"){
				$sql .=" ORDER BY a.view_cnt DESC ,a.board_idx DESC LIMIT ?, ? ";
			}
			if($orderby =="2"){
				$sql .=" ORDER BY a.reply_cnt DESC ,a.board_idx DESC LIMIT ?, ? ";
			}
			if($orderby =="3"){
				$sql .=" ORDER BY a.like_cnt DESC ,a.board_idx DESC LIMIT ?, ? ";
			}
			if($orderby =="4"){
				$sql .=" ORDER BY a.scrap_cnt DESC  ,a.board_idx DESC LIMIT ?, ? ";
			}
			if($orderby =="5"){
				$sql .=" ORDER BY a.reply_date DESC ,a.board_idx DESC LIMIT ?, ? ";
			}
    }else{
			$sql .=" ORDER BY a.board_notice_order_no DESC,a.notice_yn DESC, a.board_idx DESC LIMIT ?, ? ";
		}


		return $this->query_result($sql,array(
															 $page_no,
															 $page_size
															 ),$data
														 );
	}

	// 리스트 총 카운트
	public function board_list_count($data){
		$category 	 = $data['category'];
		$s_text 	 = $data['s_text'];

		$sql = "SELECT
							COUNT(*) AS cnt
						FROM
							tbl_board as a
							join tbl_member as b on b.member_idx=a.member_idx and b.del_yn='N'
						WHERE
							a.del_yn = 'N'
							AND a.display_yn='Y'
							AND a.category='$category'
							AND a.site_code='$this->current_lang'

		";
		if($s_text !=""){
			$sql .= " AND ( title like '%$s_text%' or  contents like '%$s_text%' ) ";
		}

		return $this->query_cnt($sql,array());
	}

	// 상세
	public function board_detail($data){

		$board_idx = $data['board_idx'];

		$sql = "SELECT
							a.board_idx,
              a.member_idx,
							a.title,
							a.category,							
							a.view_cnt,							
							a.reply_cnt,							
							a.like_cnt,							
							a.scrap_cnt,							
							a.title,
							a.contents,
							a.notice_yn,
							a.board_img,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d %H:%i') as ins_date,
							b.member_img,
							FN_AES_DECRYPT(b.member_name) AS member_name
						FROM
							tbl_board as a
							left join tbl_member as b on b.member_idx=a.member_idx and b.del_yn='N'
							LEFT JOIN tbl_board_category  AS f ON f.board_category_idx=a.category 
						WHERE
							a.del_yn = 'N'
							AND a.display_yn='Y'
							and a.board_idx=?
					";

   		return $this->query_row($sql,array(
														  $board_idx
														  ),$data
														);
	}

	//  등록
	public function board_reg_in($data){	
		$site_code = $data['site_code'];
		$category = $data['category'];
		$title = $data['title'];
		$contents = $data['contents'];
		$contents_text = $data['contents_text'];
		$board_img = $data['board_img'];
    
		$this->db->trans_begin();

		$sql = "INSERT INTO
							tbl_board
						(						
							site_code,
							member_idx,
							category,
							title,
							contents,
							contents_text,
							board_img,
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
							?,					
							'N',
							NOW(),
							NOW()
						)
						";

		$this->query($sql,
								 array(								
									$site_code,
		 							$this->member_idx,
		 							$category,
		 							$title,
		 							$contents,
		 							$contents_text,
		 							$board_img,
			
							   ),
								 $data);

		$board_idx = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
	   	return $board_idx;
		}
	}

	//  수정
	public function board_mod_up($data){

		$board_idx = $data['board_idx'];		
		$title = $data['title'];
		$contents = $data['contents'];
		$contents_text = $data['contents_text'];
		$board_img = $data['board_img'];
		
		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board
						SET							
							title = ?,
							contents = ?,
							contents_text = ?,
							board_img = ?,						
							upd_date = NOW()
						WHERE
							board_idx = ?
						";

		$this->query($sql,
								 array(
							   $title,
								 $contents,
							   $contents_text,
							   $board_img,
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

  // 7. 게시물 삭제
	public function board_del($data){
		$board_idx = $data['board_idx'];

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


  // 7. 게시물 삭제
  public function board_read_mod_up($data){
    $board_idx = $data['board_idx'];

    $this->db->trans_begin();

    $sql = "UPDATE
              tbl_board
            SET
              view_cnt = view_cnt+1,
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

  // 2. 원글 체크
  public function board_summary($data){
    $board_idx = $data['board_idx'];

    $sql = "SELECT
              a.board_idx,
              a.member_idx,
							a.scrap_cnt,
							a.reply_cnt,
							a.like_cnt							
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




  // 3. 포토게시판댓글 리스트
  public function board_comment_list($data){

    $board_idx = $data['board_idx'];
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              a.board_reply_idx,
              a.member_idx,
              a.board_idx,
              a.reply_comment,
              a.parent_board_reply_idx,
              a.depth,
              a.like_cnt,
              DATE_FORMAT(a.ins_date,'%Y-%m-%d %H:%i') as ins_date,
							if(DATE_FORMAT(a.ins_date,'%Y-%m-%d')=current_date,'Y','N')  as is_today,
              TIMESTAMPDIFF(MINUTE ,a.ins_date,NOW()) AS date_diff,
              a.display_yn,
              a.del_yn,
              b.del_yn as member_del_yn,
              b.member_img,					
              FN_AES_DECRYPT(b.member_name) AS member_name,
              ifnull((SELECT like_yn  FROM tbl_board_reply_like WHERE board_reply_idx = a.board_reply_idx AND member_idx = '$member_idx'),'N') AS my_like_yn,
              ifnull((SELECT COUNT(*) AS cnt FROM tbl_board_reply_report WHERE del_yn = 'N' AND board_reply_idx = a.board_reply_idx AND member_idx = '$member_idx'),0) AS my_report_cnt
            FROM
              tbl_board_reply a
              JOIN tbl_member b ON b.member_idx = a.member_idx
            WHERE
               a.depth = 0
              AND a.board_idx = ?
    ";

    $sql.=" ORDER BY a.board_reply_idx asc  ";

    return $this->query_result($sql,
                              array(
                              $board_idx,                    
                              ),
                              $data
                              );

  }


  // 3. 리스트  count
  public function board_comment_list_count($data){

    $board_idx = $data['board_idx'];
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              COUNT(*) AS cnt
            FROM
              tbl_board_reply a
              JOIN tbl_member b ON b.member_idx = a.member_idx and b.del_yn='N'
            WHERE
               a.depth = 0
              AND a.board_idx = ?

    ";
    return $this->query_cnt($sql,
                              array(
                              $board_idx,

                              ),
                              $data
                              );

  }

  // 4. 포토게시판대댓글 리스트
  public function board_comment_reply_list($data){
    $board_idx = $data['board_idx'];
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              a.board_reply_idx,
              a.board_idx,
              a.reply_comment,
              a.grand_parent_board_reply_idx,
              a.parent_board_reply_idx,
              a.depth,
              TIMESTAMPDIFF(MINUTE ,a.ins_date,NOW()) AS date_diff,
							DATE_FORMAT(a.ins_date,'%Y-%m-%d %H:%i') as ins_date,
							if(DATE_FORMAT(a.ins_date,'%Y-%m-%d')=current_date,'Y','N')  as is_today,
              a.display_yn,
              a.like_cnt,
              a.del_yn,
              a.member_idx,
              b.del_yn as member_del_yn,
              b.member_img,		
              FN_AES_DECRYPT(b.member_name) as member_name,
              FN_AES_DECRYPT(d.member_name)  as parent_member_name
            FROM
              tbl_board_reply a
              JOIN tbl_member b ON b.member_idx = a.member_idx and b.del_yn='N'
			  left join tbl_board_reply as c on c.board_reply_idx=a.parent_board_reply_idx and c.del_yn='N'
              left JOIN tbl_member d ON d.member_idx = c.member_idx
            WHERE
               a.depth > 0
              AND a.board_idx = ?
    ";
    $sql.=" ORDER BY a.reply_depth ASC,a.parent_board_reply_idx ASC, a.board_reply_idx ASC ";

    return $this->query_result($sql,
                              array(
                              $board_idx
                              ),
                              $data
                              );

  }

  // 2. 포토게시판댓글 상세
  public function board_reply_detail($data){
    $board_reply_idx = $data['board_reply_idx'];

    $sql = "SELECT
              a.board_idx,
              a.member_idx,
              a.like_cnt,
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
								?, 
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
  
  	//  수정
	public function reply_comment_mod_up($data){

		$board_reply_idx = $data['board_reply_idx'];
		$reply_comment = $data['reply_comment'];
				
		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board_reply
						SET							
							reply_comment = ?,					
							upd_date = NOW()
						WHERE
							board_reply_idx = ?
						";

		$this->query($sql,
								 array(
							   $reply_comment,							
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

	// 6. 포토게시판댓글 삭제
	public function reply_comment_del($data){
		$board_reply_idx = $data['board_reply_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_board_reply
						SET
							del_yn = 'Y',
							upd_date = NOW()
						WHERE
							board_reply_idx = ?
						";

		$this->query($sql,
								array(
								$board_reply_idx
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

}	//클래스의 끝
?>
