<div class="table-responsive">

  <div class="row table_title">
    <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong> 건</div>
    <div class="col-lg-6 text-right"> &nbsp;
      <!-- <a class="btn btn-gray" href="javascript:void(0)" onclick="default_select_del()">선택삭제</a> -->
       <a class="btn btn-success" href="/<?=mapping('notice')?>/notice_reg">등록</a></div>
  </div>

  <form name="form_default" id="form_default" method="post">
    <table class="table table-bordered">
      <thead>
        <tr>
          <!-- <th width="50"><input type="checkbox" onclick="chk_box(this.checked)"></th> -->
          <th width="50">No</th>
          <th width="*">제목</th>
          <th width="100">사이트</th>
          <th width="100">노출 여부</th>
          <th width="150">등록일</th>
        </tr>
      </thead>
      <tbody>

        <?php
          if(!empty($result_list)){
            foreach($result_list as $row){
        ?>
        <tr>
          <!-- <td>
            <input type="checkbox"  name="checkbox" value="<?=$row->notice_idx?>">
          </td> -->
          <td>
            <?=$no--?>
          </td>
 
          <td class="td_left">
            <a href="/<?=mapping('notice')?>/notice_detail?notice_idx=<?=$row->notice_idx?>&history_data=<?=$history_data?>"><?=$row->title?></a>
          </td>
          <td>
          <?=$row->site_name?>

          </td>   
          <td>
          <?=($row->display_yn =="Y")?"활성화":"비활성화";?>

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
  </form>
	<?=$paging?>
</div>
