<div class="body  ">
  <div class="inner_wrap">
    <div class="login_wrap">
    <div class="txt_center"> 
      <h2><?=lang('lang_10182','로그인')?></h2>
    </div>
    <div class="label"><?=lang('lang_10183','아이디')?></div>
    <input type="text" id="member_id" name="member_id" placeholder="<?=lang('lang_10184','아이디')?>" class="">
    <div class="label"><?=lang('lang_10185','비밀번호')?></div>
    <input type="password"  id="member_pw" name="member_pw" placeholder="<?=lang('lang_10186','비밀번호')?>" class="">
    <div class="mt20 flex_center">
      <input type="checkbox" id="chk_1_1" name="chk_1">
      <label for="chk_1_1"><span></span><?=lang('lang_10187','로그인 유지')?></label>
      <div class="font_gray_9">
        <a href="/<?=$this->current_lang?>/<?=mapping('find_id')?>"><?=lang('lang_10188','아이디 찾기')?></a>
        <span> ‧ </span>
        <a href="/<?=$this->current_lang?>/<?=mapping('find_pw')?>"><?=lang('lang_10189','비밀번호 찾기')?></a>
      </div>
    </div> 
    <div class="btn_full_weight btn_point mt40">
    <a href="javascript:void(0)" onClick="login_action_member();"><?=lang('lang_10190','로그인')?></a>
    </div>
    <div class="btn_full_weight btn_point_line mt20">
      <a href="/<?=$this->current_lang?>/<?=mapping('join')?>" class=""><?=lang('lang_10191','회원가입')?></a>
    </div>  
  </div>
</div>

</div>


<input type="hidden" name="device_os" id="device_os" value="">
<input type="hidden" name="gcm_key" id="gcm_key" value="">


<form id="hidden_form" name="hidden_form"  method="get" >
	<?php
	foreach($_GET as $key => $value){
	if($key !="return_url"){
	?>
	<input type="hidden" name="<?=$key?>" id="<?=$key?>" value="<?=$value?>">
	<?php }}?>
</form>
<script>
var return_url = "<?=$return_url?>";

function login_action_member(){

var form_data = {
  'device_os' : $('#device_os').val(),
  'gcm_key' : $('#gcm_key').val(),
  'member_id' : $('#member_id').val(),
  'member_pw' : $('#member_pw').val()
};

$.ajax({
  url      : "/<?=$this->current_lang?>/<?=mapping('login')?>/login_action_member",
  type     : 'POST',
  dataType : 'json',
  async    : true,
  data     : form_data,
  success: function(result){
    if(result.code == '-1'){
    alert(result.code_msg);
    $("#"+result.focus_id).focus();
    return;
    }
    // 0:실패 1:성공
    if(result.code == "-2") {
      alert(result.code_msg);
    } else {
      alert(result.code_msg);
      member_login_url();     
    }
  }
});
}


// 로그인후  url
function member_login_url(){
  <?if($return_url !=""){?>
  $("#hidden_form")[0].action="/<?=$this->current_lang?>"+return_url;
  $("#hidden_form")[0].submit();
  <?}else{?>
  location.href ='/<?=$this->current_lang?>/<?=mapping('main')?>';
  <?}?>
}
</script>


