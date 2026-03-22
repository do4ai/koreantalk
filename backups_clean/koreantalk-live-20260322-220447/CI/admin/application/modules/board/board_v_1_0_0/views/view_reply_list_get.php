
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="50">No</th>
          <th width="150">이름</th>
          <th width="*">내용</th>
          <!--<th width="80">종류</th>
           <th width="180">답글작성</th>
          <th width="150">원 댓글 작성자</th> 
          <th width="80">신고수</th>-->
          <th width="150">등록일시</th>
          <th width="130">제재상태</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if(!empty($result_list)){
            foreach($result_list as $row){
        ?>
          <tr>
            <td style="text-align:center;"><?=$no--?></td>
            <td style="text-align:center;"><?=$row->member_nickname?></td>
            <td class="td_left"><?=$row->reply_comment?></td>
            <!--  <td style="text-align:center;"><?php echo $row->depth == 0? "댓글":"답글"; ?></td>
            <td style="text-align:center;"><a class="btn-sm btn-info" data-target="#layerpop3" data-toggle="modal"  onclick="set_board_reply_idx('<?=$row->board_reply_idx?>');">답글작성</a></td>
            <td style="text-align:center;"><?=$row->parent_member_nickname?></td> 
            <td style="text-align:center;"><?=$row->report_cnt?></td>-->
            <td style="text-align:center;"><?=$this->global_function->date_YmdHi_hyphen($row->ins_date)?></td>
            <td style="text-align:center;">
              <a class="btn-sm btn-success" href="javascript:void(0)" onclick="board_reply_display_mod_up('<?=$row->board_reply_idx?>','<?=($row->display_yn =="Y")?"N":"Y";?>');"><?php echo $row->display_yn == "Y"? "블라인드":"블라인드 해제"; ?></a>
            </td>
          </tr>
        <?php
            }
          }else{
        ?>
        <tr>
          <td colspan="15">
            <?=no_contents('0')?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

	<?=$paging?>
