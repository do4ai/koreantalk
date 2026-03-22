<!-- footer : s -->
<footer>
 <div class="foot_top">
   <div class="inner_wrap"> 
    <ul class="ul_footer_terms">

      <li style="cursor:pointer;"  ><a href="javascript:void(0)"  onclick="terms_detail('1')" ><?=lang('lang_10053','서비스 이용 약관')?></a></li>
      <li style="cursor:pointer;"  ><a href="javascript:void(0)" onclick="terms_detail('0')" ><?=lang('lang_10054','개인정보처리방침')?></a></li>
      <li style="cursor:pointer;"  ><a href="javascript:void(0)"  onclick="terms_detail('2')" ><?=lang('lang_10055','전자 금융거래 이용 약관')?></a></li>
      <li style="cursor:pointer;"  ><a href="/<?=$this->current_lang?>/<?=mapping('faq')?>"><?=lang('lang_10056','FAQ')?></a></li>
      <li style="cursor:pointer;"  ><a href="/<?=$this->current_lang?>/<?=mapping('notice')?>"><?=lang('lang_10057','공지사항')?></a></li>
    </ul>
     <ul class="ul_footer_info">
      <li>
        <?=lang('lang_10058','상호명 : 코리안톡')?>
      </li>
      <li>
        <?=lang('lang_10059','대표 : 고종오')?>
      </li>
      <li>
        <?=lang('lang_10060','사업자등록번호 : 407-47-64543')?>
      </li>
      <li>
        <?=lang('lang_10061','통신판매업신고 : 2023-OOOO-00000')?>
      </li>
      <li>
        <?=lang('lang_10062','주소 : 서울시 강남구 강남대로 342,4층')?> 
      </li>
      <li>
        <?=lang('lang_10063','개인정보관리책임자 : 고종오')?>
      </li>
      <li>
        <?=lang('lang_10064','이메일 : help@OOOO.com')?>
      </li>
     </ul>
   
     <div class="copy"><?=lang('lang_10333','Copyright ⓒ 2023 by (주)코리안톡 Inc. All rights reserved.')?></div>
   </div>
 </div>
 
</footer>
<!-- footer : s --> 
</div>
<!-- wrap : e -->

</body>
</html>

<script>
function botoom_go_url(type){
 location.href="/<?=$this->current_lang?>/<?=mapping('terms')?>/?type="+type;
}

</script>

