<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative"><?=lang('lang_10295','1:1 상담')?></h2> 
    <div class="label"><?=lang('lang_10296','제목')?> <span class="essential">*</span></div>
    <input type="text" placeholder='<?=lang('lang_10297','제목을 입력해 주세요.')?>' id="qa_title" name="qa_title">
    <div class="label"><?=lang('lang_10298','내용')?> <span class="essential">*</span></div>
    <textarea  id="qa_contents" name="qa_contents" cols="" rows="" placeholder='<?=lang('lang_10299','내용을 입력해 주세요.')?>'></textarea>
    
    <div class="btn_half_wrap" style="width:414px;margin:40px auto;"> 
      <span class="btn_m btn_gray">
       <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>"><?=lang('lang_10300','목록')?></a>
      </span>
      <span class="btn_m btn_point">
        <a href="javascript:(0)" onclick="qa_reg_in();"><?=lang('lang_10301','등록')?></a>
      </span>
    </div>
  </div>
</div>

<script type="text/javascript">
function qa_reg_in(){

  var formData = {
    'qa_title' : $('#qa_title').val(),
    'qa_contents' : $('#qa_contents').val()
  }

  $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_reg_in",
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
   		  if(result.code == -2) {
     			alert(result.code_msg);
   		  }else {
          alert(result.code_msg);
          location.href ='/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_list';
     		}
      }
    });
}
</script>
