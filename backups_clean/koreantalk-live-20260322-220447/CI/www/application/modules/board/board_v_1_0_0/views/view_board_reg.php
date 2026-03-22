<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title"><?=lang('lang_10022','커뮤니티 등록')?> </h2>  
    <table>
      <tr>
        <th><div class="label"><?=lang('lang_10023','제목')?> <span class="essential">*</span></div></th>
      </tr>
      <tr>
        <td><input type="text" placeholder='<?=lang('lang_10024','제목을 입력해 주세요.')?>' id="title" name="title"></td>
      </tr>
      <tr>
        <th><div class="label"><?=lang('lang_10024','제목을 입력해 주세요.')?> <span class="essential">*</span></div></th>
      </tr>
      <tr>
        <!-- <td><textarea  id="contents" name="contents"></textarea>
       -->
      <td>
        <div class="editor_area btn-editor" style="width:100%">
          <textarea  id="contents" name="contents"></textarea>
        </div>
      </td>
      </tr>
      <tr>
        <th>
          <div class="label"><?=lang('lang_10027','이미지')?> <span class="font_gray_9"> <?=lang('lang_10028','(최대 5장)')?></span></div>
        </th>
      </tr>
      <tr>
        <td>
          <div class="row"  id="img">
            
            <div class="img_reg img_box">

              <img src="/images/btn_img.svg" alt=""  onClick="file_upload_click('img','images', 5)">
            </div>
            <!-- <div class="img_reg img_box">
              <img src="/images/btn_photo.png" alt="" class="btn_delete">
              <img src="/p_images/s1.png" alt="">
            </div>
            <div class="img_reg img_box">
              <img src="/images/btn_photo.png" alt="" class="btn_delete">
              <img src="/p_images/s1.png" alt="">
            </div> -->
          </div>
        </td>
      </tr>
    </table>
    <div class="btn_half_wrap">
      <div class="btn_m btn_gray">
        <a href="javascript:void(0)" onClick="history.go(-1);"><?=lang('lang_10029','취소')?></a>
      </div>
      <div class="btn_m btn_point">
      <a href="javascript:void(0)" onClick="default_reg_in()"><?=lang('lang_10030','등록')?></a>
      </div>
    </div>
  </div>
</div>



<input type="hidden" name="category" id="category" value="<?=$category?>">

<script type="text/javascript">

// 써머노트 셋팅
var summernote_id = 'contents';
$('#'+summernote_id).summernote({
height: 440,
fontNames: [ 'NotoSansKR-Regular']
});

function default_reg_in(){
  var text = $('#contents').summernote('code'); // 써머노트 내용 가져오기
  text = $('<div>').html(text).text(); // HTML 태그 제거하고 텍스트만 추출

  var formData = {		
    'category' : $('#category').val(),
    'title' : $('#title').val(),
    'contents' : $('#contents').val(),
    'contents_text' : text,
    'board_img' : get_checkbox_value('img'),
    'site_code' :"<?=$this->current_lang?>",

  }

  $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/board_reg_in",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success : function(result){
        if(result.code == '-1'){
     			alert(result.code_msg);
     			$("#"+result.focus_id).focus();
     			return;
   		  }
   		  // 0:실패 1:성공
   		  if(result.code == 0) {
     			alert(result.code_msg);
   		  }else {
          alert(result.code_msg);
          location.href ='/<?=$this->current_lang?>/<?=mapping('board')?>/?category=<?=$category?>';
     		}
      }
    });
}
</script>
