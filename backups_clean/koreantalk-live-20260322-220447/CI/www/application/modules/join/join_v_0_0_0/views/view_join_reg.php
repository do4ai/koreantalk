<div class="body ">
  <div class="inner_wrap">
  <div class="txt_center"> 
    <h2 class="fs_50 mt100 mb40">회원가입</h2>
  </div>
  <div class="grid_member">
    <div>
      <div class="label">아이디<span class="essential"> *</span></div>
      <div class="flex_input_btn">
        <input type="text" placeholder='아이디로 사용할 이메일 주소 입력'>
        <button class="btn_s btn_point_line"><a href="#">인증 번호 받기</a></button>
      </div>
      <div class="flex_input_btn mt10">
        <input type="text">
        <button class="btn_s btn_check"><a href="#">확인</a></button>
      </div>
    </div>
    <div>
      <div class="label">휴대폰번호<span class="essential"> *</span></div>
      <input type="text" placeholder='휴대폰번호를 입력해 주세요.'>
    </div>
    <div>
      <div class="label">비밀번호<span class="essential"> *</span></div>
      <input placeholder="영문, 숫자 조합 8~20자리 이내로 입력해 주세요." type="password">
    </div>
    <div>
      <div class="label">비밀번호 확인<span class="essential">*</span></div>
      <input placeholder="영문, 숫자 조합 8~20자리 이내로 입력해 주세요." type="password">
    </div>
    <div>
      <div class="label">이름<span class="essential"> *</span></div>
      <input type="text" placeholder='이름을 입력해 주세요.'>
    </div>
    <div>
      <div class="label">외국인 한글 이름 </div>
      <input type="text" placeholder='외국인 한글 이름을 입력해 주세요.'>
    </div> 
  </div>
     
  <div class="w850"> 
    <hr class="mt40 mb40">
    <h5>이용약관동의</h5>
    <div class="all_checkbox row mt10 mb30">
      <ul>
        <li>
          <input type="checkbox" name="checkAll" id="checkAll">
          <label for="checkAll">
            <span></span>
            전체 약관 동의
          </label>
        </li>
        <li>
          <input type="checkbox" name="checkOne" id="checkOne_1" value="Y">
          <label for="checkOne_1">
            <span></span>
            <p>서비스 이용약관 <i> (필수)</i></p>
          </label>
          <span><a href="javascript:void(0)" onclick="modal_open('terms')"><img src="/images/arrow_right.svg"></a></span>
        </li>
        <li>
          <input type="checkbox" name="checkOne" id="checkOne_2" value="Y">
          <label for="checkOne_2">
            <span></span>
            <p>개인정보 이용방침  <i> (필수)</i></p>
          </label>
          <span><a href="javascript:void(0)" onclick="modal_open('terms')"><img src="/images/arrow_right.svg"></a></span>
        </li>
        <li>
          <input type="checkbox" name="checkOne" id="checkOne_2" value="Y">
          <label for="checkOne_2">
            <span></span>
            <p>전자 금융거래 이용약관  <i> (필수)</i></p>
          </label>
          <span><a href="javascript:void(0)" onclick="modal_open('terms')"><img src="/images/arrow_right.svg"></a></span>
        </li>
      </ul>
    </div>
    <div class="btn_full_weight btn_point mb140 w230 mt60">
      <a href="/<?=$this->current_lang?>/<?=mapping('join')?>/join_complete">회원가입</a>
    </div>  
  </div>

</div>
<div class="modal modal_terms">
  <div class="title">이용약관</div>
  <img src="/images/i_delete_pop.png" alt="" onclick="modal_close('terms')" class="btn_del">
  <div id="edit">
    약관내용
  </div>
</div>
<div class="md_overlay md_overlay_terms" onclick="modal_close('terms')"></div>

</div>