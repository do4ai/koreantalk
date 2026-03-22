
  <div class="container-fluid">
   <!-- Page Heading -->
   <div class="page-header">
     <h1>상품 관리</h1>
   </div>
   <!-- body : s -->
   <div class="bg_wh mt20">
   	<div class="table-responsive">
       <section>
         <div class="row table_title">
           <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;상품 등록</div>
         </div>

         <form name="form_default" id="form_default">
           <input name="product_idx" id="product_idx" type="hidden">      
           <input name="product_img_path" id="product_img_path" type="hidden" value="">    
           <input name="product_detail_img_path" id="product_detail_img_path" type="hidden" value="">    
           <input name="product_price" id="product_price" type="hidden" value=""> 

           <table class="table table-bordered td_left">
             <colgroup>
                 <col style="width:150px">
                 <col style="width:350px">
                 <col style="width:150px">
                 <col style="width:350px">
              </colgroup>
         	   <tbody>
              <!-- <tr>
                 <th><span class="text-danger">*</span> 분류 </th>
                 <td colspan="3">
                   <select class="form-control" style="width:170px" id="product_b_category_idx" name="product_b_category_idx" onchange="category_m_list(this.value)">
                     <option value="">전체</option>
                     <?php
                       if(!empty($category_list)){
                         foreach($category_list as $row){?>
                           <option value="<?=$row->category_management_idx?>^<?=$row->category_name?>"><?=$row->category_name?></option>
                     <?php    }
                         }
                     ?>
                   </select>
                   >
                   <select class="form-control" style="width:170px" id="product_m_category_idx" name="product_m_category_idx" onchange="category_s_list(this.value)">
                     <option value="">전체</option>
                   </select>
                   >
                   <select class="form-control" style="width:170px" id="product_s_category_idx" name="product_s_category_idx">
                     <option value="">전체</option>
                   </select> <a class="btn-sm btn-success" href="#" onclick="category_add()">추가</a><br>
                   <div id="category_ajax"></div>
                 </td>
               </tr> -->
               <tr>
                 <th><span class="text-danger">*</span>상품명 </th>
                 <td colspan=3><input class="form-control" type="text" id="product_name" name="product_name" value=""></td>
             
             
               </tr>
               <tr>
                 <th><span class="text-danger">*</span>저자 </th>
                 <td colspan=3><input class="form-control" type="text" id="author" name="author"  value=""></td>
               </tr>
               <tr>
                 <th><span class="text-danger">*</span>판매가 </th>
                 <td><input class="form-control" type="text" name="_product_price" id="_product_price" value="" onKeyup="this.value=addCommas(this.value.replace(/[^0-9]/g,''));"></td>
                <th><span class="text-danger">*</span>노출상태 </th>
                 <td> <input type="radio" name="display_yn" id="display_yn"  value="N"> 비활성화
											<input type="radio" name="display_yn" id="display_yn" checked value="Y"> 활성화
                   </td>
               </tr>
               <tr>
                 <th><span class="text-danger">*</span>스마트 스토어 url </th>
                 <td colspan=3><input class="form-control" type="text" id="smart_store_url" name="smart_store_url" value=""></td>             
               </tr>
                <tr>
                 <th><span class="text-danger">*</span>연관 TOPIK 영상 </th>
                 <td colspan=3>
                 <select class="form-control" style="width:500px" id="lecture_movie_idx" name="lecture_movie_idx">
                     <option value="">전체</option>
                     <?php
                       if(!empty($lecture_movie_list)){
                         foreach($lecture_movie_list as $row){?>
                           <option value="<?=$row->lecture_movie_idx?>">[<?=$row->lecture_name?>/<?=$row->category_name?>] <?=$row->movie_name?> (<?=$row->movie_time?>)</option>
                     <?php    }
                         }
                     ?>
                   </select>
                 
                 </td>             
               </tr>
							 <tr>
                 <th>
                   <span class="text-danger">*</span> 대표 이미지</br>(최대1장,600*400)</br>
                   <input type="button" class="btn btn-xs btn-default" id="file1" value="업로드" onclick="file_upload_click('img','image','1','100');">
                 </th>
                 <td colspan="3">
                   <div class="view_img mg_btm_20">
                     <ul class="img_hz" id="img"></ul>
                   </div>
                 </td>
               </tr>

               <tr>
                 <th>
                   <span class="text-danger">*</span>상세이미지</br>(최대10장,600*400)</br>
                   <input type="button" class="btn btn-xs btn-default" id="file1" value="업로드" onclick="file_upload_click('detail_img','image','10','100');">
                 </th>
                 <td colspan="3">
                   <div class="view_img mg_btm_20">
                     <ul class="img_hz" id="detail_img"></ul>
                   </div>
                 </td>
               </tr>
			      <tr>
                 <th><span class="text-danger">*</span>간략설명 </th>
                 <td colspan=3><input class="form-control" type="text" id="product_desc" name="product_desc" value=""></td>
             
             
               </tr>
	
							

 
                <tr>
                  <th colspan="4">상품설명</th>
                </tr>
                <tr >
                  <td colspan="4" >
                    <div class="editor_area btn-editor"  >
                      <textarea class="input-block-level" id="product_contents" name="product_contents"  ></textarea>
                    </div>
                  </td>
                </tr>
							
              </tbody>
            </table>
          </form>
        </section>

        <div class="text-right">
          <a href="javascript:history.go(-1)" class="btn btn-gray">취소</a>
          <a href="javascript:void(0)" class="btn btn-success" onclick="default_reg();">등록</a>
        </div>

    </div>
   </div>

  <!-- body : s -->
<script>
$(function() {
/* 이미지 업로드시 사용 */
  $('#product_contents').summernote({
    height: '200',
    lang: 'ko-KR',
    dialogsInBody: false,
    callbacks: {
          onImageUpload: function(files, editor, welEditable) {
            for (var i = files.length - 1; i >= 0; i--) {
              sendFile(files[i], editor, welEditable,'product_contents');
            }
          }
        }
  });
  
  //select2 
  $('#lecture_movie_idx').select2(); 
});

//summernote editor contents parts
var postForm = function() {
   $('textarea[name="product_contents"]').html($('#product_contents').code());   
}

//에디터 이미지 등록
function sendFile(file,editor, welEditable,name) {
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
          $('textarea[name="'+name+'"]').summernote('insertImage',  result.url);
        }
    });
 }
</script>

<script>
var category_num=0;

//분류::중분류 가자요기
var category_m_list = function(category_idx) {

  $.ajax({
    url: "/<?=mapping('product')?>/category_list",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: {
        "category_idx" : category_idx
    },
    success: function(dom){
      var selectStr = "";

      $('#product_m_category_idx').html("<option value=''>전체</option>");
      if(dom.length != 0) {
        for(var i = 0; i < dom.length; i ++) {
          selectStr += "<option value='"+ dom[i].category_management_idx +"^"+dom[i].category_name + "'>" + dom[i].category_name + "</option>";
        }
        $('#product_m_category_idx').append(selectStr);
      }
    }
  });
}

//분류::소분류 가자요기
var category_s_list = function(category_idx) {

  $.ajax({
    url: "/<?=mapping('product')?>/category_list",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: {
        "category_idx" : category_idx
    },
    success: function(dom){
      var selectStr = "";

      $('#product_s_category_idx').html("<option value=''>전체</option>");
      if(dom.length != 0) {
        for(var i = 0; i < dom.length; i ++) {
          selectStr += "<option value='"+ dom[i].category_management_idx +"^"+dom[i].category_name + "'>" + dom[i].category_name + "</option>";
        }
        $('#product_s_category_idx').append(selectStr);
      }
    }
  });
}


//카테고리 추가
function category_add(){

  str ='<span id="arr_category_'+category_num+'">#. ';
  product_cateogry ='';
  var num=0;
  if($("#product_b_category_idx").val() !=""){
    num++;
    temp_b = $("#product_b_category_idx").val().split('^');
    str +=temp_b[1];
    product_cateogry +=$("#product_b_category_idx").val();
  }else{
    alert("대분류를 선택해 주세요.");
    $("#product_b_category_idx").focus();
    return;
  }

  if($("#product_m_category_idx").val() !=""){
    num++;
    temp_m = $("#product_m_category_idx").val().split('^');
    str +=' >'+temp_m[1];
    product_cateogry +=','+$("#product_m_category_idx").val();
  }else{
    product_cateogry +=',';
  }
  if($("#product_s_category_idx").val() !=""){
    num++;
    temp_s = $("#product_s_category_idx").val().split('^');
    str +=' >'+temp_s[1];
    product_cateogry +=','+$("#product_s_category_idx").val();
  }else{
    product_cateogry +=',';
  }
  if(num>0){
    str +=' <a onClick=arr_category_del("'+category_num+'");>[삭제]</a><input type=hidden name="product_category[]" value="'+product_cateogry+'" ></br></span>';
    $("#category_ajax").append(str);
    category_num ++;
  }

}

//카테고리삭제
function arr_category_del(category_num){
  $("#arr_category_"+category_num).remove();
}



//등록
function default_reg(){

  $('#product_img_path').val(get_checkbox_value('img')); 
  $('#product_detail_img_path').val(get_checkbox_value('detail_img')); 
  $('#product_price').val(removeCommas( $('#_product_price').val())); 

  $.ajax({
    url      : "/<?=mapping('product')?>/product_reg_in",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data     : $('#form_default').serialize(),
    success  : function(result) {
      if(result.code == '-1'){
        alert(result.code_msg);
        $("#"+result.focus_id).focus();
        return;
      }
      // 0:실패 1:성공
      if(result.code == '-2') {
        alert(result.code_msg);
      } else {
        alert(result.code_msg);
        location.href="/<?=mapping('product')?>/product_list";
      }
    }
  });
}
</script>
