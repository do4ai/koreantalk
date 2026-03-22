<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>공지사항</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <form name="form_default" id="form_default" method="post">
       <?
      if($this->site_code !=""){
      ?>
        <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
      <?}?>
      <table class="search_table">
        <colgroup>
          <col style="width:10%">
          <col style="width:35%">
          <col style="width:10%">
          <col style="width:35%">
        </colgroup>
        <tbody>
          <tr>
            <th>제목</th>
            <td>
              <input name="title" id="title" class="form-control">
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
      'title' :  $('#title').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'site_code' : $('#site_code').val(),
      'notice_type' :  $("input[name='notice_type']:checked").val(),
      'history_data' : window.history.length,
      'page_num' : page
    };

    $.ajax({
      url      : "/<?=mapping('notice')?>/notice_list_get",
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
      url      : "/<?=mapping('notice')?>/notice_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "notice_idx" : selected_idx
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

  // 공지사항 상태 수정
  function notice_state_mod_up(notice_idx, notice_state){

    var formData = {
      "notice_idx" : notice_idx,
      "notice_state" : notice_state
    };

    $.ajax({
      url      : "/<?=mapping('notice')?>/notice_state_mod_up",
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

  function notice_push_mod_up(notice_idx,notice_type){

    var formData = {
      "notice_idx" : notice_idx,
      "notice_type" : notice_type,
    };

    $.ajax({
      url      : "/<?=mapping('notice')?>/notice_push_mod_up",
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
