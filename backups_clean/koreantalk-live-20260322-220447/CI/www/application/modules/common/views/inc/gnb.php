<header>
	<div class="inner_wrap"> 
		<a href="/<?=$this->current_lang?>/<?=mapping('main')?>"><img src="/images/new_logo.jpg" alt="" class="logo"></a>
    
    <div class="gnb-area">
      <!-- <div class="gnb"> -->
      
      <ul class="zeta-menu">
        <li class="custom_relative <?php if($this->uri->segment(2)==mapping("lecture")) echo "on";?> ">
          <a href="javascrip:void(0);"><?=lang('lang_10037','TOPIK')?></a>					
        </li>

        <? if($this->current_lang =="kr"){ ?>
        <li class="<?php if($this->uri->segment(2)==mapping("product")) echo "on";?>">
          <a href="/<?=$this->current_lang?>/<?=mapping('product')?>"><?=lang('lang_10038','서점')?></a>
        </li>  
        <?}else{?>			   
        <li class="<?php if($this->uri->segment(2)==mapping("electron_book")) echo "on";?>">
          <a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>"><?=lang('lang_10039','서점')?></a>
        </li>  
        <?}?>

        <li class="<?php if($this->uri->segment(2)==mapping("board")) echo "on";?>">
          <a href="/<?=$this->current_lang?>/<?=mapping('board')?>?category=1"><?=lang('lang_10040','커뮤니티')?></a>
        </li> 

        <li class="custom_relative">
          <a href="javascrip:void(0);" onclick="openOption(this)"><?=lang('lang_10041','문화')?></a>
        </li> 
      </ul> 
      <!-- </div>  -->
      
      <ul class="lnb mypage zeta-menu-2" > 
        <?if($this->member_idx>0){?>
        <li>
          <a href="javascrip:void(0);" onclick="openOption(this)"><?=lang('lang_10044','마이페이지')?></a>
        </li>

        <?}else{?>
        <!-- <li class="login_btn<?php if($this->uri->segment(2)==mapping("login")) echo "on";?>"> -->
        <li >
          <a href="/<?=$this->current_lang?>/<?=mapping('login')?>" >
            <?=lang('lang_10051','로그인')?>
          </a>
        </li>
        <!-- <li class="<?php if($this->uri->segment(2)==mapping("join")) echo "on";?>"> -->
        <!-- <li class="">
          <a href="/<?=$this->current_lang?>/<?=mapping('join')?>">
            <?=lang('lang_10052','회원가입')?>
          </a>
        </li>    -->
        <?}?>
      </ul>

      <!-- 2 depth menu : s -->
      <div class="sub-menu expand">
        <div class="inner_wrap sub-menu-wrap">
          <div class="sub-left-menu">
          <div class="sub-menu-container">
            <ul>
            <? foreach($lecture_list as $row){ ?>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail?lecture_idx=<?=$row->lecture_idx?>&lecture_category_idx=<?=$row->lecture_category_idx?>" onclick="openOption_2(this)"><?=$row->lecture_name?> </a>
              </li>
            <?}?>
            </ul>
          </div>

          <div class="sub-menu-container">
            <ul>
            <?if($this->current_lang=='kr'){?>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('product')?>" onclick="openOption_2(this)">서점</a>
              </li>
              <? foreach($book_list as $row){ ?>
                <li class="">
                  <a href="/<?=$this->current_lang?>/<?=mapping('product')?>/product_detail?product_idx=<?=$row->product_idx?>" onclick="openOption_2(this)"><?=$row->product_name?> </a>
                </li>
              <?}?>
            <?}else{?>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>" onclick="openOption_2(this)">Bookstore</a>
              </li>
              <? foreach($electron_book_list as $row){ ?>
                <li class="">
                  <a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/electron_book_detail?electron_book_idx=<?=$row->electron_book_idx?>" onclick="openOption_2(this)"><?=$row->product_name?> </a>
                </li>
              <?}?> 
            <? } ?>    
           
            </ul>
          </div>

          <div class="sub-menu-container">
            <ul>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('board')?>?category=1" onclick="openOption_2(this)"><?=lang('lang_menu_00032','커뮤니티')?></a>
              </li>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>" onclick="openOption_2(this)"><?=lang('lang_11402','1:1 상담')?></a>
              </li>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('board')?>?category=2" onclick="openOption_2(this)"><?=lang('lang_11403','결혼')?></a>
              </li>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('board')?>?category=3" onclick="openOption_2(this)"><?=lang('lang_11404','유학')?></a>
              </li>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('board')?>?category=4" onclick="openOption_2(this)"><?=lang('lang_11405','근로자')?></a>
              </li>
              <li class="">
                <a href="/<?=$this->current_lang?>/<?=mapping('board')?>?category=5" onclick="openOption_2(this)"><?=lang('lang_11406','취업')?></a>
              </li>


       
            </ul>
          </div>

          <div class="sub-menu-container">
            <ul>
              <li class="">
                <a href="https://youtube.com/playlist?list=PLuAmN6I7RWbgatGK4rpZ3GPt1mI0_oysE&si=ESz7Sn7_KqNFQZUC" target="_blank"><?=lang('lang_10412','여행')?></a>
              </li>
              <li class="">
                <a href="https://youtube.com/playlist?list=PLuAmN6I7RWbib9o8bAV3-jCrTtsKeoeP7&si=avPzY85RUTDaLm5E" target="_blank"><?=lang('lang_10413','음식')?></a>
              </li>
              <li class="">
                <a href="https://youtube.com/playlist?list=PLuAmN6I7RWbiKMnFf9C2-MwWIua5fZpOb&si=kLGklyh8T0Lc6Gga" target="_blank"><?=lang('lang_10414','뷰티')?></a>
              </li>
              <li class="">
                <a href=" https://youtube.com/playlist?list=PLuAmN6I7RWbibe5iUZwHtEZjOXj31q8k5&si=99GAE2JcB8no5M91" target="_blank"><?=lang('lang_10415','패션')?></a>
              </li>
              <li class="">
                <a href="https://youtube.com/playlist?list=PLuAmN6I7RWbgH9AEo28HXO0hV9RjS5kFS&si=W4AvrstCooNrS9To" target="_blank"><?=lang('lang_10416','어울림')?></a>
              </li>
            </ul>
          </div>
          </div>
          <?if($this->member_idx>0){?>        
            <div class="sub-right-menu">
              <div class="sub-menu-container">
                <ul class="" style="margin-top:0;">				
                  <li class="<?php if($this->uri->segment(2)==mapping("member_info")) echo "on";?>">
                    <a href="/<?=$this->current_lang?>/<?=mapping('member_info')?>"><?=lang('lang_10045','내 정보 관리')?></a>
                  </li>
                  <li class="<?php if($this->uri->segment(2)==mapping("my_order")) echo "on";?>">
                    <a href="/<?=$this->current_lang?>/<?=mapping('my_order')?>"><?=lang('lang_10046','전자책 구매 내역')?></a>
                  </li>
                          <li class="<?php if($this->uri->segment(2)==mapping("my_lecture")) echo "on";?>">
                    <a href="/<?=$this->current_lang?>/<?=mapping('my_lecture')?>"><?=lang('lang_10047','시청한 교육 영상')?></a>
                  </li>
                  <li class="<?php if($this->uri->segment(2)==mapping("qa")) echo "on";?>">
                    <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>"><?=lang('lang_10048','1:1 상담')?></a>
                  </li>           
                  <li class="<?php if($this->uri->segment(2)==mapping("member_pw_change")) echo "on";?>">
                    <a href="/<?=$this->current_lang?>/<?=mapping('member_pw_change')?>"><?=lang('lang_10049','비밀번호 변경')?></a>
                  </li> 
                  
                  <li class="">
                    <a href="/<?=$this->current_lang?>/logout"><?=lang('lang_10050','로그아웃')?></a>
                  </li> 
                </ul>
              </div>
            </div> 
          <? } ?>
        </div>
      </div>
      <!-- 2 depth menu : e -->
    </div>
    <div class="border-line"></div>
  </div>
</header>
<script>

$(document).ready(function(){

  $('.sub-menu').hide();
  let submenu_timer;

  $('.gnb-area').on('mouseenter', function () {
    clearTimeout(submenu_timer);
    $('.sub-menu').stop(true, true).slideDown(200, function () {
      $('.border-line').css('top', 80 + $('.sub-menu').outerHeight());
    });
    $('header').addClass('expand-active');
  });

  $('.gnb-area').on('mouseleave', function () {
    submenu_timer = setTimeout(function () {
      $('.sub-menu').stop(true, true).slideUp(200);
      $('.border-line').css('top', 80);
      $('header').removeClass('expand-active');
    }, 100);
  });

});

</script>
