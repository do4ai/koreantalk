<div class="table-responsive">

  <div class="row table_title">
    <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong> 건</div>
    <div class="col-lg-6 text-right">
     &nbsp;<a href="/<?=mapping('board_category')?>/board_category_reg" class="btn btn-success">등록</a></div>
  </div>

 
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="50">No</th>
          <th width="*">카테고리 명</th>
          <th width="150">등록된 게시물수</th>
          <th width="150">등록일</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if(!empty($result_list)){
            foreach($result_list as $row){
        ?>
          <tr>
            <td><?=$no--?></td>
           
            <td >
            <a href="/<?=mapping('board_category')?>/board_category_mod?board_category_idx=<?=$row->board_category_idx?>&history_data=<?=$history_data?>"><?=$row->category_name?></a>
          </td>
            <td><?=number_format($row->board_cnt)?></td>
            <td><?=$this->global_function->date_Ymd_hyphen($row->ins_date)?></td>
            
          </tr>

        <?php
            }
          }else{
        ?>
        <tr>
          <td colspan="9">
            <?=no_contents('0')?>
          </td>
        </tr>
        <?php } ?>

      </tbody>
    </table>

	<?=$paging?>
</div>
