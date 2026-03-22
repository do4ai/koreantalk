<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>사이트 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
    
      <input type="hidden" name="page_num" id="page_num" value="1">
      <table class="search_table">
        <colgroup>
          <col style="width:100px">
          <col style="width:350px">
          <col style="width:100px">
          <col style="width:350px">
        </colgroup>
        <tbody>
          <tr>
            <th>사이트명</th>
            <td>
              <input name="site_name" id="site_name" class="form-control">
            </td>
            <th>등록일</th>
            <td>
              <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
              <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
            </td>
          </tr>
        </tbody>
      </table>
   
      <div class="text-center mt20">
        <a class="btn btn-success" href="javascript:void(0)" onclick="default_list_get(1);">검색</a>
      </div>
    </div>
    <!-- search : e -->

    <div class="bg_wh mb20" id="list_ajax"></div>

  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->
<input type="text" name="page_num" id="page_num" value="1"  style="display:none">
<script>
  $(document).ready(function(){
    setTimeout("default_list_get($('#page_num').val())", 10);
  });  

  function default_list_get(page){

    var formData = {
      'site_name' :  $('#site_name').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'history_data' : window.history.length,
      'page_num' : page
    };

    $.ajax({
      url      : "/<?=mapping('site')?>/site_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

 

</script>
