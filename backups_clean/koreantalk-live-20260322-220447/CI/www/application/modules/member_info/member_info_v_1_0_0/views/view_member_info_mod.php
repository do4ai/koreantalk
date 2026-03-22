<div class="body inner_wrap">
  <div class="txt_center"> 
    <h2 class="fs_50 mt100 mb40"><?=lang('lang_10212','내 정보 보기')?></h2>
  </div>
  <div class="grid_member">
    <div>
      <div class="label"><?=lang('lang_10213','아이디')?></div> 
      <p><?=$result->member_id?></p> 
    </div>
    <div>
      <div class="label"><?=lang('lang_10214','이름')?></div>
      <p><?=$result->member_name?></p> 
    </div>
    <div>
      <div class="label"><?=lang('lang_10215','휴대폰번호')?><span class="essential"> *</span></div>
      <input type="text" placeholder='<?=lang('lang_10216','휴대폰번호를 입력해 주세요.')?>' name="member_phone" id="member_phone" value="<?=$result->member_phone?>">
    </div>
    <div>
      <div class="label"><?=lang('lang_10217','외국인 한글 이름')?></div>
      <input type="text" placeholder='<?=lang('lang_10218','외국인 한글 이름을 입력해 주세요.')?>' name="member_nickname" id="member_nickname" value="<?=$result->member_nickname?>" >
    </div> 
    <div>
      <div class="label"><?=lang('lang_10219','서비스언어')?> </div>
      <select name="current_lang"   id="current_lang" >
          <option value="kr" <?=($result->current_lang =="kr")?"selected":"";?>><?=lang('lang_10220','한국어')?></option>
          <option value="us" <?=($result->current_lang =="us")?"selected":"";?>><?=lang('lang_10221','영어')?></option>
        </select>
    </div> 
  </div>
     
  <div class="w850 mb140">  
    <div class="btn_full_weight btn_point w230 mt60">
     <a href="javascript:void(0)" onClick="default_mod_up()"><?=lang('lang_10222','수정')?></a>
    </div>  
    <a href="/<?=$this->current_lang?>/<?=mapping('member_out')?>" class="f_right font_gray_9" style="margin-top:-30px"><?=lang('lang_10223','회원탈퇴')?></a>
  </div>

</div> 


<script>

// 가입하기
function default_mod_up(){

  var formData = {
    'member_nickname' :  $('#member_nickname').val(),
    'member_phone' :  $('#member_phone').val(),
    'current_lang' :  $('#current_lang').val(),
  };

  $.ajax({
    url      : "/<?=$this->current_lang?>/<?=mapping('member_info')?>/member_mod_up",
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
        change_lang($('#current_lang').val());       
      }
    }
  });
}



</script>

