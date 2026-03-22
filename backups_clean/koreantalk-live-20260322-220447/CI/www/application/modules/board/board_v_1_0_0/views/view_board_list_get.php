<ul class="ul_board">
<?php
$display =(count($result_list)<1)? "block":"none";
if(!empty($result_list)){
	foreach($result_list as $row){

		$board_img=""; 
		$img_count="";
		if($row->board_img !=""){
			$img_arr =explode(",",$row->board_img);
			$img_count =count($img_arr);
			$board_img=$img_arr[0];
		}

?>
      <li>
      
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/board_detail?board_idx=<?=$row->board_idx?>&category=<?=$row->category?>">
          <table class="tbl_board">
            <colgroup>
              <col width='*'>
              <col width='110px'>
            </colgroup>
            <tr>
              <? if($row->notice_yn=='Y'){?>
                <th style="display: flex; align-items: center;">
                  <span class="notice-badge" style="background-color: #FF90AC; color: white; border-radius: 10px; padding: 2px 8px; font-size:12px; margin-right: 5px;">공지</span>
                  <h5 class="text-overflow" style="color: #FF90AC;"><?=$row->title?></h5>
                </th>
              <?}else{?>
                <th><h5 class="text-overflow"><?=$row->title?></h5></th>
              <? }?>  
              <td rowspan="3">
                <div class="img_box thumbnail">
                  <? if(!empty($board_img)){?>
                    <img src="<?=$board_img?>" alt="">
                  <?}?>
            
                </div>
              </td>
            </tr>
            <tr>
              <th>
                <p class="contents"><?=$row->contents_text?></p>
              </th>
            </tr>
            <tr>
              <th>
                <ul class="ul_board_info">
                  <? if($row->notice_yn=='N'){?>
                    <li>
                      <img src="/images/i_comment.png" alt="">
                      <?=$row->reply_cnt?>
                    </li>
                  <? } ?>
                  <li>
                    <img src="/images/i_view.png" alt="">
                    <?=$row->view_cnt?>
                  </li>
                  <li>
				            <?=$row->ins_date?>
                  </li>
                  <? if($row->notice_yn=='Y'){?>
                    <li>
                      <div class="point_color"> KoreanTalk</div> 
                    </li>
                  <? }else{?>
                    <li>
                    <div class="point_color"> <?=$row->member_name?></div> 
                    </li>
                  <? }?>  
                </ul>
              </th>
            </tr>
          </table>
        </a>
      </li>
	  <?php
		}
		}
	  ?>  
    </ul>
     
    <?=$paging?>



<script type="text/javascript">
  $("#span_s_text").html('<?=$s_text?>');
  $(".no_data").css("display","<?=$display?>");
</script>
