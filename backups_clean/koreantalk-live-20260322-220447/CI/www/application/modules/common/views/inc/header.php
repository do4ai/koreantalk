<!DOCTYPE html>
<html lang="kor">
<head>
  <!--타이틀 :	title 태그와 파비콘만 사용-->
	<title><?=SERVICE_NAME?></title>
	<link rel="shortcut icon" href="/images/favicon.png">

	<!--메타 : 메타 태그만 사용-->
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes">

	<!--내부 기본 CSS : 내부에서 생성한 CSS만 사용-->
	<link rel="stylesheet" href="/css/common.css">
	<link rel="stylesheet" href="/css/p_common.css">
	<link rel="stylesheet" href="/css/style.css">

	<!--외부 CSS : 외부 모듈에서 제공된 CSS만 사용-->
	<!-- <link rel="stylesheet" href="/external_css/font-awesome.css"> -->
	<link rel="stylesheet" href="/external_css/swiper.css">
	<link rel="stylesheet" href="/external_css/swiper-bundle.min.css">

  <!-- select2 -->
  <link href="/css/select2.min.css" rel="stylesheet">
	<!--외부 CSS 커스텀 : 외부 모듈 적용 후 자체적으로 CSS변경 한 경우만 사용-->
	<link rel="stylesheet" href="/external_css/outside.css">
  <link rel="stylesheet" href="/external_css/summernote.css" >

	<!--내부 기본 JS : 내부에서 생성한 JS 경우만 사용 하며. 이를 사용하기 위한 라이브러만사용(jquery.js) -->
	<script src="/js/jquery-1.12.4.min.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script src="/js/common.js"></script>

	<!--외부 JS : 외부 모듈에서 제공된 JS만 사용-->
	<script src="/external_js/swiper.jquery.js"></script>
	<script src="/external_js/swiper-bundle.min.js"></script>
	<script src="/external_js/pinch-zoom.js"></script>
  <!-- <script src="/external_js/lang/summernote-ko-KR.js"></script> -->

  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

  <!-- select2 -->
  <script src="/js/select2.min.js"></script>

<script>
var member_idx ="<?=$this->member_idx?>";
var current_lang ="<?=$this->current_lang?>";

//로그인 체크
function COM_login_check(return_url,go_type){
  if(member_idx ==""){
    alert("<?=lang("lang_main_00137","로그인이 필요합니다.")?>");
    location.href= "/<?=$this->current_lang.'/'.mapping('login')?>?return_url="+return_url;
    return false;
  }else{
    if(go_type ==undefined){
       return true;
    }else{
       location.href=return_url; 
    }
  }
}

// 히스토리 체크
function history_back_fn(){
  if(window.history.length==1){
    location.href = '/<?=$this->current_lang.'/'.mapping('main')?>';
  }else{
    history.go(-1);
  }
}


//언어 변경
function change_lang(str){
  var current_url = "<?=$_SERVER['REQUEST_URI']?>";
  var change_url = current_url.replace(current_lang,str);
 
  $.ajax({
    url : "/<?=$this->current_lang?>/language/change_lang",
    type : "post",
    dataType : "json",
    data : {  
      "current_lang" : str
    },
    async : false,
    success : function(dom){ 
     // alert("<?=lang("lang_common_00821","정상적으로 처리되었습니다.")?>");
      location.href = change_url;
    }
  });  
 
}

// 쇼핑몰 이동
function go_shop(url){
  var win = window.open(url,'_blank');
  win.focus();  
}

// 이용약관
function terms_detail(type){
  var form_data = {
    'type' : type
  };

  $.ajax({
    url: "/<?=$this->current_lang?>/<?=mapping('join')?>/terms_detail",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: form_data,
    success: function(result){
      if(result.code == '-1'){
      alert(result.code_msg);
      return;
      }
      // 0:실패 1:성공
      if(result.code == 0) {
      alert(result.code_msg);
      } else {
        $('#modal_title').html(result.title);
        $('#edit').html(result.contents);
        modal_open('terms');
      }
    }
  });
}


</script>


<body>

<div class="modal modal_terms">
  <div class="title" id="modal_title"><?=lang('lang_10065','이용약관')?></div>
  <img src="/images/i_delete_pop.png" alt="" onclick="modal_close('terms')" class="btn_del">
  <div id="edit">
    <?=lang('lang_10066','약관내용')?>
  </div>
</div>
<div class="md_overlay md_overlay_terms" onclick="modal_close('terms')"></div>

  <!-- wrap : s -->
  <div class="wrap" >
