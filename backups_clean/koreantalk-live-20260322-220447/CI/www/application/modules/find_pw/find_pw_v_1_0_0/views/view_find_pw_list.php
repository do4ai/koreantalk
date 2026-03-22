<div class="body  ">
  <div class="inner_wrap">
    <div class="login_wrap">
    <div class="txt_center"> 
      <h2><?=lang('lang_10129','비밀번호 찾기')?></h2>
    </div>
    <div class="label"><?=lang('lang_10130','아이디')?></div>
    <input type="text" placeholder="<?=lang('lang_10131','이메일 주소를 입력해 주세요')?>" class="" id="member_id" name="member_id">
    <div class="label"><?=lang('lang_10132','이름')?></div>
    <input type="text" placeholder="<?=lang('lang_10133','이름을 입력해 주세요')?>" class="" id="member_name" name="member_name">
    <div class="label"><?=lang('lang_10134','전화번호')?></div>
    <input type="text" placeholder="<?=lang('lang_10135','숫자만 입력해 주세요')?>"  class="" id="member_phone" name="member_phone">
    <div class="btn_m txt_center btn_point mt60">
	<a href="javascript:void(0)" onclick="find_pw_member()"><?=lang('lang_10136','비밀번호 찾기')?></a>
    </div> 
    <div class="find_result" id="span_result" style="display:none;">
      <p><?=lang('lang_10137','회원님의 이메일로 비밀번호 변경')?> <br><?=lang('lang_10138','메일을 발송 하였습니다.')?></p>
      <p class="point"> <?=lang('lang_10139','비밀번호 변경 후 로그인 해 주세요.')?></p>
    </div></div>
  </div>

</div>


<script type="text/javascript">

var email_send_yn ="N";

function find_pw_member(){
  if(email_send_yn =="Y"){
    alert("<?=lang('lang_10140','이메일을 발송 중입니다. 잠시만 기다려주세요.')?>");
    return;
  }
  email_send_yn ="Y";
  
  $("#find_pw_btn").attr("onclick", "");

	var form_data = {
		'member_id' : $('#member_id').val(),
		'member_name' : $('#member_name').val(),
		'member_phone' : $('#member_phone').val(),
	};


	$.ajax({
		url      : "/<?=$this->current_lang?>/<?=mapping('find_pw')?>/find_pw_member",
		type     : "POST",
		dataType : "json",
		async    : true,
		data     : form_data,
		success  : function(result) {
			//alert(result);
			if(result.code == '-1'){
				alert(result.code_msg);
				$("#"+result.focus_id).focus();
        $("#find_pw_btn").attr("onclick", "find_pw_member()");
				return;
			}else {
				//find_modal_open();
				if(result.code == '0'){
					$("#span_result").css("display","none");
					//$("#span_result_false").css("display","block");
				}else{
					//$("#span_result_false").css("display","none");
					$("#span_result").css("display","block");
				}
        $("#find_pw_btn").attr("onclick", "find_pw_member()");

			}
      email_send_yn ="N";
      
		}
	});
}

</script>
