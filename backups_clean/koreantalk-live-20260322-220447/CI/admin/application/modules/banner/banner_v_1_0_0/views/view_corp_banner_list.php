  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>자제 업체 배너 관리</h1>
    </div>

    <!-- search : s -->
     <div class="bg_wh mt20">
       <div class="table-responsive">
         <form name="form_default" id="form_default" method="post">
         <input type="hidden" name="page_num" id="page_num" value="1">
         <input type="hidden" name="banner_type" id="banner_type" value="1">
         <table class="search_table">
           <colgroup>
           	<col style="width:15%">
           	<col style="width:35%">
           	<col style="width:15%">
           	<col style="width:35%">
           </colgroup>
           <tbody>
             <tr>
               <th >제목</th>
               <td colspan=3>
                 <input name="title" id="title" class="form-control">
               </td>

             </tr>

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
      'banner_type' :$('#banner_type').val(),
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
