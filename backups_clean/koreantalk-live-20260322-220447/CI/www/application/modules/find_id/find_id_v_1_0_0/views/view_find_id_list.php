<div class="body  ">
  <div class="inner_wrap">
  <div class="login_wrap">
    <div class="txt_center"> 
      <h2><?=lang('lang_10117','아이디 찾기')?></h2>
    </div>
    <div class="label"><?=lang('lang_10118','이름')?></div>
    <input type="text" placeholder="<?=lang('lang_10119','이름을 입력해 주세요')?>" class=""  id="member_name" name="member_name">
    <div class="label"><?=lang('lang_10120','전화번호')?></div>
    <input type="text" placeholder="<?=lang('lang_10121','숫자만 입력해 주세요')?>" class="" id="member_phone" name="member_phone">
    <div class="btn_m txt_center btn_point mt40">
		<a href="javascript:void(0)" onclick="find_id_member()"><?=lang('lang_10122','아이디 찾기')?></a>
    </div>

    <div class="find_result"  id="span_resultspan_result_false"  style="display:none;">
      <p><?=lang('lang_10123','일치하는 회원정보가 없습니다.')?></p>      
    </div>

	<div class="find_result" id="span_result" style="display:none;" >
      <p><?=lang('lang_10124','회원님의 아이디입니다.')?></p>
      <p class="point"  id="span_member_id"> </p>
    </div>

</div>
</div>

</div>



<script type="text/javascript">
function find_id_member(){


	var form_data = {
		'member_name' : $('#member_name').val(),
		'member_phone' : $('#member_phone').val(),
	};

	$.ajax({
		url      : "/<?=$this->current_lang?>/<?=mapping('find_id')?>/find_id_member",
		type     : "POST",
		dataType : "json",
		async    : true,
		data     : form_data,
		success  : function(result) {
			if(result.code == '-1'){
				alert(result.code_msg);
				$("#"+result.focus_id).focus();
				return;
			}else {
				if(result.code == '-2'){
					$("#span_result").css("display","none");
					$("#span_result_false").css("display","block");
				}else{
				//	$("#span_result_false").css("display","none");
					$("#span_result").css("display","block");
					$("#span_member_id").html(result.member_id);
				}

			}
		}
	});
}

</script>


