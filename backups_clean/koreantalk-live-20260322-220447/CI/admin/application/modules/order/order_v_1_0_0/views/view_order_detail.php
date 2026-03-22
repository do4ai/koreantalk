<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>거래 내역 상세</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">

      <section>
        <div class="row table_title">
          <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;거래정보</div>
          <div class="col-lg-6 text-right"> &nbsp;주문일 : <?=$this->global_function->date_YmdHi_Hyphen($result->ins_date)?></div>
        </div>
      	<table class="table table-bordered td_left">
          <colgroup>
        	<col style="width:15%">
        	<col style="width:35%">
        	<col style="width:15%">
        	<col style="width:35%">
          </colgroup>
      		<tbody>

            <tr>
	            <th style="text-align:center;">주문번호</th>
	            <td><?=$result->order_number?></td>
             <th style="text-align:center;">사이트</th>
	            <td><?=$result->site_name?></td>            
	          </tr>
            <tr>
	            <th style="text-align:center;">주문자 id</th>
	            <td><?=$result->order_id?></td>
              <th style="text-align:center;">주문명</th>
	            <td><?=$result->order_name?></td>	            
	          </tr>
            <tr>
	            <th style="text-align:center;">상품명</th>
	            <td><?=$result->product_name?></td>
              <th style="text-align:center;">판매가</th>
	            <td><?=number_format($result->product_price)?></td>	            
	          </tr>

           
            
          </tbody>
      	</table>

       

       






      </section>
      <div class="text-right">
        <a href="javascript:void(0)" onclick="default_list()" class="btn btn-gray">목록</a>

       
      </div>
  	</div>
  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->

<input name="order_idx" id="order_idx" type="hidden" value="<?=$result->order_idx?>">
<input name="order_state" id="order_state" type="hidden" value="<?=$result->order_state?>">

<script>
  let order_idx ="<?=$result->order_idx?>";
  // 리뷰 목록
  function default_list(){
    history.back(<?=$history_data?>);
  }
  
  //다운로드
  var file_download = function() {
    location.href ="/<?=mapping('order')?>/file_download?order_idx="+order_idx;
  }

  // pdf 다운로드
  var download_pdf = function() {
    location.href ="/<?=mapping('order')?>/download_pdf?order_idx="+order_idx;
  }
 
  // 취소
  function default_cancel() {
    if(!confirm('정말 취소 하시겠습니까? 상태를 복구 할 수 없습니다.')){
      return;
    }
    
    var order_idx = document.querySelector('#order_idx').value;
    
    var form_data = {
      "order_idx" : order_idx,
      "order_state" : 3
    };
  
    $.ajax({
      url      : "/<?=mapping('order')?>/order_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : form_data,
      success: function(result){
        // -1:유효성 검사 실패
        if(result.code == -1){
          alert(result.code_msg);
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.reload();
        }
      }
    });
  }

  // 취소
  function default_end() {
    if(!confirm('완료상태로 변경하시겠습니까?')){
      return;
    }
    
    var order_idx = document.querySelector('#order_idx').value;
    
    var form_data = {
      "order_idx" : order_idx,
      "order_state" : 2
    };
  
    $.ajax({
      url      : "/<?=mapping('order')?>/order_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : form_data,
      success: function(result){
        // -1:유효성 검사 실패
        if(result.code == -1){
          alert(result.code_msg);
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.reload();
        }
      }
    });
  }

  // 진행
  function default_prossessing() {
    if(!confirm('진행상태로 변경하시겠습니까?')){
      return;
    }
    
    var order_idx = document.querySelector('#order_idx').value;
    
    var form_data = {
      "order_idx" : order_idx,
      "order_state" : 1
    };
  
    $.ajax({
      url      : "/<?=mapping('order')?>/order_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : form_data,
      success: function(result){
        // -1:유효성 검사 실패
        if(result.code == -1){
          alert(result.code_msg);
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.reload();
        }
      }
    });
  }

  $(document).ready(function() {
    $("a[name='single_image']").fancybox();
  });

</script>
