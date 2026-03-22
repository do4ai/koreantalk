<div class="body  ">
  <div class="inner_wrap">
    <div class="login_wrap">
    <div class="txt_center"> 
      <h2>로그인</h2>
    </div>
    <div class="label">아이디</div>
    <input type="text" placeholder="아이디" class="">
    <div class="label">비밀번호</div>
    <input type="text" placeholder="비밀번호" class="">
    <div class="mt20 flex_center">
      <input type="checkbox" id="chk_1_1" name="chk_1">
      <label for="chk_1_1"><span></span>로그인 유지</label>
      <div class="font_gray_9">
        <a href="/<?=$this->current_lang?>/<?=mapping('find_id')?>">아이디 찾기</a>
        <span> ‧ </span>
        <a href="/<?=$this->current_lang?>/<?=mapping('find_pw')?>">비밀번호 찾기</a>
      </div>
    </div> 
    <div class="btn_full_weight btn_point mt40">
      <a href="/<?=$this->current_lang?>/<?=mapping('main')?>">로그인</a>
    </div>
    <div class="btn_full_weight btn_point_line mt20">
      <a href="/<?=$this->current_lang?>/<?=mapping('join')?>" class="">회원가입</a>
    </div>  
  </div>
</div>

</div>