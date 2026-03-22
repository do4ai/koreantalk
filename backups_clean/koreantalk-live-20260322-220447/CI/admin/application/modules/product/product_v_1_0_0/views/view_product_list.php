  <div class="container-fluid wide">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>상품 관리</h1>

    </div>

    <!-- body : s -->   
       <form name="form_default" id="form_default">
      <div class="bg_wh mt20">
        <!-- search : s -->
        <div class="table-responsive">
          <table class="search_table">
            <colgroup>
            <col style="width:15%">
            <col style="width:35%">
            <col style="width:15%">
            <col style="width:35%">
            </colgroup>
            <tbody>
              <tr>
                <th>상품명</th>
                <td colspan=3>
                  <input type="text" class="form-control" name="product_name" id="product_name">
                </td>
               
              </tr>

              <tr>
                <th>노출여부</th>
                <td>
                  <input type="radio" name="display_yn" value="" checked > 전체
                  <input type="radio" name="display_yn" value="Y" > 활성화
                  <input type="radio" name="display_yn" value="N" > 비활성화
                </td>
               
                 <th>등록일</th>
                <td>
                  <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>
              </tr>

           
            </tbody>
          </table>
          </form>



          <div class="text-center mt20">
            <a href="javascript:void(0);" onclick="default_list_get(1);" class="btn btn-success">검색</a>
          </div>
        </div>
      </div>
      <!-- search : e -->



      <div class="bg_wh mt20">
        <div class="table-responsive">
          <!-- list_get : s -->
          <div id="list_ajax"></div>
          <!-- list_get : e -->
        </div>
      </div>
      <input type="text" style="display:none" id="page_num" name="page_num" value="1">
    </form>
  </div>
  <!-- container-fluid : e -->

<script>
  $(function(){
    default_list_get($("#page_num").val());
  });

  function default_list_get(page_num){

    var form_data = {
      'product_name' : $('#product_name').val(),
      'display_yn' :  $("input[name='display_yn']:checked").val(),     
      'product_b_category_idx' : $('#product_b_category_idx').val(),
      'product_m_category_idx' : $('#product_m_category_idx').val(),
      'product_s_category_idx' : $('#product_s_category_idx').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'page_num' : page_num,
      'history_data' : window.history.length
    };

    $.ajax({
      url      : "/<?=mapping('product')?>/product_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : form_data,
      success  : function(result) {
        $('#list_ajax').html(result);
        $("#page_num").val(page_num)
      }
    });
  }


  var category_m_list = function(category_idx) {

  $.ajax({
    url: "/<?=mapping('product')?>/category_list",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: {
        // "product_b_category_idx" : $("#product_b_category_idx").val(),
        "category_idx" : category_idx
    },
    success: function(dom){
      var selectStr = "";

      $('#product_m_category_idx').html("<option value=''>전체</option>");
      if(dom.length != 0) {
        for(var i = 0; i < dom.length; i ++) {
          selectStr += "<option value='"+ dom[i].category_management_idx  + "'>" + dom[i].category_name + "</option>";
        }
        $('#product_m_category_idx').append(selectStr);
      } else {
        // $('#product_m_category_idx').html("<option value=''>전체</option>");
      }
    }
  });
}

  var category_s_list = function(category_idx) {

  $.ajax({
    url: "/<?=mapping('product')?>/category_list",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: {
        // "product_b_category_idx" : $("#product_b_category_idx").val(),
        "category_idx" : category_idx
    },
    success: function(dom){
      var selectStr = "";

      $('#product_s_category_idx').html("<option value=''>전체</option>");
      if(dom.length != 0) {
        for(var i = 0; i < dom.length; i ++) {
          selectStr += "<option value='"+ dom[i].category_management_idx  + "'>" + dom[i].category_name + "</option>";
        }
        $('#product_s_category_idx').append(selectStr);
      } else {
        // $('#product_m_category_idx').html("<option value=''>전체</option>");
      }
    }
  });
}

var do_excel_down = function() {
  document.form_default.action ="/<?=mapping('product')?>/product_list_excel";
  document.form_default.submit();
}



function checked_product_mod_up(request_type){
  if(request_type!="5"){
    var selected_idx = get_checkbox_value('checkbox');
  }else{
    var selected_idx = get_checkbox_value_with_others('checkbox','product_recommend');
  }

  if(selected_idx.length<1){
    alert("선택된 항목가 없습니다.");
    return  false;
  }

  var form_data = {
    'request_type' : request_type,
    'product_idx' :  selected_idx
  };

  $.ajax({
  	url: "/<?=mapping('product')?>/checked_product_mod_up",
  	type: 'POST',
  	dataType: 'json',
  	async: true,
  	data: form_data,
  	success: function(result){
  	  if(result.code == '-1'){
    		alert(result.code_msg);
    		$("#"+result.focus_id).focus();
    		return;
  	  }
  	  // 0:실패 1:성공
  	  if(result.code == '-2') {
  		    alert(result.code_msg);
  	  } else {
    		alert(result.code_msg);
    		get_page();
  	  }
    }
  });

}

</script>
