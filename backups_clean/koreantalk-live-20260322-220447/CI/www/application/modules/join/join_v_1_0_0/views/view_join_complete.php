<div class="body inner_wrap ">
  <div class="login_wrap">
  <div class="txt_center">
    <img src="/images/img_success.png" class="mb10">
    <h2><?=lang('lang_10148','회원가입 완료')?></h2>
  </div> 
  <div class="join_box">
    <table>
      <colgroup>
        <col width='70px'>
        <col width='*'>
      </colgroup>
      <tr>
        <th><?=lang('lang_10149','아이디')?></th>
        <td><?=$member_id?></td>
      </tr>
      <tr>
        <th><?=lang('lang_10150','이름')?></th>
        <td><?=$member_name?></td>
      </tr>
    </table>
  </div>
  <div class="txt_center">
    <div class="btn_m btn_point">
      <a href="/<?=$this->current_lang?>/<?=mapping('login')?>"><?=lang('lang_10151','로그인 화면으로 이동')?></a>
    </div> 
  </div></div>
</div>
















