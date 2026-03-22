<!-- container-fluid : s -->
<div class="container-fluid" style="width: 1200px;">

	<!-- Page Heading -->
	<div class="page-header">
		<h1>전자책 구매 내역  관리</h1>
	</div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">
      <form name="form_default" id="form_default" method="post">
    		<table class="search_table">
					<colgroup>
						<col style="width:15%">
						<col style="width:35%">
						<col style="width:15%">
						<col style="width:35%">
					</colgroup>
    			<tbody>
		
						<tr>
					
							<th style="text-align:center;"> 아이디</th>
							<td>
								<input name="order_id" id="order_id" class="form-control" autocomplete="off">
							</td>
              <th style="text-align:center;">주문자 명</th>
	            <td>
								<input name="order_name" id="order_name" class="form-control" autocomplete="off" >
	            </td>	            
	          </tr>			
  
						<tr>
		
	           <th style="text-align:center;">주문번호</th>
	            <td>
								<input name="order_number" id="order_number" class="form-control" autocomplete="off" >
	            </td>	      
						<th style="text-align:center;">상품명</th>
	            <td>
								<input name="product_name" id="product_name" class="form-control" autocomplete="off" >
	            </td>	              
	          </tr>
            <?
            if($this->site_code ==""){
            ?>
            <tr>
		          
 
	      
							<th style="text-align:center;">주문일자</th>
	            <td >
								<input class="form-control datepicker" name="s_date" id="s_date" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
								<input class="form-control datepicker" name="e_date" id="e_date" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>
	            </td>
              <th style="text-align:center;">사이트</th>
              <td >
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>	            
	          </tr>
            <?}else{?>
             <tr>
		
	      
							<th style="text-align:center;">주문일자</th>
	            <td colspan=3>
								<input class="form-control datepicker" name="s_date" id="s_date" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
								<input class="form-control datepicker" name="e_date" id="e_date" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>

                <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
	            </td>	            
	          </tr>
            <?}?>

		
            
    			</tbody>
    		</table>
      </form>

  		<div class="text-center mt20">
  			<a href="javascript:void(0)" class="btn btn-success" onclick="default_list_get(1);">검색</a>
  		</div>

  	</div>
    <!-- search : e -->

  	<div class="table-responsive">
      <div id="list_ajax"></div>
  	</div>
  </div>
  <!-- body : e -->
</div>
<input type="text" name="page_num" id="page_num" value="1" style="display:none">

<script>
	checkall_func('order_state_all', 'order_state');

	$(document).ready(function(){
		setTimeout("default_list_get($('#page_num').val())", 10);
	});

	// 리뷰 리스트 가져오기
  function default_list_get(page_num){

    $('#page_num').val(page_num);

		var form_data = {
			'site_code' : $('#site_code').val(),
			'order_id' : $('#order_id').val(),
			'order_name' : $('#order_name').val(),
			'order_number' : $('#order_number').val(),
			'product_name' : $('#product_name').val(),
			's_date' : $('#s_date').val(),
			'e_date' : $('#e_date').val(),
			'order_type' :  $("input[name='order_type']:checked").val(),
			'order_state' : get_checkbox_value('order_state'),
			'history_data' : window.history.length,
			'page_num' : page_num
		};

    $.ajax({
      url      : "/<?=mapping('order')?>/order_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : form_data,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  var download_pdf = function(order_idx) {
    location.href ="/<?=mapping('order')?>/download_pdf?order_idx="+order_idx;
  }

  var do_excel_down = function() {
    document.form_default.action ="/<?=mapping('order')?>/order_list_excel";
    document.form_default.submit();
  }
</script>
