  <ul class="product_ul row"> 
      <?php    
      foreach($result_list as $row){
      ?> 
      <li>
        <a href="javascript:void(0)" onClick="do_view('<?=$row->product_idx?>')">
          <div class="img_box">
            <img src="<?=$row->product_img_path?>" alt="">
          </div>
          <h5 class="title"><?=$row->product_name?></h5>
          <div class="font_gray_6"><?=lang('lang_10251','저자')?> ‧ <?=$row->product_name?> </div>
          <div class="font_gray_6 mt8"><?=lang('lang_10252','주문번호')?> ‧ <?=$row->order_number?>  </div>
          <div class="font_gray_6 mt8"><?=lang('lang_10253','구매 날짜')?> ‧ <?=$this->global_function->date_Ymdhi_Hyphen($row->ins_date)?>  </div> 
          <div class="flex_price">
            <h4><?=number_format($row->product_price)?> <?=lang('lang_10254','원')?></h4>
            <a href="javascript:void(0)" onClick="do_view('<?=$row->product_idx?>')" class="font_gray_6"><?=lang('lang_10255','상세보기')?></a>
          </div>
          <div class="font_gray_6"><?=$row->product_contents?></div>
        </a>
        <div class="btn_m <?=($row->download_cnt>0)?"btn_grad":"btn_point";?>" id="li_<?=$row->product_auth_code?>">
          <a  href="javascript:void(0)" onClick="file_download('<?=$row->product_auth_code?>')"><?=lang('lang_10256','PDF 다운로드')?></a>
        </div>
      </li>

      <?}?>
  


    </ul> 
    <?php if(empty($result_list)){ ?>
   
      <div class="no_data">
        <p><?=lang('lang_10257','조회된 구매 내역이 없습니다.')?></p>
      </div>
      
    <?}?>

    <?=$paging?>



