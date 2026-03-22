<div class="body  row"> 
  <div class="inner_wrap"> 
    <h2 class="sub_title txt_left"><?=$result->title?></h2> 
    <div class="date"><?=$this->global_function->date_Ymd_Hyphen($result->ins_date)?></div>
    <hr class="mt40 mb40">
    <?if($result->img_path !=""){?>
        <img src="<?=$result->img_path?>" alt="" class="img_block">
    <?}?>


    <p class="mt40 mb40"> <?=nl2br($result->contents)?></p>
    <div class="btn_m w230 mb140 btn_gray">
    <a href="javascript:history.go(-1)" onClick=""><?=lang('lang_10260','목록')?></a>
    </div>
  </div>
</div>


