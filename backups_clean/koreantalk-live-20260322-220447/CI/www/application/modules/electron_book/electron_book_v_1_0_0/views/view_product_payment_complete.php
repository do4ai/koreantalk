<div class="body row"> 
  <div class="inner_wrap">
    <div class="w850">
      <div class="sub_title"><?=lang('lang_10089','결제 하기')?></div>
      <h2><?=lang('lang_10090','주문 내역')?></h2>
      <div class="flex_payment">
        <div>
          <table>
            <colgroup>
              <col width='115px'>
              <col width='*'>
            </colgroup>
            <tr>
              <th>
                <div class="img_box">
                  <img src="<?=$result->product_img_path?>" alt="">
                </div></th>
                <td>
                <h5><?=$result->product_name?></h5>
                </td>
            </tr>
          </table>
        </div>
        <div>  
        <table class="tbl_pay_com mt40">
          <colgroup>
            <col width='70px'>
            <col width='*'>
          </colgroup>
          <tr>
            <th><?=lang('lang_10091','주문 번호')?></th>
            <td><?=$result->order_number?></td>
          </tr>
          <tr>
            <th><?=lang('lang_10092','결제일')?></th>
            <td><?=$result->order_date?></td>
          </tr>
        </table>
        </div> 
      </div>
      <div class="txt_center">
        <div class="font_gray_6"><?=lang('lang_10093','* 구매하신 전자책은')?> <b class="point_color">'<?=lang('lang_10094','마이페이지 > 전자책 구매 내역')?>'</b><?=lang('lang_10095','에서 다운로드 받으실 수 있습니다.')?></div>
        <div class="btn_m btn_point mt40"> <a  href="/<?=$this->current_lang?>/<?=mapping('my_order')?>/"><?=lang('lang_10096','전자책 구매 내역 가기')?></a></div>
      </div>
    </div>
  </div>
</div>



<script> 
var product_swiper = new Swiper(".product_swiper", { 
  pagination: {
    el: ".swiper-pagination",
  },
  slidesPerView:1, 
});
</script>