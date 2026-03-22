<div class="body inner_wrap">
  <div class="txt_center"> 
    <h2 class="fs_50 mt100 mb40">내 정보 보기</h2>
  </div>
  <div class="grid_member">
    <div>
      <div class="label">아이디</div> 
      <p>text</p> 
    </div>
    <div>
      <div class="label">이름</div>
      <p>이름이에요</p> 
    </div>
    <div>
      <div class="label">휴대폰번호<span class="essential"> *</span></div>
      <input type="text" placeholder='휴대폰번호를 입력해 주세요.'>
    </div>
    <div>
      <div class="label">외국인 한글 이름</div>
      <input type="text" placeholder='외국인 한글 이름을 입력해 주세요.'>
    </div> 
    <div>
      <div class="label">서비스언어 </div>
      <input type="text" placeholder='외국인 한글 이름을 입력해 주세요.'>
    </div> 
  </div>
     
  <div class="w850 mb140">  
    <div class="btn_full_weight btn_point w230 mt60">
      <a href="/<?=$this->current_lang?>/<?=mapping('join')?>/join_complete">등록</a>
    </div>  
    <a href="/<?=$this->current_lang?>/<?=mapping('member_out')?>" class="f_right font_gray_9" style="margin-top:-30px">회원탈퇴</a>
  </div>

</div> 