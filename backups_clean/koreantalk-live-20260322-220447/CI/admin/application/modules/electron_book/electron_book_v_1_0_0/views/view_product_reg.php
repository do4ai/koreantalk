
  <div class="container-fluid">
   <!-- Page Heading -->
   <div class="page-header">
     <h1>전자책 관리</h1>
   </div>
   <!-- body : s -->
   <div class="bg_wh mt20">
   	<div class="table-responsive">
       <section>
         <div class="row table_title">
           <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp; 등록</div>
         </div>

         <form name="form_default" id="form_default">
           <input name="product_idx" id="product_idx" type="hidden">                    
           <input name="pdf_url" id="pdf_url" type="hidden" value="">    
           <input name="product_img_path" id="product_img_path" type="hidden" value="">    
           <input name="product_detail_img_path" id="product_detail_img_path" type="hidden" value="">    
           <input name="product_price" id="product_price" type="hidden" value=""> 
           <input type="text" style="display:none" id="site_code" name="site_code" value="us">
           <?
          if($this->site_code !=""){
          ?>
            <input type="text" style="display:none" id="site_code_" name="site_code_" value="<?=$this->site_code?>">
          <?}?>

           <table class="table table-bordered td_left">
             <colgroup>
                 <col style="width:150px">
                 <col style="width:350px">
                 <col style="width:150px">
                 <col style="width:350px">
              </colgroup>
         	   <tbody>
                <?
                if($this->site_code ==""){
                ?>
                <!-- <tr>
                  <th>사이트</th>
                  <td colspan=3>
                  <select name="site_code" id="site_code" class="form-control w_auto">
                    <option value="">선택</option>
                      <?php foreach($site_list as $row){ ?>
                        <option value="<?=$row->site_code?>"  >
                          <?=$row->site_name?>
                        </option>
                      <?php } ?>
                  </select>
                  </td>
                </tr> -->
                <?}?>
                <tr>
                 <th><span class="text-danger">*</span>상품명 </th>
                 <td colspan=3><input class="form-control" type="text" id="product_name" name="product_name" value=""></td>                      
               </tr>

               <tr>
                 <th><span class="text-danger">*</span>저자 </th>
                 <td colspan=3><input class="form-control" type="text" id="author" name="author" value=""></td>
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
                 <th><span class="text-danger">*</span>연관 TOPIK 영상 </th>
                 <td colspan=3>
                 <select class="form-control" style="width:500px" id="lecture_movie_idx" name="lecture_movie_idx">
                     <option value="">전체</option>
                     <?php
                       if(!empty($lecture_movie_list)){
                         foreach($lecture_movie_list as $row){?>
                           <option value="<?=$row->lecture_movie_idx?>"  >[<?=$row->lecture_name?>/<?=$row->category_name?>] <?=$row->movie_name?> (<?=$row->movie_time?>)</option>
                     <?php    }
                         }
                     ?>
                   </select>
                 
                 </td>             
               </tr>      
					      <tr>
                 <th>
                   <span class="text-danger">*</span> pdf</br>
                   <input type="button" class="btn btn-xs btn-default" id="file1" value="업로드" onclick="file_upload_click('pdf','file','1','100');">
                 </th>
                 <td colspan="3">
                   <div class="view_img mg_btm_20">
                     <ul class="img_hz" id="pdf"></ul>
                   </div>
                 </td>
               </tr>

							 <tr>
                 <th>
                   <span class="text-danger">*</span> 대표 이미지</br>(최대 1장,600*400)</br>
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
                   <span class="text-danger">*</span>상세 이미지</br>(최대 10장,600*400)</br>
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
                  <th colspan="4">상품 설명</th>
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


//등록
function default_reg(){
  if($("#site_code").val() ==""){
    alert("사이트를 선택해 주세요");
    return;
  }

  $('#pdf_url').val(get_checkbox_value('pdf')); 
  $('#product_img_path').val(get_checkbox_value('img')); 
  $('#product_detail_img_path').val(get_checkbox_value('detail_img')); 
  $('#product_price').val(removeCommas( $('#_product_price').val())); 

  $.ajax({
    url      : "/<?=mapping('electron_book')?>/product_reg_in",
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
        location.href="/<?=mapping('electron_book')?>/product_list";
      }
    }
  });
}
</script>
