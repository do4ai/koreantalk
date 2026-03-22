<ul>
<?php
$display =(count($result_list)<1)? "block":"none";

foreach($result_list as $row){

$replace_yn ="N";//대체여부
if($row->del_yn =="Y"){$replace_yn ="Y";}//삭제
if($row->display_yn =="N"){$replace_yn ="Y";}//미준수로 삭제된글


?>
<li class="my_review_li" id="reply_<?=$row->board_reply_idx?>">
	<?if($replace_yn =="N"){?>
		<table>
			<tr>
			<th>
				<?if($row->member_idx == $this->member_idx){?>
					<button class="i_writer"><?=lang('lang_10031','작성자')?></button>
					<b class="name"><?=$row->member_name?></b>
				<?}else if($row->member_idx == 1){?>
					<b class="name point_color"><?=$row->member_name?></b>
				<?}?>
			</th>
			</tr>
			<tr>
			<td>
				<p class="contents" id="reply_contents_<?=$row->board_reply_idx?>"><?=nl2br($row->reply_comment)?></p>
				<div class="date"><?=$row->ins_date?></div>
			</td>
			</tr>
		</table>
		<?if($row->member_idx ==$this->member_idx){?>
		<img src="/images/_i_dot.png" alt="" class="btn_dot"> 
		<ul class="ul_mod">
			<li>
			<a href="javascript:void(0)" onClick="set_board_reply_idx('<?=$row->board_reply_idx?>',<?=nl2br($row->reply_comment)?>)"><?=lang('lang_10032','수정')?></a>
			</li>
			<li>
			<a href="javascript:void(0)" onClick="reply_comment_del('<?=$row->board_reply_idx?>')"><?=lang('lang_10033','삭제')?></a>
			</li>
		</ul>
		<?}?>
	
	<?}else{?>
		<?if($row->del_yn =="Y"){?>			
		<div >
			<p><?=lang('lang_10034','삭제된 댓글 입니다.')?></p>
		</div>          
		<?}?>
		<?if($row->display_yn =="N"){?>
			<div >
				<p><?=lang('lang_10035','운영규정 미준수로 인해 삭제된 댓글 입니다.')?></p>
			</div>
		<?}?>
	<?}?>
  </li>
<?php		
	}
?>
 </ul>

<script type="text/javascript">
$(".my_review_li").slice(0, 10).show(); 
$(".no_data").css("display", "<?=$display?>");
$("#list_cnt").html("<?=lang('lang_10036','댓글')?> <?=number_format($board_summary->reply_cnt)?>");
</script>


<script>
  $('.btn_dot').click(function(){
		var $ul_mod = $(this).siblings('.ul_mod');
		if($ul_mod.is(':visible')){
			$ul_mod.hide();
			$('.back').hide();
		} else {
			$('.ul_mod').hide();
			$ul_mod.show();
			$('.back').show();
		}
  });
  $('.back').click(function(){
    $('.ul_mod').hide();
    $('.back').hide();
  });

  $('.ul_mod').hide();
</script>