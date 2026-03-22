<div class="row table_title">
  <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과( <span id="list_count"><?=number_format($result_list_count)?></span> 개)</div>
  <div class="col-lg-6 text-right">   
    <!-- <a href="javascript:void(0);" class="btn btn-danger" onclick="default_select_del()">선택 삭제</a>&nbsp; -->
    <a href="/<?=mapping('banner')?>/banner_reg?banner_type=<?=$banner_type?>" class="btn btn-success">배너등록</a></div>
</div>


<table class="table table-bordered">
  <thead>
    <tr>
      <!-- <th width="50"><input type="checkbox" onclick="chkBox(this.checked)"></th> -->
      <th width="30">No.</th>
      <th width="100">사이트</th>
      <th width="*">배너명</th>
      <th width="100">등록일</th>
      <th width="90">노출여부</th>
    </tr>
  </thead>
  <tbody>
    <tbody>
      <?php
        if(!empty($result_list)){
  			  foreach($result_list as $row ){
      ?>
        <tr>
          <!-- <td>
            <input type="checkbox" name="checkbox" value="<?=$row->banner_idx?>">
          </td> -->
          <td>
            <?=$no--?>
          </td>
          <td>
            <?=$row->site_name?>
          </td>
          <td>
            <a href="/<?=mapping('banner')?>/banner_detail?banner_idx=<?=$row->banner_idx?>"><?=$row->title?></a>
          </td>
          <td>
            <?=$this->global_function->dateYmdHyphen($row->ins_date)?>
          </td>
          <td>
            <?php
              if($row->state == "1") echo "활성화";
              else echo "비활성화";
            ?>
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
