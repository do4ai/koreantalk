<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>커뮤니티 댓글 신고관리</h1>
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
            <th>신고 한 회원 닉네임</th>
            <td>
              <input name="member_nickname" id="member_nickname" class="form-control">
            </td>
            <th>제목</th>
            <td>
              <input name="title" id="title" class="form-control">
            </td>

          </tr>
          <tr>
            <th>신고유형</th>
            <td colspan=3>
              <label class="checkbox-inline"><input type="radio" name="allCheck" id="allCheck" checked value="" >전체</label>
              <label class="checkbox-inline"><input type="radio" name="report_type" value="0" >욕설, 비방글 </label>
              <label class="checkbox-inline"><input type="radio" name="report_type" value="1" >음란성글</label>
              <label class="checkbox-inline"><input type="radio" name="report_type" value="2" >기타</label>

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
<input type="text" name="board_type" id="board_type" value="0"  style="display:none">
<input type="text" name="page_num" id="page_num" value="1"  style="display:none">
<script>

  $(document).ready(function(){
    setTimeout("default_list_get($('#page_num').val())", 10);
  })

  function default_list_get(page){
    $('#page_num').val(page);

    var formData = {
      'member_nickname' : $('#member_nickname').val(),
      'report_type' :  $("input[name='report_type']:checked").val(),
      'title' : $('#title').val(),
      'board_type' : $('#board_type').val(),
      'page_num' : page,
    };

    $.ajax({
      url      : "/<?=mapping('board_reply_report')?>/board_reply_report_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  $(function(){
    //전체선택 체크박스 클릭
    $("#allCheck").click(function(){
      //만약 전체 선택 체크박스가 체크된상태일경우
      if($("#allCheck").prop("checked")) {
        //해당화면에 전체 checkbox들을 체크해준다
        $("input[type=checkbox]").prop("checked",true);
        // 전체선택 체크박스가 해제된 경우
      } else {
        //해당화면에 모든 checkbox들의 체크를해제시킨다.
        $("input[type=checkbox]").prop("checked",false);
      }
    });
    // 체크박스 요소 선택 시 전체선택 체크박스 해제
    $("input[name=report_type]").click(function(){
      $("#allCheck").prop("checked",false);
    });
  });


  function chk_box(bool) {
    var obj = document.getElementsByName("checkbox");
    for (var i=0; i<obj.length; i++) {
      obj[i].checked = bool;
    }
  }


</script>
