<div class="body">
  <div class="inner_wrap"> 
    <div class="sub_title"><?=lang('lang_10224','회원탈퇴')?></div>  
    <h5 class="mt40 mb20"><?=lang('lang_10225','회원탈퇴안내')?></h5>
    <ul class="ul_member_out">
      <li>
        <?=lang('lang_10226','회원탈퇴 전에 아래 내용을 확인해주세요.')?>
      </li>
      <li></li>
      <li>
        <?=lang('lang_10227','- 고객님의 계정에 저장된 정보가 삭제될 예정입니다. 삭제된 정보는 추후에 복원할 수 없습니다.')?>
      </li>
      <li>
        <?=lang('lang_10228','- 같은 아이디로 재가입이 불가합니다.')?>
      </li> 
    </ul>
    <div class="label"><?=lang('lang_10229','탈퇴 사유')?> <span class="essential">*</span></div>
    <textarea name="member_leave_reason" id="member_leave_reason" class=" mb20" cols="" rows="" style="height:170px" placeholder='<?=lang('lang_10230','소중한 만남에 불편을 드렸다면 정말 죄송합니다. 서비스 이용 중 어떤 부분이 불편하셨는지 알려주시면 소중한 의견 반영하여 더 좋은 서비스로 찾아뵙겠습니다.')?>'></textarea>
    <input type='checkbox' id='chk_1_1' name='chk_1' value="Y">
    <label for='chk_1_1'><span></span><?=lang('lang_10231','안내사항을 모두 확인하였으며, 이에 동의합니다.')?></label>

    <div class="btn_half_wrap mt60" style="width:470px;margin:50px auto;"> 
      <span class="btn_full_weight btn_gray">
      <a href="/<?=$this->current_lang?>/<?=mapping('main')?>"><?=lang('lang_10232','취소')?></a>
      </span>
      <span class="btn_full_weight btn_point">
      <a href="javascript:void(0)" onClick="default_mod_up()"><?=lang('lang_10233','회원 탈퇴')?></a>
      </span>
    </div>
  </div>
</div>


<script>

// 가입하기
function default_mod_up(){

  var selected_idx = get_checkbox_value('chk_1');

  if(selected_idx !="Y"){
    alert("<?=lang('lang_10234','안내사항에 동의 체크해 주세요.')?>");
    return  false;
  }

  var formData = {
    'member_leave_reason' :  $('#member_leave_reason').val(),
  };

  $.ajax({
    url      : "/<?=$this->current_lang?>/<?=mapping('member_out')?>/member_out_mod_up",
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
        location.href ='/<?=$this->current_lang?>/logout/';
      }
    }
  });
}



</script>