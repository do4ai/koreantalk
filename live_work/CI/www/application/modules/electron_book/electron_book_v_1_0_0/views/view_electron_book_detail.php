<div class="body mt100 row"> 
  <div class="inner_wrap">
    <table class="tbl_product_detail mb100">
      <colgroup> 
      <col width='410px'>
      <col width='*'>
      </colgroup>
      <tr> 
        <th>
        <?
          if($result->product_detail_img_path !=""){
          $img_arr = explode(",",$result->product_detail_img_path);
          
          ?> 
          <div style="overflow-x:hidden;height:560px">
            <div class="product_swiper">
              <div class="swiper-wrapper">
                <?php    
                foreach($img_arr as $img){
                ?>
               <div class="swiper-slide">
                  <div class="img_box">
                    <img src="<?=$img?>" alt="">
                  </div>
                </div> 
                <?}?>
              </div> 
              <div class="swiper-pagination"></div>
            </div> 
          </div>
          <?}?>
        </th>
        <td>
          <div class="title"><?=$result->product_name?></div>
          <div class="font_gray_6"><?=lang('lang_10075','저자')?> ‧<?=$result->author?> </div>
          <h2><?=number_format($result->product_price)?> <?=lang('lang_10076','원')?></h2>
          <div class="desc"><?=nl2br($result->product_desc)?></div> 

          <?if($result->my_buy_cnt>0){?>
            <div class="btn_m btn_grad"><a href="javascript:void(0)" ><?=lang('lang_10077','구매하기')?></a></div>
            <?if(!empty($study_enabled)){?>
              <div class="btn_m btn_point" style="margin-top:12px;">
                <a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/study_viewer?electron_book_idx=<?=$result->electron_book_idx?>" id="btn_study" data-electron-book-idx="<?=$result->electron_book_idx?>">Study</a>
              </div>
            <?}?>
          <?}else{?>
            <div class="btn_m btn_point"><a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/product_payment?electron_book_idx=<?=$result->electron_book_idx?>" ><?=lang('lang_10078','구매하기')?></a></div>
          <?}?>
        </td> 
    </table>
    
    <?=$result->product_contents?>

    <?if(!empty($movie_info)){?>
    <div class="box_detail">
      <h2><?=lang('lang_10079','같이 봐야 하는 TOPIK 영상')?></h2>
      <table class="tbl_product_box mt30">
        <colgroup>
          <col width='490px'>
          <col width='*'>
        </colgroup>
        <tr>
          <th>
            <div class="img_box">
              <img src="<?=$movie_info->img_path?>" alt="">
            </div>
          </th>
          <td>
            <h5><?=$movie_info->lecture_name?></h5>
            <div class="font_gray_6"><?=$movie_info->contents?></div>
            <div class="btn_m btn_point">
              <a  href="/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail?lecture_idx=<?=$movie_info->lecture_idx?>&lecture_category_idx=<?=$movie_info->lecture_category_idx?>&lecture_movie_idx=<?=$movie_info->lecture_movie_idx?>"><?=lang('lang_10080','시청하기')?></a>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <?}?>
  </div></div>
<script> 
var product_swiper = new Swiper(".product_swiper", { 
  pagination: {
    el: ".swiper-pagination",
  },
  slidesPerView:1, 
});
</script>


<input name="electron_book_idx" id="electron_book_idx" type="text" value="1" style="display:none">
<script>
var electron_book_idx="<?=$result->electron_book_idx?>";
var return_url="<?=$this->current_lang?>/<?=mapping('electron_book')?>/electron_book_detail&electron_book_idx="+electron_book_idx;

//주문하기
function order_reg_in() { 
 	if(COM_login_check(return_url)==false){ return;}  
  var form_data = { 
    "electron_book_idx" :electron_book_idx
  };


  $.ajax({
    url      : "/<?=$this->current_lang?>/<?=mapping('electron_book')?>/order_reg_in",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data     : form_data,
    success: function(result){
      // -1:유효성 검사 실패
      if(result.code == -1){
        alert(result.code_msg);
        return;
      }
      // -2:실패 1:성공
      if(result.code == '-2') {
        alert(result.code_msg);
      } else {
        alert(result.code_msg);
        location.href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/";
      }
    }
  });
}




</script>
