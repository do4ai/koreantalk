<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>공지사항 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">

      <section>
        <form name="form_default" id="form_default" method="post">
        <?
          if($this->site_code !=""){
        ?>
        <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
        <?}?>
        	<table class="table table-bordered td_left">
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
              <th><span class="text-danger">*</span> 사이트 </th>
              <td>
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
              <th><span class="text-danger">*</span> 게시판 종류 </th>
              <td>
               <select name="category" id="category" class="form-control ">
                <option value="">선택</option>
                  <option value="1">커뮤니티</option>
                  <option value="2">결혼</option>
                  <option value="3">유학</option>
                  <option value="4">근로자</option>
                  <option value="5">동포</option>
                </select>
              </td>
            </tr>
            <?}?>
              <tr>
                <th> <span class="text-danger">*</span> 제목</th>
                <td colspan="3">
                  <input name="title" id="title" type="text" class="form-control" >
                </td>
              </tr>
              <tr>
                <th>
                  사진[가로]
                  <p class="text-info">[670px ]</p>
                  <input type="button" class="btn btn-xs btn-default" value="파일업로드" onclick="file_upload_click('img','image','5','150');" style="margin-bottom:10px">
                </th>
                <td colspan="3">
                  <div class="view_img mg_btm_20">
                    <ul class="img_hz" id="img"></ul>
                  </div>
                </td>
              </tr>
              <tr>
                <th><span class="text-danger">*</span> 내용</th>
                <td colspan="3">
                  <div class="editor_area btn-editor" style="width:100%">
                    <textarea class="input-block-level" id = "contents" name="contents"></textarea>
                  </div>
                </td>
              </tr>
              <tr>
                <th>상태</th>
                <td>
                  <select id="display_yn" name="display_yn" class="form-control w_auto">
                    <option value="N">비활성화</option>
                    <option value="Y">활성화</option>
                  </select>
                </td>
                <th>글 순서</th>
                <td >
                  <input name="board_notice_order_no" id="board_notice_order_no" type="text" class="form-control" value="0">
                </td>
              </tr>
            </tbody>
          </table>
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
<script>

  // 써머노트 셋팅
  var summernote_id = 'contents';
  $('#'+summernote_id).summernote({
  height: 440,
  fontNames: [ 'NotoSansKR-Regular']
  });

  function default_reg(){
    var text = $('#contents').summernote('code'); // 써머노트 내용 가져오기
    text = $('<div>').html(text).text(); // HTML 태그 제거하고 텍스트만 추출

    var formData = {		
      'category' : $('#category').val(),
      'title' : $('#title').val(),
      'contents' : $('#contents').val(),
      'site_code' : $('#site_code').val(),
      'category' : $('#category').val(),
      'display_yn' : $('#display_yn').val(),
      'board_notice_order_no' : $('#board_notice_order_no').val(),
      'contents_text' : text,
      'board_img' : get_checkbox_value('img')
    }

    $.ajax({
      url      : "/<?=mapping('board_notice')?>/board_notice_reg_in",
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
        // 0:실패 1:성공
        if(result.code == -2) {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.href ='/<?=mapping('board_notice')?>/board_notice_list';
        }
      }
    });
  }

</script>
