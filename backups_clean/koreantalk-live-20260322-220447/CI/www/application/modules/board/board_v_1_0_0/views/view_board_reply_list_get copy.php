<ul>
          <li>
            <table>
              <tr>
                <th>
                  <button class="i_writer">작성자</button>
                  <b class="name">정민석</b>
                </th>
              </tr>
              <tr>
                <td>
                  <p class="contents">이런 커뮤니티가 있어서 너무 기뻐요. 다른 분들과 함께 언어 공부를 하며 새로운 친구들을 만나고 싶어요.</p>
                  <div class="date">2023-08-11 14:13</div>
                </td>
              </tr>
            </table>
            <img src="/images/_i_dot.png" alt="" class="btn_dot"> 
            <ul class="ul_mod">
              <li>
                <a href="">수정</a>
              </li>
              <li>
                <a href="">삭제</a>
              </li>
            </ul>
          </li>

          <li>
            <table>
              <tr>
                <th> 
                  <b class="name point_color">정민석</b>
                </th>
              </tr>
              <tr>
                <td>
                  <p class="contents">이런 커뮤니티가 있어서 너무 기뻐요. 다른 분들과 함께 언어 공부를 하며 새로운 친구들을 만나고 싶어요.</p>
                  <div class="date">2023-08-11 14:13</div>
                </td>
              </tr>
            </table>
            <img src="/images/_i_dot.png" alt="" class="btn_dot"> 
            <ul class="ul_mod">
              <li>
                <a href="">수정</a>
              </li>
              <li>
                <a href="">삭제</a>
              </li>
            </ul>
          </li>
          <li>
            <div class="no_data">
              <p>운영규정 미준수로 인해 삭제된 댓글 입니다.</p>
            </div>
          </li>
          <li>
            <div class="no_data">
              <p>삭제된 댓글 입니다.</p>
            </div>
          </li>
        </ul>






























<?php
$display =(count($result_list)<1)? "block":"none";



if(!empty($result_list)){
	foreach($result_list as $row){

  $replace_yn ="N";//대체여부
	if($row->member_del_yn =="Y"){$replace_yn ="Y";}//회원탈퇴
	if($row->display =="N"){$replace_yn ="Y";}//회원탈퇴


?>
<li class="my_review_li">
<?if($replace_yn =="N"){?>
	<table class="tbl_cmt" id="reply_comment_<?=$row->board_reply_idx?>"   >
		<colgroup>
			<col width="47px">
			<col width="*">
		</colgroup>
		<tr>
			<th class="th_top relative" rowspan="3">
				<div class="img_box">
					<img src="/images/level_<?=$row->member_grade?>.png" alt="">
				</div>
			</th>
			<td>
				<p class="name"><?=$this->global_function->user_detail($row->member_idx,$row->member_nickname)?></p>
				<p class="date"><?=$this->global_function->date_ymd_comma($row->ins_date)?></p>
				<img src="/images/head_btn_more.png" onclick="modal_open('cmt_more_<?=$row->board_reply_idx?>');set_board_part('1');set_board_reply_idx('<?=$row->board_reply_idx?>','<?=$row->member_nickname?>')" alt="더보기" class="btn_more">
				<div class="modal_cmt_more modal_cmt_more_<?=$row->board_reply_idx?>" >
					<ul class="setting_ul">
						<?if($row->member_idx==$this->member_idx){?>
						<li>
							<a href="javascript:void(0);" onClick="reply_comment_del();">삭제</a>
						</li>
						<?}else{?>
						<li>
							<a href="javascript:modal_close('modal_cmt_more_<?=$row->board_reply_idx?>');modal_open('report');">신고</a>
						</li>
						<li>
							<a href="javascript:void(0);" onClick="member_block_reg_in('1');"">차단</a>
						</li>
						<?}?>
					</ul>
				</div>
				<div class="md_overlay md_overlay_cmt_more_<?=$row->board_reply_idx?>" onclick="modal_close('cmt_more_<?=$row->board_reply_idx?>')"></div>
			</td>
		</tr>
		<tr>
			<td>
				<p class="p"><?=nl2br($row->reply_comment)?></p>
			</td>
		</tr>
		<tr>
			<td>
				<span class="reg_reply" onclick="reg_reply(1);set_board_reply_idx('<?=$row->board_reply_idx?>','<?=$row->member_nickname?>')">답글쓰기</span>
				<span class="wish_btn like3">
					<a class="<?=($row->my_like_yn =="Y")? "on" :"";?>" href="javascript:void(0)" onclick="wish_btn(this);board_reply_like_reg_in('<?=$row->board_reply_idx?>')"></a>
				</span>
				<span class="date" id="span_reply_like_cnt_<?=$row->board_reply_idx?>"><?=$row->like_cnt?></span>
			</td>
		</tr>
	</table>

	<div class="cmt_blind" id="reply_report_<?=$row->board_reply_idx?>"  style="display:<?=($row->my_report_cnt>0)?"block":"none";?>" >신고하신 댓글입니다.</div>
	<div  id="reply_block_<?=$row->board_reply_idx?>"  style="display:<?=((in_array($row->board_reply_idx, $block_arr)))?"block":"none";?>">차단된 회원의 글 입니다. <span onClick="set_board_reply_idx('<?=$row->board_reply_idx?>','<?=$row->member_nickname?>');member_block_reg_in('1');">차단 해제</span></div>
  <div class="cmt_blind" id="reply_del_<?=$row->board_reply_idx?>"     style="display:<?=($row->del_yn =="Y")?"block":"none";?>" >삭제된 글 입니다.</div>

	
<?}else{?>
	<?if($row->member_del_yn =="Y"){?>
	<div class="cmt_blind">탈퇴한 회원이 작성한 글입니다.</div>
	<?}?>
	<?if($row->display_yn =="N"){?>
	<div class="cmt_blind">관리자에 의해 블라인드 된 글입니다.</div>
	<?}?>
<?}?>


<?
$filter_array = array_filter($comment_reply_array, function ($item) use ($row) {
	return $item->grand_parent_board_reply_idx === $row->board_reply_idx;
});

foreach($filter_array as $row2) {
	$_replace_yn ="N";//대체여부
	if($row2->member_del_yn =="Y"){$_replace_yn ="Y";}//회원탈퇴
	if($row2->display_yn =="N"){$_replace_yn ="Y";}//블라인트

?>
 <?if($_replace_yn =="N"){?>
	 <table class="tbl_reply" id="reply_comment_<?=$row2->board_reply_idx?>"   style="display:<?=( ($row2->my_report_cnt>0) || ( in_array($row2->board_reply_idx, $block_arr))   || ( $row2->del_yn =="Y")   )?"none":"block";?>" >
		 <colgroup>
			 <col width="47px">
			 <col width="*">
		 </colgroup>
		 <tr>
			 <th class="th_top relative" rowspan="3">
				 <div class="img_box">
					 <img src="/images/level_<?=$row2->member_grade?>.png" alt="">
				 </div>
			 </th>
			 <td>
				 <p class="name"><?=$this->global_function->user_detail($row2->member_idx,$row2->member_nickname)?></p>
				 <p class="date"><?=$this->global_function->date_ymd_comma($row2->ins_date)?></p>
				 <img src="/images/head_btn_more.png" onclick="modal_open('cmt_more_<?=$row2->board_reply_idx?>');set_board_part('1');set_board_reply_idx('<?=$row2->board_reply_idx?>','<?=$row2->member_nickname?>')" alt="더보기" class="btn_more">
			
				 <div class="modal_cmt_more modal_cmt_more_<?=$row2->board_reply_idx?>">
					<ul class="setting_ul">
						<?if($row2->member_idx==$this->member_idx){?>
						<li>
							<a href="javascript:void(0);" onClick="reply_comment_del();">삭제</a>
						</li>
						<?}else{?>
						<li>
							<a href="javascript:modal_close('cmt_more');modal_open('report');">신고</a>
						</li>
						<li>
							<a href="javascript:void(0);" onClick="member_block_reg_in('1');"">차단</a>
						</li>
						<?}?>
					</ul>
				</div>
				<div class="md_overlay md_overlay_cmt_more_<?=$row2->board_reply_idx?>" onclick="modal_close('cmt_more_<?=$row2->board_reply_idx?>')"></div>


			 </td>
		 </tr>
		 <tr>
			 <td>
				 <p class="p">
				 <?if($row2->depth==1){?><span class="tag_name">@<?=$row2->parent_member_nickname?></span><?}?> &nbsp;&nbsp;	
				 <?=nl2br($row2->reply_comment)?>
				</p>
			 </td>
		 </tr>
		 <tr>
			 <td>
				 <span class="reg_reply" onclick="reg_reply(0);set_board_reply_idx('<?=$row2->board_reply_idx?>','<?=$row2->member_nickname?>')">답글쓰기</span>
				 <span class="wish_btn like3">
					 <a class="<?=($row2->my_like_yn =="Y")? "on" :"";?>" href="javascript:void(0)" onclick="wish_btn(this);board_reply_like_reg_in('<?=$row2->board_reply_idx?>')"></a>
				 </span>
				 <span class="date" id="span_reply_like_cnt_<?=$row2->board_reply_idx?>">	<?=$row2->like_cnt?></span>
			 </td>
		 </tr>
	 </table>

	<div class="tbl_reply cmt_blind" id="reply_report_<?=$row2->board_reply_idx?>"  style="display:<?=($row2->my_report_cnt>0)?"block":"none";?>" >신고하신 댓글입니다.</div>
	<div class="tbl_reply" id="reply_block_<?=$row2->board_reply_idx?>"  style="display:<?=((in_array($row2->board_reply_idx, $block_arr)))?"block":"none";?>">차단된 회원의 글 입니다. <span onClick="set_board_reply_idx('<?=$row2->board_reply_idx?>','<?=$row2->member_nickname?>');member_block_reg_in('1');">차단 해제</span></div>
	<div class="tbl_reply cmt_blind" id="reply_del_<?=$row2->board_reply_idx?>"     style="display:<?=($row2->del_yn =="Y")?"block":"none";?>" >삭제된 글 입니다.</div>


  <?}else{?>

		 <?if($row2->member_del_yn =="Y"){?>
		 <div class="tbl_reply cmt_blind">탈퇴한 회원이 작성한 글입니다.</div>
		 <?}?>
		 <?if($row2->display_yn =="N"){?>
		 <div class="tbl_reply cmt_blind">관리자에 의해 블라인드 된 글입니다.</div>
		 <?}?>
	<?}?>

	<?}?>

  </li>

<?php
		}
	}
?>


<script type="text/javascript">
$(".my_review_li").slice(0, 10).show(); 
$(".no_data").css("display", "<?=$display?>");
$("#list_cnt").html("<?=number_format($board_summary->reply_cnt)?>");
</script>
