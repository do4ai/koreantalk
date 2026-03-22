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
        <table class="tbl_pay_com mt40">
          <colgroup>
            <col width='70px'>
            <col width='*'>
          </colgroup>
          <tr>
            <th>주문 번호</th>
            <td>ddsds</td>
          </tr>
          <tr>
            <th>결제일</th>
            <td>ddsds</td>
          </tr>
        </table>
        </div> 
      </div>
      <div class="txt_center">
        <div class="font_gray_6">*  구매하신 전자책은 <b class="point_color">'마이페이지 > 전자책 구매 내역'</b>에서 다운로드 받으실 수 있습니다.</div>
        <div class="btn_m btn_point mt40"><a href="">전자책 구매 내역 가기</a></div>
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