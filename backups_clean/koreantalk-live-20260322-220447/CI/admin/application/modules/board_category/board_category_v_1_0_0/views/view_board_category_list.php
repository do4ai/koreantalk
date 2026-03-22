<!-- container-fluid : s -->
<div class="container-fluid  wide">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>게시판 카테고리 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <form name="form_default" id="form_default" method="post">
      <table class="search_table">
        <colgroup>
          <col style="width:120px">
          <col style="width:330px">
          <col style="width:120px">
          <col style="width:330px">
        </colgroup>
        <tbody>
        <tr>
        <th >카테고리명</th>
        <td >
          <input name="category_name" id="category_name" class="form-control">
        </td>
        <th >등록일</th>
        <td >
          <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
          <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
        </td>

      </tr>
      
        </tbody>
      </table>
      </form>
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
  })

  function default_list_get(page){
    $('#page_num').val(page);

    var formData = {
      'category_name' :  $('#category_name').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'history_data' : window.history.length,
      'page_num' : page,
    };

    $.ajax({
      url      : "/<?=mapping('board_category')?>/board_category_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  function board_display_yn_mod_up(board_idx, display_yn){

    var formData = {
      "board_idx" : board_idx,
      "display_yn" : display_yn
    };

    $.ajax({
      url      : "/<?=mapping('board')?>/board_display_yn_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success: function(result){
        if(result.code == "0"){
          alert(result.code_msg);
        }else{
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }

</script>
