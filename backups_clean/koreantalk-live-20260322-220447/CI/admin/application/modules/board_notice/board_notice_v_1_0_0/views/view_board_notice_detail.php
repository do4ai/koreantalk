<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>커뮤니티 공지사항 관리</h1>
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
                    <option value="<?=$row->site_code?>" <?=($result->site_code ==$row->site_code)?"selected":"";?>>
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
                </select>
              </td>
              <th><span class="text-danger">*</span> 게시판 종류 </th>
              <td>
               <select name="category" id="category" class="form-control ">
                <option value="">선택</option>
                  <option value="1" <?=($result->category ==1)?"selected":"";?>>커뮤니티</option>
                  <option value="2" <?=($result->category ==2)?"selected":"";?>>결혼</option>
                  <option value="3" <?=($result->category ==3)?"selected":"";?>>유학</option>
                  <option value="4" <?=($result->category ==4)?"selected":"";?>>근로자</option>
                  <option value="5" <?=($result->category ==5)?"selected":"";?>>동포</option>
                </select>
              </td>
            </tr>
            <?}?>
            
              <tr>
                <th><span class="text-danger">*</span> 제목</th>
                <td colspan="3">
                  <input class="form-control" type="text" name="title" id="title" value="<?=$result->title?>">
                </td>
              </tr>

              <tr>
                <th><span class="text-danger">*</span> 내용</th>
                <td colspan="3">
                  <div class="editor_area btn-editor" style="width:100%">
                    <textarea name="contents" id="contents" placeholder="" class="input_default"><?=$result->contents?></textarea>
                  </div>
                </td>                
              </tr>
              <tr>
                <th>
                  사진[가로]
                  <p class="text-info">[670px]</p>
                  <input type="button" class="btn btn-xs btn-default" value="파일업로드" onclick="file_upload_click('img','image','5','150');" style="margin-bottom:10px">
                </th>
                <td colspan="3">
                  <ul>
                    <?
                      $img_arr =explode(",",$result->board_img);
                      $i=0;
                    ?>
                      <div class="img_hz" id="img">
                        <? if(!empty($result->board_img)){?>
                          <?foreach($img_arr as $img) {?>
                            <li id="id_file_0_<?=$i?>" style="display:inline-block;">
                              <img src="/images/btn_del.gif" style="width:15px "onclick="file_upload_remove('id_file_0_<?=$i?>')"/><br>
                              <img src="<?=$img?>" style="width:150px">
                              <input type="hidden" name="img"  value="<?=$img?>"/>
                            </li>
                          <?
                              $i++;
                            }
                          }
                        ?>
                      </div>
                  </ul>      
                </td>
              </tr>
              <tr>
                <th>상태</th>
        				<td>
                  <select name="display_yn" id="display_yn" class="form-control w_auto">
                    <option value="N" <?php if($result->display_yn =="N"){ echo "selected";}?>>비활성화</option>
                    <option value="Y" <?php if($result->display_yn =="Y"){ echo "selected";}?>>활성화</option>
                  </select>
                </td>
                <th>글 순서</th>
                <td >
                  <input name="board_notice_order_no" id="board_notice_order_no" type="text" class="form-control" value="<?=$result->board_notice_order_no?>">
                </td>
              </tr>
    
            
            </tbody>
          </table>
          <input type="hidden" name="board_idx" id="board_idx" value="<?=$result->board_idx?>">
        </form>
      </section>

      <div class="text-right" style="float:right;">
        <a class="btn btn-gray" href="javascript:void(0)" onclick="default_list()">목록</a>
        <a class="btn btn-danger" href="javascript:void(0)" onclick="board_notice_del('<?=$result->board_idx?>')">삭제</a>
        <a class="btn btn-success" href="javascript:void(0)" onclick="default_mod('<?=$result->board_idx?>')">수정</a>
      </div>

    </div>
  </div>
  <!-- body : e -->
</div>
<!-- container-fluid : e -->
<script>

  // 써머노트 셋팅
  var summernote_id = 'contents';
    $('#'+summernote_id).summernote({
    height: 440,
    fontNames: [ 'NotoSansKR-Regular']
  });

  function default_list(){
    history.back(<?=$history_data?>);
  }

  // 커뮤니티 공지사항 수정
  function default_mod() {
    var text = $('#contents').summernote('code'); // 써머노트 내용 가져오기
    text = $('<div>').html(text).text(); // HTML 태그 제거하고 텍스트만 추출

    var formData = {		
      'board_idx' : $('#board_idx').val(),
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
      url      : "/<?=mapping('board_notice')?>/board_notice_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success : function(result) {
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == "-2") {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }

  // 커뮤니티 공지사항 삭제 
  function board_notice_del(board_idx){

    if(!confirm("커뮤니티 공지사항을 삭제하시겠습니까?")){
      return;
    }

    $.ajax({
      url      : "/<?=mapping('board_notice')?>/board_notice_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "board_idx" : board_idx
      },
      success: function(result) {
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == "-2") {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }
</script>
