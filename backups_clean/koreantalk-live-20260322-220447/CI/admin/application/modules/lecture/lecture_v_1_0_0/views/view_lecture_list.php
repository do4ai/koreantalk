<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>TOPIK 영상 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <form name="form_default" id="form_default" method="post">
      <table class="search_table">
        <colgroup>
          <col style="width:10%">
          <col style="width:35%">
          <col style="width:10%">
          <col style="width:35%">
        </colgroup>
        <tbody>
          <tr>
            <th>동영상이름</th>
            <td>
              <input name="lecture_name" id="lecture_name" class="form-control">
            </td>
            <th>등록일</th>
            <td>
              <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
              <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
            </td>
          </tr>
            <?
            if($this->site_code ==""){
            ?>
            <tr>
              <th>사이트</th>
              <td colspan=3>
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
            <?}?>

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
  var page_num =  $('#page_num').val();
  default_list_get(page_num);
})

  function default_list_get(page){
    $('#page_num').val(page);

    var formData = {
      'lecture_name' :  $('#lecture_name').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'lecture_type' :  $("input[name='lecture_type']:checked").val(),
      'history_data' : window.history.length,
      'page_num' : page
    };

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  function chk_box(bool) {
    var obj = document.getElementsByName("checkbox");
    for (var i=0; i<obj.length; i++) {
      obj[i].checked = bool;
    }
  }

  // 선택삭제
  var default_select_del = function(){

    var selected_idx =  get_checkbox_value('checkbox');

    if(selected_idx.length<1){
      alert("선택된 항목가 없습니다.");
      return  false;
    }

    if(!confirm("선택된 항목를 삭제하시겠습니까?")){
      return;
    }

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "lecture_idx" : selected_idx
      },
      success: function(result) {
        if(result.code == "0"){
          alert(result.code_msg);
        }else{
          alert("삭제가 완료되었습니다.");
          location.reload();
        }
      }
    });

  }

  // 교육영상 상태 수정
  function lecture_state_mod_up(lecture_idx, lecture_state){

    var formData = {
      "lecture_idx" : lecture_idx,
      "lecture_state" : lecture_state
    };

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_state_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success: function(result){
        if(result.code == "0"){
          alert(result.code_msg);
        }else{
          alert(result.code_msg);
          location.reload();
        }
      }
    });
  }

  function lecture_push_mod_up(lecture_idx,lecture_type){

    var formData = {
      "lecture_idx" : lecture_idx,
      "lecture_type" : lecture_type,
    };

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_push_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success: function(result){
        if(result.code == "0"){
          alert(result.code_msg);
        }else{
          alert(result.code_msg);
          location.reload();
        }
      }
    });
  }

</script>
