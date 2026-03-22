<div class="row table_title">
	<div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong>  건</div>
	<p class="col-lg-6 text-right">
  판매금액 :<?=number_format($order_list_sum->total)?> 원
  </p>
</div>

 <table class="table table-bordered check_wrap">
      <tr>
        <th width="50">No</th>       
        <th width="120">주문번호</th>        
        <th width="150">주문일시</th>
        <th width="120">사이트</th>        
        <th width="*">상품명</th>      
        <th width="120">아이디</th>        
        <th width="120">주문자명</th>        
        <th width="120">휴대폰번호</th>                        
        <th width="120">판매가</th>
        
      </tr>
    </thead>
    <tbody>
      <?php
       
        if(!empty($result_list)){
          foreach($result_list as $row){
      ?>
        <tr>
          <td >
            <?=$no--?>
          </td>
          
          <td >
           <a href="/<?=mapping('order')?>/order_detail?order_idx=<?=$row->order_idx?>&history_data=<?=$history_data?>"><?=$row->order_number?></a>
        
          </td>
          <td >
            <?=$this->global_function->date_YmdHi_hyphen($row->ins_date)?>
          </td>
           <td ><?=$row->site_name?></td>       
          <td ><?=$row->product_name?></td>    
          <td ><?=$row->order_id?></td>    
          <td ><?=$row->order_name?></td>    
          <td ><?=$row->order_phone?></td>
          <td ><?=number_format($row->product_price)?></td>    
           
           </td> 
       
        </tr>
      <?php
          }
        }else{
      ?>
      <tr>
        <td colspan="13">
          <?=no_contents('0')?>
        </td>
      </tr>
      <?php
        }
      ?>
    </tbody>
  </table>

  <?=$paging?>

