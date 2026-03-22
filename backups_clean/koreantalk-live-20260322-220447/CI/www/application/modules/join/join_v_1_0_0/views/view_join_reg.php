<div class="body ">
  <div class="inner_wrap">
  <div class="txt_center"> 
    <h2 class="fs_50 mt100 mb40"><?=lang('lang_10152','회원가입')?></h2>
  </div>
  <div class="grid_member">
    <div>
      <div class="label"><?=lang('lang_10153','아이디')?><span class="essential"> *</span></div>
      <div class="flex_input_btn">
        <input type="text" placeholder='<?=lang('lang_10154','아이디로 사용할 이메일 주소 입력')?>' name="member_email" id="member_email">
        <input type="hidden" name="member_email_orign" id="member_email_orign" value="">
        <button class="btn_s btn_point_line"><a href="javascript:void(0)" onclick="email_verify_setting();" id="email_verify_btn" class="p0 flex_center_2"><?=lang('lang_10155','인증 번호 받기')?></a></button>
      </div>
      <div class="flex_input_btn timer_area mt10">
        <input type="text" pattern="\d*" id="verify_num" name="verify_num" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');">
        <div class="txt_timer" id="span_auth_number"></div>
        <button id="verify_btn_color" class="btn_s btn_check"><a onclick="email_verify_confirm()" id="verify_check"><?=lang('lang_10156','확인')?></a></button>
      </div>
    </div>
    <div>
      <div class="label"><?=lang('lang_10157','휴대폰번호')?><span class="essential"> *</span></div>
      <input type="text" placeholder='<?=lang('lang_10158','휴대폰번호를 입력해 주세요.')?>' name="member_phone" id="member_phone"  onKeyup="this.value=this.value.replace(/[^0-9]/g,'');">
    </div>
    <div>
      <div class="label"><?=lang('lang_10159','비밀번호')?><span class="essential"> *</span></div>
      <input placeholder="<?=lang('lang_10160','영문, 숫자 조합 8~20자리 이내로 입력해 주세요.')?>" type="password" name="member_pw" id="member_pw">
    </div>
    <div>
      <div class="label"><?=lang('lang_10161','비밀번호 확인')?><span class="essential">*</span></div>
      <input placeholder="<?=lang('lang_10162','영문, 숫자 조합 8~20자리 이내로 입력해 주세요.')?>" type="password" name="member_pw_confirm" id="member_pw_confirm">
    </div>
    <div>
      <div class="label"><?=lang('lang_10163','이름')?><span class="essential"> *</span></div>
      <input type="text" placeholder='<?=lang('lang_10164','이름을 입력해 주세요.')?>' name="member_name" id="member_name">
    </div>
    <div>
      <div class="label"><?=lang('lang_10165','외국인 한글 이름')?></div>
      <input type="text" placeholder='<?=lang('lang_10166','외국인 한글 이름을 입력해 주세요.')?>' name="member_nickname" id="member_nickname">
    </div> 
  </div>
     
  <div class="w850"> 
    <hr class="mt40 mb40">
    <h5><?=lang('lang_10167','이용약관동의')?></h5>
    <div class="all_checkbox row mt10 mb30">
      <ul>
        <li>
          <input type="checkbox" name="checkAll" id="checkAll">
          <label for="checkAll">
            <span></span>
            <?=lang('lang_10168','전체 약관 동의')?>
          </label>
        </li>
        <?php foreach ($terms_list as $row){ ?>
        <li>
          <input type="checkbox" name="checkOne" id="checkOne_<?=$row->type ?>" value="Y">
          <label for="checkOne_<?=$row->type ?>">
            <span></span>
            <p><?=$row->title ?> <i><?=lang('lang_10169','(필수)')?></i></p>
          </label>
          <span><a href="javascript:void(0)" onclick="terms_detail('<?=$row->type ?>')"><img src="/images/arrow_right.svg"></a></span>
        </li>
        <?}?>
        
      </ul>
    </div>
    <div class="btn_full_weight btn_point mb140 w230 mt60">
      <a href="javascript:void(0)" onClick="default_reg_in()"><?=lang('lang_10170','회원가입')?></a>
    </div>  
  </div>

</div>


</div>




<!-- modal : e -->
<input type="hidden" name="timer_yn" id="timer_yn" value="N">
<input type="hidden" name="timer_cnt" id="timer_cnt" value="0">
<!-- 현재 발신번호 미등록으로 auth_yn='Y'인 상태, 추후 N 으로 바꿔줘야 -->
<input type="hidden" name="auth_yn" id="auth_yn" value="N">
<input type="hidden" name="time_over_yn" id="time_over_yn" value="N">
<input type="hidden" name="email_verify_idx" id="email_verify_idx" value="">



<script type="text/javascript">




var email_send_yn ="N";
// 인증번호 요청
function email_verify_setting(){

  if(email_send_yn =="Y"){
    alert("<?=lang('lang_10171','이메일을 발송 중입니다. 잠시만 기다려주세요.')?>");
    return;
  }
  email_send_yn ="Y";

  $("#email_verify_btn").attr("onclick", "");
  var member_email = $('#member_email').val();

  var form_data = {
    'member_email' : member_email
  };

//  alert("이메일을 발송 중입니다. 잠시만 기다려주세요.");


  $.ajax({
		url: "/<?=$this->current_lang?>/<?=mapping('email_verify')?>/email_verify_setting",
		type: 'POST',
		dataType: 'json',
		async: true,
		data: form_data,
		success: function(result){
      email_send_yn ="N";
		  if(result.code == '-1'){
  			alert(result.code_msg);
  			$("#"+result.focus_id).focus();
        $("#email_verify_btn").attr("onclick", "email_verify_setting()");

  			return;
		  }
		  // 0:실패 1:성공
		  if(result.code == 0) {
  			alert(result.code_msg);
		  } else {
  			alert(result.code_msg);
        email_send_yn ="N";
        $("#email_verify_idx").val(result.email_verify_idx);
        $("#member_email_orign").val(member_email);

        if ($('#timer_yn').val()=='N') {
          COM_set_timer(5,'span_auth_number');
          $('#timer_yn').val('Y');
        }else {
          $('#timer_cnt').val('1');
          COM_set_timer(5,'span_auth_number');
        }
      	//  $('#btn_auth_ok').text('확인');
        $('#span_auth_number').css('display','block');
  	//		$("#verify_btn_color").removeClass('btn_check');
  	//		$("#verify_btn_color").addClass('btn_active');
		  }
      $("#email_verify_btn").attr("onclick", "email_verify_setting()");
      $("#email_verify_btn").html("<?=lang('lang_10172','재전송')?>");
	  }
  });
}

// 인증번호 확인
function email_verify_confirm(){

  var form_data = {
    'email_verify_idx' : $('#email_verify_idx').val(),
    'verify_num' : $('#verify_num').val(),
    'time_over_yn' : $('#time_over_yn').val(),
  };

  $.ajax({
		url: "/<?=$this->current_lang?>/<?=mapping('email_verify')?>/email_verify_confirm",
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
		  // 0:실패 1:성공
		  if(result.code == 0) {
			alert(result.code_msg);
		  } else {
			alert(result.code_msg);
      $('#auth_yn').val("Y");
      $('#timer_yn').val('N');
      $('#timer_cnt').val('0');
      $('#span_auth_number').css('display','none');
      $("#verify_btn_color").removeClass('btn_check').addClass('btn_point_line');
		//	$("#verify_btn_color").addClass('btn_desabled');
		  }
	  }
  });
}


// 가입하기
function default_reg_in(){
  var selected_idx = get_checkbox_value('checkOne');

  if(selected_idx !="Y,Y"){
    alert("<?=lang('lang_10173','필수 약관 동의에 체크해주세요.')?>");
    return  false;
  }

  var auth_yn = $('#auth_yn').val();
  var member_id_input = $('#member_email').val();
  var member_id = $('#member_email_orign').val();

  if (member_id!=member_id_input || auth_yn!='Y') {
    alert("<?=lang('lang_10174','본인인증을 완료해주세요.')?>");
    return;
  }

  var formData = {
    'member_id' :  $('#member_email_orign').val(),
    'member_pw' :  $('#member_pw').val(),
    'member_pw_confirm' :  $('#member_pw_confirm').val(),
    'member_name' :  $('#member_name').val(),
    'member_nickname' :  $('#member_nickname').val(),
    'member_phone' :  $('#member_phone').val(),
  };

  $.ajax({
    url      : "/<?=$this->current_lang?>/<?=mapping('join')?>/member_reg_in",
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
      if(result.code == "-2") {
        alert(result.code_msg);
      } else {
        alert(result.code_msg); 
        location.href ='/<?=$this->current_lang?>/<?=mapping('join')?>/join_complete?member_id='+$('#member_email_orign').val()+'&member_name='+$('#member_name').val();
      }
    }
  });
}
</script>
