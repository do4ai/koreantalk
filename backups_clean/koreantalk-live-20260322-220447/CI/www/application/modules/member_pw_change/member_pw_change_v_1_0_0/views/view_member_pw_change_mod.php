<div class="body ">
  <div class="inner_wrap">
    <div class="txt_center"> 
      <h2 class="fs_50 mt100 mb40"><?=lang('lang_10235','비밀번호 변경')?></h2>
    </div>
    <div class="grid_member"> 
      <div>
        <div class="label"><?=lang('lang_10236','기존 비밀번호')?><span class="essential"> *</span></div>
        <input placeholder="<?=lang('lang_10237','영문, 숫자 조합 8~20자리 이내로 입력해 주세요.')?>" type="password" id="member_pw" name="member_pw">
      </div>
			<div>
      </div>
      <div>
        <div class="label"><?=lang('lang_10238','새 비밀번호')?><span class="essential">*</span></div>
        <input placeholder="<?=lang('lang_10239','영문, 숫자 조합 8~20자리 이내로 입력해 주세요.')?>" type="password"  id="member_pw_new" name="member_pw_new">
      </div>
      <div>
        <div class="label"><?=lang('lang_10240','새 비밀번호 확인')?><span class="essential">*</span></div>
        <input placeholder="<?=lang('lang_10241','영문, 숫자 조합 8~20자리 이내로 입력해 주세요.')?>" type="password" id="member_pw_new_confirm" name="member_pw_new_confirm">
      </div> 
    </div>
        
    <div class="btn_full_weight btn_point mb140 w230 mt60">
	<a href="javascript:void(0)" onClick="member_pw_mod_up()"><?=lang('lang_10242','수정')?></a>
    </div>   

  </div> 
</div> 


<script type="text/javascript">

function member_pw_mod_up(){

	var form_data = {
		'member_pw' : $('#member_pw').val(),
		'member_pw_new' : $('#member_pw_new').val(),
		'member_pw_new_confirm' : $('#member_pw_new_confirm').val()
	};

	$.ajax({
		url: "/<?=$this->current_lang?>/<?=mapping('member_pw_change')?>/member_pw_mod_up",
		type: 'POST',
		dataType: 'json',
		async: true,
		data: form_data,
		success: function(result){

			if(result.code == '-1'){
				alert(result.code_msg);
				$("#"+result.focus_id).focus();
				return;
			}
			// -1000:실패 1000:성공
			if(result.code == '1000') {
				alert(result.code_msg);
	     		 location.reload();
			} else {
				alert(result.code_msg);
			}
		}
	});
}

</script>


