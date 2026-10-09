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
          <div class="font_gray_6"><?=lang('lang_10270','저자')?> ‧<?=$result->author?> </div>
          <h2><?=number_format($result->product_price)?> <?=lang('lang_10271','원')?></h2>
          <!-- <div class="desc"><?=$result->product_contents?></div>  -->
          <div class="desc"><?=nl2br($result->product_desc)?></div> 
          <div class="btn_m btn_point"><a href="javascript:void(0)" onClick="go_shop('<?=$result->smart_store_url?>')"><?=lang('lang_10272','구매하기')?></a></div>
        </td> 
    </table>

    <?=$result->product_contents?>
    
  

    <?if(!empty($movie_info)){?>
    <div class="box_detail">
      <h2><?=lang('lang_10273','같이 봐야 하는 TOPIK 영상')?></h2>
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
              <a  href="/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail?lecture_idx=<?=$movie_info->lecture_idx?>&lecture_category_idx=<?=$movie_info->lecture_category_idx?>&lecture_movie_idx=<?=$movie_info->lecture_movie_idx?>"><?=lang('lang_10274','시청하기')?></a>
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
