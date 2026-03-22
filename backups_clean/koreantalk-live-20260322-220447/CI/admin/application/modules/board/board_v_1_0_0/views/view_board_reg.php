<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>얼스게시판</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">

      <section>
        <form name="form_default" id="form_default" method="post">
        	<table class="table table-bordered td_left">
            <colgroup>
              <col style="width:150px">
              <col style="*">
            </colgroup>
        		<tbody>

              <tr>
                <th><span class="text-danger">*</span> 제목</th>
                <td>
                  <input name="title" id="title" type="text" class="form-control" style="width:500px">
                </td>
              </tr>

              <tr>
                <th>
                  대표이미지
                  <p>1000*가변</p>
                  <input type="button" class="btn btn-xs btn-default" value="파일업로드"  onclick="file_upload_click('img','image','10','150');" style="margin-bottom:10px">
                </th>
                <td>
                  <div class="view_img mg_btm_20">
                    <ul class="img_hz" id="img"></ul>
                  </div>
                </td>
              </tr>
            
              <tr>
                <th><span class="text-danger">*</span> 내용</th>
                <td>
                  <textarea name="contents" id="contents" style="width:100%; height:100px;" placeholder="내용을 입력하세요." class="input_default"></textarea>
                </td>
              </tr>   
            
            </tbody>
          </table>
          <input type="text" name="board_img" id="board_img" value=""  style="display:none">
          <input type="text" name="board_img_detail" id="board_img_detail" value=""  style="display:none">
        </form>
      </section>

      <div class="row">
        <div class="col-lg-12 text-right">
          <a href="/<?=mapping('notice')?>" class="btn btn-gray">취소</a>
          <a href="javascript:void(0)" onclick="default_reg();" class="btn btn-success">등록</a>
        </div>
      </div>

    </div>
  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->
<script>
  // 저장 스크립트
  function default_reg(){
    $('#board_img').val(get_checkbox_value('img'));
    $('#board_img_detail').val(get_checkbox_value('img_detail'));

    $.ajax({
      url      : "/<?=mapping('board')?>/board_reg_in",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : $("#form_default").serialize(),
      success: function(result){
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // 0:실패 1:성공
        if(result.code == 0) {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.href ='/<?=mapping('board')?>/earth_board_list';
        }
      }
    });
  }



</script>
