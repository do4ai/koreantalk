<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1><?=($result->board_type=="0")? "커뮤니티 ":"얼쑤토리";?> 상세</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <section>
        <form name="form_default" id="form_default" method="post">
          <table class="table table-bordered td_left">
            <colgroup>
              <col style="width:15%">
              <col style="width:35%">
              <col style="width:15%">
              <col style="width:35%">
            </colgroup>
            <tbody>
              <tr>
                <th><span class="text-danger">*</span> 제목</th>
                <td colspan=3>
                  <input name="title" id="title" type="text" class="form-control" value="<?=$result->title?>" style="width:500px">
                </td>
              </tr>
              <tr>
              <th>
                대표이미지
                <p>1000*가변</p>
                <input type="button" class="btn btn-xs btn-default" value="파일업로드"  onclick="file_upload_click('img','image','10','150');" style="margin-bottom:10px">
              </th>
              <td colspan=3>
                <div class="view_img mg_btm_20">
                  <ul class="img_hz" id="img">
                    <?php if($result->board_img != ""){ ?>
                        <li id="id_file_img_0" style="display:inline-block;">
                          <img src="/images/btn_del.gif" style="width:15px;cursor:pointer" onclick="file_upload_remove('img_0')"/><br>
                          <img class="preview_img" src="<?=$result->board_img?>" style="width:150px">
                          <input type="hidden" name="img_path[]" id="img_path[]" value="<?=$result->board_img?>"/>
                          <input type="checkbox" name="img"  value="<?=$result->board_img?>" checked style="display:none"/ >
                     <?php } ?>

                  </ul>
                </div>
              </td>
            </tr>
         
            <tr>
              <th><span class="text-danger">*</span> 내용</th>
                <td colspan=3>
                <textarea name="contents" id="contents" style="width:100%; height:100px;" placeholder="내용을 입력하세요." class="input_default"><?=$result->contents?></textarea>
              </td>
            </tr>

              <tr>
                <th> 조횟수</th>
                <td ><?=$result->view_cnt?></td>
                <th> 좋아요수</th>
                <td ><?=$result->like_cnt?></td>
              </tr>
            </tbody>
          </table>
          <div class="text-right" style="float:right;height:40px">
            <a class="btn btn-gray" href="javascript:void(0)" onclick="default_list();">목록</a>
            <a class="btn btn-danger" href="javascript:void(0)" onClick="default_del()">삭제</a>
            <a class="btn btn-success" href="javascript:void(0)" onclick="default_mod()">수정</a>

          </div>

        <table class="table table-bordered td_left">
          <colgroup>
            <col style="width:15%">
            <col style="width:35%">
            <col style="width:15%">
            <col style="width:35%">
          </colgroup>
              <tr>
                <th colspan="4">댓글 & 답글</th>
              </tr>
              <tr>
                <td colspan="4" >
                  <table class="table table-bordered td_left">
                    <colgroup>
                      <col style="width:15%">
                      <col style="width:35%">
                      <col style="width:15%">
                      <col style="width:35%">
                    </colgroup>
                    <tbody>
                      <tr>
                        <th>닉네임</th>
                        <td ><input name="member_nickname" id="member_nickname" class="form-control" autocomplete="off"></td>
                        <th>정렬 기준</th>
                        <td >
                          <select class="form-control" style="width:220px" id="orderby" name="orderby" >
                            <option value="0">신고수 내림차순 정렬</option>
                            <option value="1">신고수 올림차순 정렬</option>
                            <option value="2">등록일자 내림차순 정렬</option>
                            <option value="3">등록일자 올림차순 정렬</option>
                       </select>
                     </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="text-center mt20">
                    <a class="btn btn-success" href="javascript:void(0)" onclick="reply_list_get(1);">검색</a>
                  </div>
                  <div class="text-center mt20">
                  </div>
                  <div class="bg_wh mb20" id="reply_ajax">  <div>
                </td>
              </tr>
            </tbody>
          </table>

          <input type="text" name="board_img" id="board_img" value=""  style="display:none">
          <input type="text" name="board_img_detail" id="board_img_detail" value=""  style="display:none">
          <input type="text" name="board_idx" id="board_idx" value="<?=$result->board_idx?>" style="display:none;">
          <input type="text" name="board_type" id="board_type" value="<?=$board_type?>" style="display:none;">
          <input type="text" name="board_reply_idx" id="board_reply_idx" value="" style="display:none;">

        </form>
      </section>


      <div class="row table_title">
        <div class="col-lg-4"></div>
        <div class="col-lg-8 text-right">
          <input name="reply_comment" id="reply_comment" class="form-control" style="width:300px" autocomplete="off">
          <a class="btn btn-info" href="javascript:void(0)" onclick="reply_reg_in();">댓글등록</a>

          </div>
      </div>



    </div>
  </div>
  <!-- body : e -->
</div>
<!-- container-fluid : e -->



<!-- modal layerpop3 : s -->
<div class="modal fade" id="layerpop3" >
  <div class="modal-dialog" style="width:750px;">
    <div class="modal-content" style="width:750px;">
      <!-- header -->
      <div class="modal-header" >
        <!-- 닫기(x) 버튼 -->
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">답글등록하기</h4>
      </div>
      <!-- body -->
      <div class="modal-body">
        <div class="table-responsive">
          <table class="search_table">
            <colgroup>
              <col style="width:150px">
              <col style="width:250px">
              <col style="width:150px">
              <col style="width:250px">
            </colgroup>
            <tbody>
              <tr>
                <th style="text-align:center;">답글내용</th>
                <td colspan="3">
                  <textarea name="pop_reply_comment" id="pop_reply_comment" style="width:100%;height:50px;" placeholder="답글내용를 입력해주세요." class="form-control w_auto"></textarea>
                </td>
              </tr>
      			</tbody>
          </table>
          <div class="text-center mt20" style="">
            <a href="javascript:void(0)" class="btn btn-success" data-dismiss="modal" id="btn_cancel_3">취소</a>
            <a href="javascript:void(0)" onclick="reply_reply_reg_in();" class="btn btn-success">답글작성</a>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>
<!-- modal layerpop3 : e -->




<input type="text" name="page_num" id="page_num" value="1" style="display:none;">
<script>

  reply_list_get(1);
  
  $('#earth_board_detail').addClass('active');

    // 댓글 등록
    function reply_reg_in(){
      var form_data = {
        'board_idx' :  $('#board_idx').val(),
        'board_reply_idx' :  "",
        'reply_comment' :  $('#reply_comment').val(),
      };

      $.ajax({
        url      : "/<?=mapping('board')?>/board_comment_reg_in",
        type     : "POST",
        dataType : "json",
        async    : true,
        data     : form_data,
        success: function(result) {
          if(result.code == -1){
            alert(result.code_msg);
            return;
          }
          if(result.code == 0){
            alert(result.code_msg);
          }else{
            console.log(result);
            //alert(result.code_msg);
            $('#reply_comment').val('');
            reply_list_get($('#page_num').val());
          }
        }
      });
    }

    // 댓글:답글 댓글키 세팅
    function set_board_reply_idx(str){
     $('#board_reply_idx').val(str);
    }

    // 댓글:답글 등록
    function reply_reply_reg_in(){
      var form_data = {
        'board_idx' :  $('#board_idx').val(),
        'board_reply_idx' :  $('#board_reply_idx').val(),
        'reply_comment' :  $('#pop_reply_comment').val(),
      };

      $.ajax({
        url      : "/<?=mapping('board')?>/board_comment_reg_in",
        type     : "POST",
        dataType : "json",
        async    : true,
        data     : form_data,
        success: function(result) {
          if(result.code == -1){
            alert(result.code_msg);
            return;
          }
          if(result.code == 0){
            alert(result.code_msg);
          }else{
            console.log(result);
            //alert(result.code_msg);
            $('#pop_reply_comment').val('');
            $('#btn_cancel_3').trigger('click');
            reply_list_get($('#page_num').val());
          }
        }
      });
    }

  function reply_list_get(page){

    var formData = {
      'board_idx' :  $('#board_idx').val(),
      'orderby' :  $('#orderby').val(),
      'board_type' :  $('#board_type').val(),
      'member_nickname' :  $('#member_nickname').val(),
      'page_num' : page
    };

    $.ajax({
      url      : "/<?=mapping('board')?>/reply_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#reply_ajax').html(result);
      }
    });
  }

  // 공지사항 수정
  function default_mod() {
    $('#board_img').val(get_checkbox_value('img'));
    $('#board_img_detail').val(get_checkbox_value('img_detail'));

    $.ajax({
      url      : "/<?=mapping('board')?>/board_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : $("#form_default").serialize(),
      success : function(result) {
        if(result.code == '-1') {
          alert(result.code_msg);
        }else {
          alert("수정되었습니다.");
          default_list();
        }
      }
    });
  }

  function default_del(){

    if(!confirm("삭제하시겠습니까?")){
      return;
    }

    $.ajax({
      url      : "/<?=mapping('board')?>/board_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "board_idx" : $('#board_idx').val()
      },
      success: function(result) {
        if(result.code == '-1') {
          alert(result.code_msg);
        }else{
          alert('삭제되었습니다.');
          default_list();
        }
      }
    });
  }



  // 댓글 노출여부 상태 수정
  function board_reply_display_mod_up(board_reply_idx,display_yn){

  var page_num = $('#page_num').val();

  var formData = {
    "board_reply_idx" : board_reply_idx,
    "display_yn" : display_yn,
  };

  $.ajax({
    url      : "/<?=mapping('board')?>/board_reply_display_mod_up",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data     : formData,
    success: function(result){
      if(result.code == "0"){
        alert(result.code_msg);
      }else{
        alert(result.code_msg);
        reply_list_get(page_num);
      }
    }
  });
  }

  function default_list(){
    history.back(<?=$history_data?>);
  }
</script>


<script>
$(function() {
/* 이미지 업로드시 사용 */
  $('#contents').summernote({
    height:200,
    lang: 'ko-KR',
    dialogsInBody: false,
    callbacks: {
          onImageUpload: function(files, editor, welEditable) {
            for (var i = files.length - 1; i >= 0; i--) {
              sendFile(files[i], editor, welEditable);
            }
          }
        }
  });
});

//summernote editor contents parts
var postForm = function() {
   var content = $('textarea[name="contents"]').html($('#contents').code());
}

//에디터 이미지 등록
function sendFile(file,editor, welEditable) {
      var form_data = new FormData();
      form_data.append('file', file);
      form_data.append('id', 'id');
      form_data.append('device', 'image');
      $.ajax({
        data: form_data,
        dataType:'json',
        type: "POST",
        url: '/common/upload_file_json',
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(result) {
          $('textarea[name="contents"]').summernote('insertImage',  result.url);
        }
    });
 }
</script>
