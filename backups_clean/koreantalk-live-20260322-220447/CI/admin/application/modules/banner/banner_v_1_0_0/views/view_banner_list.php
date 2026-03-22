  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>배너 관리</h1>
    </div>

    <!-- search : s -->
     <div class="bg_wh mt20">
       <div class="table-responsive">
         <form name="form_default" id="form_default" method="post">
         <input type="text" name="page_num" id="page_num" value="1"  style="display: none;">
   
         <table class="search_table">
           <colgroup>
           	<col style="width:15%">
           	<col style="width:35%">
           	<col style="width:15%">
           	<col style="width:35%">
           </colgroup>
           <tbody>
           <tr>
							<th style="text-align:center;">배너명</th>
	            <td>
								<input name="corp_id" id="corp_id" class="form-control" autocomplete="off" >
	            </td>
							<th style="text-align:center;">등록일</th>
							<td>
              <input class="form-control datepicker" name="s_date" id="s_date" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
							<input class="form-control datepicker" name="e_date" id="e_date" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>
							</td>	             
	          </tr>
            <?
            if($this->site_code ==""){
            ?>
						<tr>
             <th style="text-align:center;">사이트</th>
              <td>
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
							<th style="text-align:center;">노출 여부</th>
							<td >
							    <label class="radio-inline"><input type="radio" name="state" id="state" value="" checked> 전체</label>                  
                  <label class="radio-inline"><input type="radio" name="state" id="state" value="1"> 활성화</label>
                  <label class="radio-inline"><input type="radio" name="state" id="state" value="0"> 비활성화</label>
    
							</td>	            
	          </tr>
            <?}else{?>
     
           	<tr>
   						<th style="text-align:center;">노출 여부</th>
							<td colspan=3>
							    <label class="radio-inline"><input type="radio" name="state" id="state" value="" checked> 전체</label>                  
                  <label class="radio-inline"><input type="radio" name="state" id="state" value="1"> 활성화</label>
                  <label class="radio-inline"><input type="radio" name="state" id="state" value="0"> 비활성화</label>
                  <input type="text" name="site_code" id="site_code" value="<?=$this->site_code?>"  style="display: none;"> 
							</td>	            
	          </tr>
            <?}?>
				

           </tbody>
         </table>
         </form>

         <div class="text-center mt20">
           <a href="javascript:void(0)" onclick="default_list_get('1');" class="btn btn-success">검색</a>
         </div>
       </div>
     </div>
     <!-- search : e -->

    <!-- body : s -->
    <div class="bg_wh mt20">
      <div class="table-responsive">
        <div id="list_ajax">
          <!--리스트-->
        </div>
      </div>
    </div>
    <!-- body : e -->
  </div>
  <!-- container-fluid : e -->


<script>
  var default_list_get = function(page_num){
    $('#page_num').val(page_num);

    var form_data = {
      'title' : $('#title').val(),
			's_date' : $('#s_date').val(),
			'e_date' : $('#e_date').val(),
      'state' : get_checkbox_value('state'),
      'site_code' :$('#site_code').val(),
      'history_data' : window.history.length,
      'page_num' : page_num
    };

    $.ajax({
      url      : "/<?=mapping('banner')?>/banner_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : form_data,
      success  : function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  $(default_list_get('1'));


  function chkBox(bool) {
    var obj = document.getElementsByName("checkbox");
    for (var i=0; i<obj.length; i++) {
      obj[i].checked = bool;
    }
  }

  //선택삭제
  var default_select_del = function(){
    var selected_idx  = get_checkbox_value('checkbox');

    if(selected_idx.length<1){
      alert("선택된 항목가 없습니다.");
      return  false;
    }

    if(!confirm("선택된 배너를 삭제하시겠습니까?")){
      return false;
    }

    $.ajax({
      url      : "/<?=mapping('banner')?>/banner_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "banner_idx" : selected_idx
      },
      success: function(result) {
        if(result.code == '-1'){
  				alert(result.code_msg);
  				$("#"+result.focus_id).focus();
  				return;
  			}
  			// -2:실패 1:성공
  			if(result.code == "-2") {
  				alert(result.code_msg);
  			} else {
  				alert(result.code_msg);
  				default_list_get($('#page_num').val());
  			}
      }
    });

  }
</script>
