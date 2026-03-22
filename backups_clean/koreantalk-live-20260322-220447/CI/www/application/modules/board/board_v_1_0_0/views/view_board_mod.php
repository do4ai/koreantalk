<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title"><?=lang('lang_10013','커뮤니티 등록')?></h2>  
    <table>
      <tr>
        <th><div class="label"><?=lang('lang_10014','제목')?> <span class="essential">*</span></div></th>
      </tr>
      <tr>
        <td><input type="text" placeholder='<?=lang('lang_10015','제목을 입력해 주세요.')?>' id="title" name="title" value="<?=$result->title?>"></td>
      </tr>
      <tr>
        <th><div class="label"><?=lang('lang_10016','내용')?> <span class="essential">*</span></div></th>
      </tr>
      <tr>
        <td><textarea placeholder='<?=lang('lang_10017','내용을 입력해 주세요.')?>' id="contents" name="contents"><?=$result->contents?></textarea></td>
      </tr>
      <tr>
        <th>
          <div class="label"><?=lang('lang_10018','이미지')?> <span class="font_gray_9"><?=lang('lang_10019','(최대 5장)')?></span></div>
        </th>
      </tr>
      <tr>
        <td>
        <?
         
            $img_arr =explode(",",$result->board_img);
            $i=0;
        ?>
          <div class="row" id="img">
            
            <div class="img_reg img_box">
              <img src="/images/btn_img.svg" alt="" onClick="file_upload_click('img','images', 5)">
            </div>
            <? if(!empty($result->board_img)){?>
            <?foreach($img_arr as $img) {?>
              
            <div class="img_reg img_box" id='id_file_0_<?=$i?>'>
              <img src="/images/i_delete.svg" alt="" class="btn_delete"  onclick="file_img_remove('id_file_0_<?=$i?>')">
              <img src="<?=$img?>" alt="">
              <input type='checkbox' name='img'  value='<?=$img?>' checked style='display:none' />
            </div>
            <?
            $i++;
          }
        }
        ?>
          </div>
        </td>
      </tr>
    </table>
    <div class="btn_half_wrap">
      <div class="btn_m btn_gray">
        <a href="javascript:void(0)" onClick="history.go(-1);"><?=lang('lang_10020','취소')?></a>
      </div>
      <div class="btn_m btn_point">
      <a href="javascript:void(0)" onClick="board_mod_up()"><?=lang('lang_10021','수정')?></a>
      </div>
    </div>
  </div>
</div>

<input type="hidden" name="board_idx" id="board_idx" value="<?=$result->board_idx?>">
<input type="hidden" name="category" id="category" value="<?=$result->category?>">
<script type="text/javascript">

// 써머노트 셋팅
var summernote_id = 'contents';
$('#'+summernote_id).summernote({
height: 440,
fontNames: [ 'NotoSansKR-Regular']
});

function board_mod_up(){

  var text = $('#contents').summernote('code'); // 써머노트 내용 가져오기
  text = $('<div>').html(text).text(); // HTML 태그 제거하고 텍스트만 추출

  var formData = {
		'board_idx' : $('#board_idx').val(),
		'category' : $('#category').val(),
    'title' : $('#title').val(),
    'contents' : $('#contents').val(),  
    'contents_text' : text,  
    'board_img' : get_checkbox_value('img'),
  }

  $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/board_mod_up",
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
          location.href ='/<?=$this->current_lang?>/<?=mapping('board')?>/?category=<?=$result->category?>';
     		}
      }
    });
}
</script>
