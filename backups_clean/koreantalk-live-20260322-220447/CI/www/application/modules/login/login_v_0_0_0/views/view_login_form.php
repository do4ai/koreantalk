<div class="body inner_wrap txt_center">

  <div class="login_form_wrap"> 
    <h2>광고주 로그인</h2>
    <h4 class="sub_title">원활한 서비스 이용을 위해 로그인 하여주세요.</h4>
    <input type="text" placeholder="이메일" class="login_input_id">
    <input type="text" placeholder="비밀번호" class="mt14">
    <div class="mt14 flex_1">
      <input type="checkbox" id="chk_1_1" name="chk_1">
      <label for="chk_1_1"><span></span>로그인 유지</label>
      <a href="/<?=$this->current_lang?>/<?=mapping('find_id')?>" style="margin-left:100px">아이디 찾기</a>
      <a href="/<?=$this->current_lang?>/<?=mapping('find_pw')?>">비밀번호 찾기</a>
    </div> 
    <div class="btn_full_weight btn_point mt30">
      <a href="/<?=$this->current_lang?>/<?=mapping('login')?>/state">로그인</a>
    </div>
    <div class="btn_full_weight btn_point mt30">
      <a href="/<?=$this->current_lang?>/<?=mapping('join')?>">잇툰 회원가입</a>
    </div>
  </div>
</div>
 