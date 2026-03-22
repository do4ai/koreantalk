<div class="table-responsive">

  <div class="row table_title">
    <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong> 건</div>
    <div class="col-lg-6 text-right"> &nbsp;   <a class="btn btn-success" href="/<?=mapping('site')?>/site_reg">등록</a></div>
  </div>

    <table class="table table-bordered">
      <thead>
        <tr>
          
          <th width="50">No</th>
          <th width="200">사이트 코드</th>
          <th width="*">사이트명</th> 
          <th width="150">날짜</th>
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
              <?=$row->site_code?>
            </td>
            <td class="text-left">
              <a href="/<?=mapping('site')?>/site_detail?site_idx=<?=$row->site_idx?>&history_data=<?=$history_data?>"><?=$row->site_name?></a>
            </td>
           
           
            <td>
              <?=$row->ins_date?>
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


</div>
