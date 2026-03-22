<!-- container-fluid : s -->
<div class="container-fluid wide">

  <!-- Page Heading -->
  <div class="page-header">

    <h1><?=$board_category_detail->category_name?> 관리</h1>

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
          <col style="width:15%">
          <col style="width:35%">
          <col style="width:15%">
          <col style="width:35%">
        </colgroup>
        <tbody>
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

          <tr>
            <th style="text-align:center;">회원명</th>
            <td>
              <input name="member_nickname" id="member_nickname" class="form-control" autocomplete="off">
            </td>
            <th style="text-align:center;">등록일</th>
            <td >
            <input name="s_date" id="s_date" class="form-control datepicker" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
              <input name="e_date" id="e_date" class="form-control datepicker" placeholder="" autocomplete="off" readonly>&nbsp;<i class="fa fa-calendar-o"></i>

            </td>
          </tr>
          <tr>
            <th style="text-align:center;">제목</th>
            <td>
              <input name="title" id="title" class="form-control" autocomplete="off">
            </td>
            <th style="text-align:center;">내용</th>
            <td>
              <input name="contents" id="contents" class="form-control" autocomplete="off">
            </td>
          </tr>
    
          <tr>
       
            <th style="text-align:center;">노출여부</th>
            <td colspan=3>
              <label class="radio-inline"><input type="radio" name="display_yn" value="" checked> 전체</label>
              <label class="radio-inline"><input type="radio" name="display_yn" value="N"> 비활성화</label>
              <label class="radio-inline"><input type="radio" name="display_yn" value="Y"> 활성화</label>
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
<input type="text" name="category" id="category" value="<?=$category?>"  style="display:none">

<script>

  $(document).ready(function(){
    setTimeout("default_list_get($('#page_num').val())", 10);
  });

  // 커뮤니티 리스트 가져오기
  function default_list_get(page_num){
    $('#page_num').val(page_num);

    var formData = {
      'title' : $('#title').val(),
      'member_nickname' :  $('#member_nickname').val(),
      'display_yn' :  $("input[name='display_yn']:checked").val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'category' : $('#category').val(),      
      'history_data' : window.history.length,
      'page_num' : page_num,
    };

    $.ajax({
      url      : "/<?=mapping('board')?>/board_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {

        $('#list_ajax').html(result);
      }
    });
  }

  // 상태 수정
  function board_state_mod_up(board_idx, display_yn){

var formData = {
  "board_idx" : board_idx,
  "display_yn" : display_yn
};

$.ajax({
  url      : "/<?=mapping('board')?>/board_state_mod_up",
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
