<div class="row table_title">
	<div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 (<strong><?=number_format($result_list_count)?></strong>)</div>
	<div class="col-lg-6  text-right"> &nbsp;<a href="/<?=mapping('faq')?>/faq_reg" class="btn btn-success">등록</a></div>
</div>

<table class="table table-bordered">
  <thead>
    <tr>
      <th width="100">No</th> 
      <th width="150">사이트</th> 
      <th width="*">제목</th>
      <th width="150">노출여부</th>
      <th width="200">등록일</th>
    </tr>
  </thead>
  <tbody>

    <?php
      if(!empty($result_list)){
        foreach($result_list as $row){

    ?>
      <tr>
        <td>
          <?=$no--?>
        </td>
         <td>
         <?=$row->site_name;?>
        </td>
        <td>
          <a href="/<?=mapping('faq')?>/faq_detail?faq_idx=<?=$row->faq_idx?>&history_data=<?=$history_data?>"><?=$row->title?></a>
        </td>
        <td>
          <?=($row->display_yn =="Y")?"활성화":"비활성화";?>
        </td>
        <td>
         <?=$row->ins_date;?>
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
    <?php
      }
    ?>

  </tbody>
</table>

<?=$paging?>
