<div class="body row"> 
  <div class="inner_wrap">
    <div class="w850">
      <div class="sub_title">결제 하기</div>
      <h2>주문 내역</h2>
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
                  <img src="/p_images/s2.png" alt="">
                </div></th>
                <td>
                <h5>한국어 문법 즐겁게 배우자</h5>
                </td>
            </tr>
          </table>
        </div>
        <div> 
          <h5>이용약관동의</h5> 
          <div class="all_checkbox row mt10">
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
            </ul>
          </div>
        </div> 
      </div>
      <div class="txt_center">
        <h5>결제 예정 금액 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b class="point_color">14,400 원</b></h5>
        <div class="btn_m btn_point mt40"><a href="/<?=$this->current_lang?>/<?=mapping('product')?>/product_payment_complete">결제하기</a></div>
      </div>
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


<script> 
var product_swiper = new Swiper(".product_swiper", { 
  pagination: {
    el: ".swiper-pagination",
  },
  slidesPerView:1, 
});
</script>