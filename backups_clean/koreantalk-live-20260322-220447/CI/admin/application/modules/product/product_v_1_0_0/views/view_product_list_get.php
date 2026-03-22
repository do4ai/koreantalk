<div class="row table_title">
  <div class="col-lg-4"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 ( 총 : <strong><span id="tot_cnt"><?=number_format($result_list_count)?></span></strong> 건 )</div>
  <p class="col-lg-8 text-right"> &nbsp;&nbsp;
    <!-- <a href="javascript:void(0);" class="btn btn-primary" onclick="do_excel_down();">엑셀 다운로드</a> -->

    <a href="/<?=mapping('product')?>/product_reg" class="btn btn-success">등록</a>
  </p>
</div>

<table class="table table-bordered tr_link">
  <thead>
    <tr>
      <!-- <th width="50"><input type="checkbox" onclick="chkBox(this.checked)"></th> -->
      <th width="30">No</th>
      <th width="100">대표이미지</th>      
      <th>상품명</th>   
      <th>판매가</th>
      <th width="150">노출여부</th>      
      <th width="180">등록일자</th>
    </tr>
  </thead>
  <tbody>
    <?php
      if(!empty($result_list)){
        foreach($result_list as $row){
    ?>
    <tr>
      <!-- <td>
        <input type="checkbox"  name="checkbox" value="<?=$row->product_idx?>">
      </td> -->
      <td>
        <?=$no--?>
      </td>
      <td>
        <img  src="<?=$row->product_img_path?>"  width="50px" height="auto">
      </td>

      <td>
        <a href="/<?=mapping('product')?>/product_detail?product_idx=<?=$row->product_idx?>&history_data=<?=$history_data?>"><?=$row->product_name?></a>
      </td>
    
      
      <td><?=number_format($row->product_price)?></td>
      <td><?=($row->display_yn =="Y")?"활성화":"비활성화"?></td>
      <td><?=$this->global_function->date_Ymd_dot($row->ins_date);?></td>

    </tr>
    <?php
        }
      }else{
    ?>
     <tr>
      <td colspan="14"><?=no_contents('0')?></td>
    </tr>

    <?php
      }
    ?>
  </tbody>
</table>
</div>

<div class="paging">
<?=$paging?>
</div>
