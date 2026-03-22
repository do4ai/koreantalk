<div class="table-responsive">

  <div class="row table_title">
    <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong> 건</div>
    <div class="col-lg-6 text-right"> &nbsp;
     
     
      <!-- <a class="btn btn-success" href="/<?=mapping('board')?>/earth_board_reg">등록</a> -->
      </div>
  </div>

  <form name="form_default" id="form_default" method="post">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="50">No</th>        
          <th width="100">사이트</th>
          <th width="100">이름</th>          
          
          <th width="*">제목</th>
          <th width="100">댓글수</th>
          <th width="150">노출여부</th>
          <th width="150">등록일</th>
       
        </tr>
      </thead>
      <tbody>
        <?php
          if(!empty($result_list)){
            foreach ($result_list as $row) {            

        ?>
          <tr>
            <td><?=$no--?></td>          
            <td><?=$row->site_name?></td>
            <td><?=$row->member_name?></td>           
            <td class="td_left"><a href="/<?=mapping('board')?>/board_detail?board_idx=<?=$row->board_idx?>&history_data=<?=$history_data?>"><?=$row->title?></a></td>            
            <td><?=$row->reply_cnt?></td>
            <td><?=($row->display_yn =="Y")? "비활성화" :"활성화";?></td>
            <td><?=$this->global_function->date_YmdHi_hyphen($row->ins_date)?></td>
           

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
  </form>
	<?=$paging?>
</div>
