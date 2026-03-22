// 전화번호 인증 타이머
function COM_set_timer(set_minute,id){
  //초기값
  var temp_minute = "";
  var temp_second = "";
  var hour = 0;
  var minute = set_minute;
  var second = 0;
  // $('#timer_cnt').val('1');
  var timer = setInterval(function () {
    if (parseInt($('#timer_cnt').val())>0) {
      $('#timer_cnt').val('0');
      clearInterval(timer); /* 타이머 종료 */
    }

    if(minute<10){
  	 temp_minute =minute;
    }else{
  	 temp_minute =minute;
    }

    if(second<10){
  	 temp_second ="0"+second;
    }else{
  	 temp_second =second;
    }

    var_time =  temp_minute +":"+temp_second;

    $("#"+id).html(var_time);

    if(second == "1" && minute == "0" ){
    	$("#"+id).html('인증 확인 시간을 초과 하였습니다');
    	$("#time_over_yn").val('N');
      clearInterval(timer); /* 타이머 종료 */
    }else{
     second--;
     // 분처리
     if(second <= 0){
    	minute--;
    	second = 59;
     }
     //시간처리
     if(minute == 0){
    	if(hour > 0){
    	 hour--;
    	 minute = 59;
    	}
     }

    }
 }, 1000); /* millisecond 단위의 인터벌 */

}

// 전체동의폼 ::민지
function allCheckFunc( obj ) {
  $("[name=checkOne]").prop("checked", $(obj).prop("checked") );
}

/* 체크박스 체크시 전체선택 체크 여부 */
function oneCheckFunc( obj ){
  var allObj = $("[name=checkAll]");
  var objName = $(obj).attr("name");

  if( $(obj).prop("checked") ){
    checkBoxLength = $("[name="+ objName +"]").length;
    checkedLength = $("[name="+ objName +"]:checked").length;

    if( checkBoxLength == checkedLength ) {
        allObj.prop("checked", true);
    } else {
        allObj.prop("checked", false);
    }
  }
  else
  {
    allObj.prop("checked", false);
  }
}

$(function(){
  $("[name=checkAll]").click(function(){
    allCheckFunc( this );
  });
  $("[name=checkOne]").each(function(){
    $(this).click(function(){
      oneCheckFunc( $(this) );
    });
  });
});

// 이미지 확대/축소
function setModalOpen(img){ 
  $("#zoom_img").attr('src', img);
  modal_open('img_origin');
  imageZoom();
}
function imageZoom(){
  var el = document.querySelector('#box_id');
  var pz = new PinchZoom.default(el, {
    draggableUnzoomed: false,
  });
}


//------------------------------------------------------------------------------------------

// 민지:: 윈도우 팝업
var popupWidth = 600;
var popupHeight = 800;
var popupX = (window.screen.width / 2) - (popupWidth / 2);
// 만들 팝업창 width 크기의 1/2 만큼 보정값으로 빼주었음
var popupY= (window.screen.height / 2) - (popupHeight / 2);
// 만들 팝업창 height 크기의 1/2 만큼 보정값으로 빼주었음
function window_popup(popUrl,target_name){
  var target = "mod" ;
  if(target_name != undefined ){
    target = target_name;
  }

  var popOption = "width="+popupWidth+", height="+popupHeight+", left="+popupX+", top="+popupY+", status=no, menubar=no, toolbar=no, resizable=no";
  window.open(popUrl,target, popOption)
}

//------------------------------------------------------------------------------------------

// JavaScript Document
$(document).ready(function() {
  //클릭 이벤트
  $(".img_search").click(function(){
    $(".search_layer").stop().slideToggle(300);
    return false;
  });

});

//------------------------------------------------------------------------------------------

//사이드 네비 토글
function side_nav_toggle(){
  $(".nav, .nav_dim").toggleClass("open");

  if($(".nav").hasClass("open")){

    $.lockBody();
  } else {
    $.unlockBody();
  }
}

//------------------------------------------------------------------------------------------

$(document).ready(function() {
  // 민지:: 탭 메뉴 토글기능
  $(".tab_area_wrap > div").hide();
  $(".tab_area_wrap > div").first().show();
  $(".tab_toggle_menu li").click(function() {
    var list = $(this).index();
    $(".tab_toggle_menu li").removeClass("active");
    $(this).addClass("active");
    $(".tab_area_wrap > div").hide();
    $(".tab_area_wrap > div").eq(list).show();
  });
});

//basic_modal-------------------------------------------------------------------------------------

function modal_open(element){
  $(".md_overlay_" + element).css("visibility", "visible").animate({opacity: 1}, 100);
  $(".modal_" + element).css({display: "block"});
  $.lockBody();
}

function modal_close(element){
  $(".md_overlay_" + element).css("visibility", "hidden").animate({opacity: 0}, 100);
  $(".modal_" + element).css({display: "none"});
  $.unlockBody();
}

//------------------------------------------------------------------------------------------

//모달 백그라운드 스크롤 막기
var scrollTop;

$.lockBody = function() {
  if(window.pageYOffset) {
    scrollTop = window.pageYOffset;
    $(".wrap").css({
      top: - (scrollTop)
    });
  }

  $('html, body').css({
    height: "100%",
    overflow: "hidden"
  });
}

$.unlockBody = function() {
  $('html, body').css({
    height: "",
    overflow: ""
  });

  $(".wrap").css({
    top: ''
  });

  window.scrollTo(0, scrollTop);
  window.setTimeout(function () {
    scrollTop = null;
  }, 0);
}

//아코디언
$(document).on("click",".accordion .trigger",function(){
  var my_trigger = $(this);
  var my_panel = $(this).siblings(".panel");
  var my_list_item = $(this).siblings(".list_item_title");

  var accordion_group = $(this).parents('.accordion'); //해당 아코디언 그룹

  if( my_trigger.hasClass('active') ){  //열려있을 때
    //해당 슬리아드 비활성화
    my_panel.stop().slideUp();
    my_trigger.removeClass('active');
  } else {  //닫혀있을 때
    accordion_group.find('.panel').stop().slideUp();
    accordion_group.find('.trigger').removeClass('active');


    //해당 슬라이드 활성화
    my_panel.stop().slideDown();
    my_trigger.addClass('active');
    my_list_item.addClass('active')
  }
});


//전체선택(함수는 lable에서 지정해야 합니다.)
function COM_check_box_all_fn(all_clase,select_class){
  var all_check = $('.'+all_clase); //전체 동의 체크박스
  var checkbox_num = $('.filter_wrap').find('.'+select_class).length; //체크 항목 개수
  var checkbox = $('.filter_wrap').find('.'+select_class); //전체동의를 제외한 체크박스

  //전체 동의 체크 했을 때
  all_check.click(function(){
    if(all_check.prop("checked") == true){
      checkbox.prop("checked",true);
    } else {
      checkbox.prop("checked",false);
    }
  });

  //전체동의 체크 해제 상태에서 모두 체크 되었을때, 전체동의 체크 상태에서 하나라도 해제 했을 때
  checkbox.click(function(){
    var checked_num = $("."+select_class+":checked").length; //체크된 박스 개수
    if(checked_num == checkbox_num){ //모두 체크 되었을 때
      all_check.prop("checked",true);
    } else {
      all_check.prop("checked",false);
    }
  });
}


// 전체동의폼::재명 수정
function checkall_func(checkAll, checkOne){

  function allCheckFunc(obj) {
    $("[name="+checkOne+"]").prop("checked", $(obj).prop("checked") );
  }

  /* 체크박스 체크시 전체선택 체크 여부 */
  function oneCheckFunc( obj ){
    var allObj = $("[name="+checkAll+"]");
    var objName = $(obj).attr("name");

    if( $(obj).prop("checked") ){
      checkBoxLength = $("[name="+ objName +"]").length;
      checkedLength = $("[name="+ objName +"]:checked").length;

      if( checkBoxLength == checkedLength ) {
          allObj.prop("checked", true);
      } else {
          allObj.prop("checked", false);
      }
    }
    else
    {
      allObj.prop("checked", false);
    }
  }

  $(function(){
    $("[name="+checkAll+"]").click(function(){
      allCheckFunc( this );
    });
    $("[name="+checkOne+"]").each(function(){
      $(this).click(function(){
        oneCheckFunc( $(this) );
      });
    });
  });
}



//------------------------------------------------------------------------------------------

//지역코드 세팅------------------------------------------------------------------------------
var get_area_list = function(area_code,depth,db_val) {
$.ajax({
 url: "/p_common/get_area_list",
 type: 'POST',
 dataType: 'json',
 async: true,
 data: {
     "area_code" : area_code,
     "depth" : depth,
 },
 success: function(dom){

   var selectStr = "";
   var sel = "";
    //select 선택 세팅
   if(depth=="1"){
      $('#do_code').html("<option value=''>시/도선택</option>");
   }
   if(depth=="2"){
      $('#si_code').html("<option value=''>구/시선택</option>");
   }
   if(depth=="3"){
      $('#dong_code').html("<option value=''>읍/면/동선택</option>");
   }
   if(depth=="4"){
      $('#ri_code').html("<option value=''>리/선택</option>");
   }
   // option vaue 세팅
   if(dom.length != 0) {

     for(var i = 0; i < dom.length; i ++) {
      sel ="";
      if(depth=="1"){
        if(dom[i].do_code ==db_val){ sel ="selected";}
      selectStr += "<option value='"+ dom[i].do_code  + "'  "+sel+" >" + dom[i].do + "</option>";
      }
      if(depth=="2"){
        if(dom[i].si_code ==db_val){ sel ="selected";}
       selectStr += "<option value='"+ dom[i].si_code  + "' "+sel+" >" + dom[i].si + "</option>";
      }
      if(depth=="3"){
        if(dom[i].dong_code ==db_val){ sel ="selected";}
       selectStr += "<option value='"+ dom[i].dong_code  + "' "+sel+" >" + dom[i].dong + "</option>";
      }
      if(depth=="4"){
        if(dom[i].ri_code ==db_val){ sel ="selected";}
       selectStr += "<option value='"+ dom[i].ri_code  + "' "+sel+" >" + dom[i].ri + "</option>";
      }

     }
     if(depth=="1"){
        $('#do_code').append(selectStr);
     }
     if(depth=="2"){
        $('#si_code').append(selectStr);
     }
     if(depth=="3"){
        $('#dong_code').append(selectStr);
     }
     if(depth=="4"){
        $('#ri_code').append(selectStr);
     }

   }
 }
});
}
//------------------------------------------------------------------------------------------

//3자리 단위마다 콤마 생성
function addCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//모든 콤마 제거
function removeCommas(x) {
  if(!x || x.length == 0) return "";
  else return x.split(",").join("");
}
//------------------------------------------------------------------------------------------

// checkbox 쉼표로 가져오기-----------------------------------------------------------------
//checkbox 쉼표로 가져오기
function get_checkbox_value(name){

var selected_idx = "";
var num = 0;
$("input[name="+name+"]:checked").each(function() {
  if(num == 0){
    selected_idx += $(this).val();
  }else{
    selected_idx += ','+$(this).val();
  }
  num++;
});
return selected_idx;
}
//------------------------------------------------------------------------------------------

// 파일업로드-------------------------------------------------------------------------------
var file_cnt = 0;
// 이미지 업로드 함수 trigger(img_id:id,limit_cnt:파일갯수,file_type:(image:이미지,file:파일),width:이미지 넓이,height:이미지 높이,accept_file:확장자명체(ex:.jpg ,.png ,.gif),img_resize:이미지 리사이즈 넓이))
function file_upload_click(img_id,file_type,limit_cnt,width,height,accept_file,img_resize){
  $('body').append('<form id="file_form" method="post"></form>');
  var fileUpload = "<input type='hidde1n' id='member_img_path'><input type='file' name='file[]' id='ex_file' accept='"+accept_file+" ' onchange=\"file_upload('"+img_id+"','"+file_type+"','"+limit_cnt+"','"+width+"','"+height+"','"+accept_file+"','"+img_resize+"');\" style='display:none' ><input type='hidden' name='img_resize' id='img_resize' value='"+img_resize+"'>";
  $('#file_form').html(fileUpload);
  $('#ex_file').click();
}

//파일업로드함수
function file_upload(img_id,file_type,limit_cnt,width,height,accept_file,img_resize){
  var formdata = new FormData($("#file_form")[0]);
  //업로드 갯수 제한
  if(limit_cnt!=""){
    var check_id = 'id_file_'+img_id+'_';
    if($("[id^="+check_id+"]").length >= parseInt(limit_cnt)){
      alert('업로드는 '+limit_cnt+'개 까지만 등록 가능합니다.');
      return;
    }
  }

 
  //파일확장자 제한
  if(accept_file != "undefined" ){
    var file_check_arr = accept_file.split(',');
    //var temp_ext = $('#ex_file').val().split('.');
    var filename =$('#ex_file').val();
    var _fileLen = filename.length;
    var _lastDot = filename.lastIndexOf('.');
    var file_ext = filename.substring(_lastDot, _fileLen).toLowerCase();

    var enable_cnt = 0;
    for(var i = 0; i < file_check_arr.length; i ++) {
      //alert(file_check_arr[i]);
      if(file_ext ==file_check_arr[i].trim()){
        enable_cnt++;
      }
    }
    //alert(enable_cnt);
    if(enable_cnt ==0){
      alert("허용된 확장자("+accept_file+")가 아닌 파일입니다.");
      return;
    }
  }
   	console.log(accept_file);
  $.ajax({
    url         : "/kr/common/multi_fileUpload",
    type        : 'post',
    dataType    : 'json',
    processData : false,
    contentType : false,
    data        : formdata,
    success     : function(img_list){
	
      var str="";

      for(var i = 0; i < img_list.length; i++){

      str="<div  id='id_file_"+img_id+"_"+i+"_"+file_cnt+"' class='img_reg img_box'>";
      if(file_type !="file"){
      str+= "<img src='/images/i_delete.svg' alt='' class='btn_delete' onclick=\"file_upload_remove('"+img_id+"_"+i+"_"+file_cnt+"');\"/>";
      str+= "<img src='"+img_list[i].path+"' alt=''>";
      }else{
      str+= img_list[i].orig_name;
      str+= " <img src='/images/i_delete.svg' alt='x' class='btn_delete'  onclick=\"file_upload_remove('"+img_id+"_"+i+"_"+file_cnt+"');\"/ >";
      }

      str+="<input type='hidden' name='"+img_id+"_path[]' id='"+img_id+"_"+i+"' value='"+img_list[i].path+"'/>";
      str+="<input type='hidden' name='"+img_id+"_file_size[]' id='"+img_id+"_file_size_"+i+"' value='"+img_list[i].file_size+"'/>";

      str+="<input type='checkbox' name='"+img_id+"'  value='"+img_list[i].path+"' checked style='display:none' />";
      str+="<input type='checkbox' name='"+img_id+"_org_name'  value='"+img_list[i].orig_name+"' checked style='display:none' />";

      str+="</div>";

       /*  

       str="<li id='id_file_"+img_id+"_"+i+"_"+file_cnt+"' style='display:inline-block;padding-right:10px;'>"

       if(file_type !="file"){
         str+= "<img src='/images/i_delete.svg' alt='x' class='btn_delete'  onclick=\"file_upload_remove('"+img_id+"_"+i+"_"+file_cnt+"');\"/ >"; 
         str+="<div class='img_box'><img src='"+img_list[i].path+"' alt=''></div>";
        // str+="<img style='width:"+width+"px;height:"+height+"px;' src='"+img_list[i].path+"'>";
       }else{
         str+= img_list[i].orig_name;
         str+= " <img src='/images/i_delete.svg' alt='x' class='btn_delete'  onclick=\"file_upload_remove('"+img_id+"_"+i+"_"+file_cnt+"');\"/ >";
       }

       str+="<input type='hidden' name='"+img_id+"_path[]' id='"+img_id+"_"+i+"' value='"+img_list[i].path+"'/>";
       str+="<input type='hidden' name='"+img_id+"_file_size[]' id='"+img_id+"_file_size_"+i+"' value='"+img_list[i].file_size+"'/>";
    
       str+="<input type='checkbox' name='"+img_id+"'  value='"+img_list[i].path+"' checked style='display:none' />";
       str+="<input type='checkbox' name='"+img_id+"_org_name'  value='"+img_list[i].orig_name+"' checked style='display:none' />";
       
       str+="</li>";
	   */

       $('#'+img_id).append(str);
      }
      file_cnt++;
    }

  });

}
var file_upload_remove = function(file_no){
$("#id_file_"+file_no).remove();
}
// -------------------------------------------------------------------------------------

//달력 세팅
$(document).ready(function() {
  for (var i = 0; i < 100; i++) {

    $("#s_date_"+i).datepicker({
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#e_date_"+i).datepicker("option", "minDate", selectedDate);
      }
    });

    $("#e_date_"+i).datepicker({
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#s_date_"+i).datepicker("option", "maxDate", selectedDate);
      }
    });
  }
});

 //3자리 단위마다 콤마 생성
  function addCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

//3자리 실행 함수
//실행 방법 _input_check type=>pirce로 설정
//input tag에 onkeyup로 함수 실행
function COM_number_comma(id){
  $("#"+id).val(addCommas($("#"+id).val().replace(/[^0-9]/g,"")));
}

//뒤로 가기
function COM_history_back_fn(){
  if(window.history.length==1){
    location.href = "/<?=mapping('main')?>";
  }else{
    history.go(-1);
  }
}

// 민지:s

// 이미지줌(확대/축소)
function set_modal_open(img){
  $("#zoom_img").attr('src', img);
  modal_open('img_origin');
  image_zoom();
}
function image_zoom(){
  var el = document.querySelector('#box_id');
  var pz = new PinchZoom.default(el, {
    draggableUnzoomed: false,
  });
}

// 위시리스트 토글버튼
function wish_btn(element){
  if($(element).hasClass("on")){
    $(element).removeClass("on");
  } else {
    $(element).addClass("on");
  }
}

// 탭메뉴 토글기능
$(document).ready(function() {
  $(".tab_area_wrap > div").hide();
  $(".tab_area_wrap > div").first().show();
  $(".tab_toggle_menu li").click(function() {
    var list = $(this).index();
    $(".tab_toggle_menu li").removeClass("active");
    $(this).addClass("active");

    $(".tab_area_wrap > div").hide();
    $(".tab_area_wrap > div").eq(list).show();
  });
});

// 사업자정보확인
function license_check(){
  var url = "http://www.ftc.go.kr/bizCommPop.do?wrkr_no="+form_license.license_no.value;
  window.open(url, "bizCommPop", "width=750, height=700;");
}


// 서머노트 데이타 세팅
function set_summernoteData(id){
  // Summernote에서 생성된 HTML 데이터 가져오기
  var summernoteData = $("#"+id).val();
  
  // 데이터를 임시적으로 div 요소에 추가하여 태그 선택
  var $tempDiv = $('<div>').html(summernoteData);
  var $allTags = $tempDiv.find('*');
  
  // 모든 태그에 8px 마진 적용
  $allTags.css({
    'margin': '8px'
  });

  $tempDiv.find('img').after('<div style="clear:both;"></div>');

  // 수정된 HTML 데이터를 다시 원래 위치에 적용
  $("#"+id).summernote('code', $tempDiv.html());

}



//summernote 에디터 tag 제거
function COM_summernote_to_string(){
  alert('11');
  var str = $('#contents').text().replace(/<\/p>/gi, "\n").replace(/<br\/?>/gi, "\n").replace(/<\/?[^>]+(>|$)/g, "");
  return str;
}

// 민지 :: 써머노트 플러그인 모둘
(function(){
  $.fn.summernote = function(options){
    var defaults = {
      height:400,
      lang: 'ko-KR',
      dialogsInBody: false,
      fontNames : [ 'NotoSansKR-Regular' ,'Arial', 'Helvetica', 'Verdana'] ,
      fontNamesIgnoreCheck : [ 'NotoSansKR-Regular' ],
      fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36'],
      callbacks: {
        onImageUpload: function(files, editor, welEditable) {
          for (var i = files.length - 1; i >= 0; i--) {
            sendFile(files[i], editor, welEditable);
          }
        }
      }
    };
    var options = $.extend({},defaults, options);
  }
})(jQuery);

//summernote editor contents parts
var postForm = function() {
   var content = $('textarea[name="+summernote_id+"]').html($('#'+summernote_id).code());
}

//에디터 이미지 등록
function sendFile(file,editor, welEditable) {
      var form_data = new FormData();
      form_data.append('file', file);
      form_data.append('id', 'id');
      form_data.append('device', 'image');
      $.ajax({
        data: form_data,
        dataType:'json',
        type: "POST",
        url: '/common/upload_file_json',
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(result) {
          $('textarea[name="+summernote_id+"]').summernote('insertImage',  result.url);
        }
    });
 }

// 민지:e

