  <!-- container-fluid : s -->
    <div class="container-fluid wide">
		<!-- Page Heading -->
		<div class="page-header">
			<h1>시작팝업 관리</h1>
		</div>


      <!-- body : s -->
      <div class="bg_wh mt20">

        <!-- search : s -->
      	<div class="table-responsive">
          <form name="form_default" id="form_default" method="post">

      		<table class="search_table" style="width:100%;">
            <colgroup>
            	<col style="width:15%">
            	<col style="width:35%">
            	<col style="width:15%">
            	<col style="width:35%">
            </colgroup>
      			<tbody>
      				<tr>
                <th style="text-align:center;">시작팝업 제목</th>
      					<td >
                  <input name="title" id="title" class="form-control">
                </td>
                <th style="text-align:center;">시작팝업 등록일</th>
                <td>
                  <input name="s_date" id="s_date" class="form-control datepicker" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input name="e_date" id="e_date" class="form-control datepicker" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>
              </tr>
      			</tbody>
      		</table>
          </form>
      		<div class="text-center mt20">
      			<a href="javascript:void(0)" class="btn btn-success" onclick="default_list_get('1')">검색</a>
      		</div>
      	</div>
        <!-- search : e -->

      	<div class="table-responsive" id="list_ajax">
      	</div>
      </div>
      <!-- body : e -->
    </form>
  </div>
  <!-- container-fluid : e -->
<input type="text" name="page_num" id="page_num" value="1"  style="display:none">
<script>

  $(document).ready(function(){
    setTimeout("default_list_get($('#page_num').val())", 10);
  });

  var default_list_get = function(page_num){
    $('#page_num').val(page_num);

    var form_data = {
      'history_data' : window.history.length,
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'title' : $('#title').val(),
      'page_num' : page_num
    };

    $.ajax({
      url      : "/<?=mapping('start_popup')?>/start_popup_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : form_data,
      success  : function(result) {
        $('#list_ajax').html(result);
      }
    });

  }

  function start_popup_state_mod_up(start_popup_idx, state){

    var formData = {
      "start_popup_idx" : start_popup_idx,
      "state" : state
    };

    $.ajax({
      url      : "/<?=mapping('start_popup')?>/start_popup_state_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success: function(result){
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == -2) {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
        }
      }
    });
  }

</script>
