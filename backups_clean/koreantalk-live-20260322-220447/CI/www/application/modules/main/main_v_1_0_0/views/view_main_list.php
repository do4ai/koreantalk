<div class="body view__main">
  <div class="main_swiper">
    <div class="swiper-wrapper">
      <?      
      foreach($banner_list as $row){
      ?>
      <div class="swiper-slide">
        <a href="javascript:void(0)" onClick="banner_url('<?=$row->link_url?>')">
        <div class="img_box">
        <img src="<?=$row->img_path?>" alt="">
        </div>
        </a>
      </div> 
      <?}?>
 
    </div> 
    <div class="swiper-pagination"></div>
  </div> 
  <div class="inner_wrap">
    <div class="btitle"><?=lang('lang_10192','한국어 학습의 새로운 시작, 코리안톡 서점')?></div>
    <ul class="main_ul">
       
    <?
      $result_list= ($this->current_lang =="kr")?$product_list :$electron_book_list;      
      $more_url= ($this->current_lang =="kr")?$product_list :$electron_book_list;      
     
      foreach($result_list as $row){
    ?> 
    
    <li>
        <?
        if($this->current_lang =="kr"){   
        ?>
        <a href="/<?=$this->current_lang?>/<?=mapping('product')?>/product_detail?product_idx=<?=$row->product_idx?>">
        <?}else{?>
        <a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/electron_book_detail?electron_book_idx=<?=$row->electron_book_idx?>">
        <?}?>
          <div class="img_box">
          
            <img src="<?=$row->product_img_path?>" alt="">
          </div>
          <h5 class="title"><?=$row->product_name?></h5>
          <h5 class="price"><?=number_format($row->product_price)?> <?=lang('lang_10193','원')?></h5>
        </a>
      </li>
      <?}?>
      
    </ul>
    <div class="btn_m btn_point txt_center mt40">
        <?
        if($this->current_lang =="kr"){   
        ?>
        <a href="/<?=$this->current_lang?>/<?=mapping('product')?>/"><?=lang('lang_10194','더보기')?></a>
        <?}else{?>
        <a  href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/"><?=lang('lang_10195','더보기')?></a>
        <?}?>
    </div>
    <div class="btitle"><?=lang('lang_10196','쉽고 재미있는 학습 경험')?></div>
    <ul class="main_ul2">
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>/">
        <div class="img_box">
          <img src="/images/easy_bg_1.png" alt="">
        </div>
        <p class="title"><?=lang('lang_11407','1:1 상담 서비스')?></p>
        </a>
      </li>
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/?category=2">
        <div class="img_box">
          <img src="/images/easy_bg_2.png" alt="">
        </div>
        <p class="title"><?=lang('lang_11408','다문화 결혼')?></p>
        </a>
      </li>
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/?category=3">
        <div class="img_box">
          <img src="/images/easy_bg_3.png" alt="">
        </div>
        <p class="title"><?=lang('lang_11409','한국 유학 소개')?></p>
        </a>
      </li>
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/?category=4">
        <div class="img_box">
          <img src="/images/easy_bg_4.png" alt="">
        </div>
        <p class="title"><?=lang('lang_11410','고용허가제 근로자')?></p>
        </a>
      </li>
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/?category=5">
        <div class="img_box">
          <img src="/images/easy_bg_5.png" alt="">
        </div>
        <p class="title"><?=lang('lang_11411','취업소개, 면접')?></p>
        </a>
      </li>
    </ul>
    <div class="btitle"></div>
    <div class="wrap_main3">
	   <?      
		  foreach($lecture_list as $row){
		?>
      <div class="img_box">
        <img src="<?=$row->img_path?>" alt="">
      </div>
      <table>
        <colgroup>
        <col width='*'>
        <col width='200px'>
        </colgroup>
     
        <tr>
          <th>
            <h4><?=$row->lecture_name?></h4>
          </th>
          <td rowspan="2">
            <div class="btn_m btn_point">
            <a href="/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail?lecture_idx=<?=$row->lecture_idx?>&lecture_category_idx=<?=$row->lecture_category_idx?>&lecture_movie_idx=<?=$row->lecture_movie_idx?>"><?=lang('lang_10202','시청하기')?></a>
            </div>
          </td>
        </tr>
        <tr>
          <th>
            <p class="mt14"><?=$row->contents?></p>
          </th>
        </tr>       
      </table>
	   <?}?>
      <div class="main_box4">
        <table>
          <colgroup>
            <col width='34px'>
            <col width='*'>
            <col width='50px'>
          </colgroup>
          <?      
          foreach($notice_list as $row){
          ?>
          <tr>
            <th>
              <img src="/images/audio-3 1.png" alt="">
            </th>
            <th>
            <a href="/<?=$this->current_lang?>/<?=mapping('notice')?>/notice_detail?notice_idx=<?=$row->notice_idx?>">
              <p><?=lang('lang_10204','[공지사항]')?> <?=$row->title?>   <?=$row->ins_date?></p>
              </a>
            </th>
            <td><a href="/<?=$this->current_lang?>/<?=mapping('notice')?>/notice_detail?notice_idx=<?=$row->notice_idx?>"><?=lang('lang_10205','더보기 >')?></a></td>
          </tr>
          <?}?>
        </table>
      </div>
    </div>
  </div>  

  <div class="main_bg img_box">
    <div class="btitle"><?=lang('lang_10206','한국어 레벨 테스트를 받아 해보세요.')?></div>
    <div class="btn_m btn_point txt_center">
      <a href="#" onclick="test_al"><?=lang('lang_10207','테스트하기')?></a>
    </div>
    <img src="/images/bn_bg.png" alt=""> 
  </div>
</div>
<div class="modal modal_main">
  <div class="md_container">
    <img src="/images/logo.png" alt="" class="logo">
    <h5><?=lang('lang_10208','코리안톡 서비스를 이용하실 언어를 선택해 주세요.')?></h5>
    <div class="font_gray_9  mt10 mb20"><?=lang('lang_10209','언어 변경은')?> <b class="point_color"><?=lang('lang_10210','마이페이지 > 내 정보 관리 > 서비스 언어')?></b><?=lang('lang_10211','에서 변경 하여 사용 가능 합니다.')?></div>
    <div class="main_btns">
      <div class="btn_left"  onClick="change_lang('kr')">
        한국어
      </div>
      <div class="btn_right"  onClick="change_lang('us')">
        ENGLISH
      </div>
    </div>
    <p class="mt40 font_gray_9">Korean Talk service<br>

      Please select the language you wish to use.<br>
      To change the language, go to<br>
      My Page > Manage My Information > Service Language<br>
      It can be used by changing in .</p>
  </div>
</div>  
<div class="md_overlay md_overlay_main" onclick="modal_close('main')"></div>
<script>
<?
if(empty(get_cookie('multi_lang'))){
?>
modal_open('main');
<?}?>
var main_swiper = new Swiper(".main_swiper", {
  pagination: {
    el: ".swiper-pagination",
  }, 
  speed: 3000,
  loop: true,
  autoplay:{
    delay: 3000,
    disableOnInteraction: false  }
}
);
</script>


<script>
//배너 url
function banner_url(url){
  if(url !=""){
    window.open('about:blank').location.href=url;
  }
}


// 회원상태체크(정지,탈퇴일 경우 로그아웃)
function member_state_check(){
  if(member_idx ==""){
    return;
  }

  var formData = {
  };

  $.ajax({
    url      : "/<?=$this->current_lang?>/<?=mapping('main')?>/member_state_check",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data     : formData,
    success: function(result){
      if(result.code == '-1'){
        alert(result.code_msg);
        $("#"+result.focus_id).focus();
        return;
      }
      // -2:실패 1000:성공
      if(result.code == "-2") {
      //    alert(result.code_msg);
      } else {
      //  alert(result.code_msg); 
        if(result.corp_state >1){
          location.href="/<?=$this->current_lang?>/logout/";         
        } 
      }
    }
  });
}

$(function(){
  member_state_check();
});


</script>