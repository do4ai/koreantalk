<ul class="product_ul row"> 
    <?php
    
    foreach($result_list as $row){
    ?>
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('product')?>/product_detail?product_idx=<?=$row->product_idx?>">
          <div class="img_box">
            <img src="<?=$row->product_img_path?>" width=80px onerror="this.src='/p_images/s4.png'">            
          </div>
          <h5 class="title"><?=$row->product_name?></h5>
          <div class="font_gray_6"><?=lang('lang_10275','저자')?> ‧ <?=$row->author?> </div>
          <div class="flex_price">
            <h4><?=number_format($row->product_price)?> <?=lang('lang_10276','원')?></h4>
            <a href="/<?=$this->current_lang?>/<?=mapping('product')?>/product_detail?product_idx=<?=$row->product_idx?>" class="font_gray_6"><?=lang('lang_10277','상세보기')?></a>
          </div>
          <div class="font_gray_6"><?=strip_tags($row->product_contents)?></div>
        </a>
        <div class="btn_m btn_grad">
          <a href="javascript:void(0)" onClick="go_shop('<?=$row->smart_store_url?>')"><?=lang('lang_10278','구매하기')?></a>
        </div>
      </li> 
      
    <?
    }
    if(empty($result_list)){
    ?> 
    <li>
       <p><?=lang('lang_10279','등록된 책이 없습니다.')?></p>
    </li>
    <?}?>
    </ul> 
    <?=$paging?>  
  