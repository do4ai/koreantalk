<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>TOPIK 영상 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <section>
        <form name="form_default" id="form_default" method="post">
          <?
          if($this->site_code !=""){
          ?>
        <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
        <?}?>
          <table class="table table-bordered td_left">
            <colgroup>
            	<col style="width:15%">
            	<col style="width:35%">
            	<col style="width:15%">
            	<col style="width:35%">
            </colgroup>
            <tbody>
            <?
            if($this->site_code ==""){
            ?>
           <tr>
              <th><span class="text-danger">*</span> 사이트 </th>
              <td colspan=3>
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>" <?=($result->site_code ==$row->site_code)?"selected":"";?>>
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
            </tr>
            <?}?>
            
              <tr>
                <th><span class="text-danger">*</span> 동영상이름</th>
                <td >
                  <input class="form-control" type="text" name="lecture_name" id="lecture_name" value="<?=$result->lecture_name?>">
                </td>
                <th>상태</th>
        				<td >
                  <select name="display_yn" class="form-control w_auto">
                    <option value="N" <?php if($result->display_yn =="N"){ echo "selected";}?>>비활성화</option>
                    <option value="Y" <?php if($result->display_yn =="Y"){ echo "selected";}?>>활성화</option>
                  </select>
                </td>
              </tr>
                 <tr>
                 <th><span class="text-danger">*</span>연관 전자책 </th>
                 <td colspan=3>
                 <select class="form-control" style="width:500px" id="electron_book_idx" name="electron_book_idx">
                     <option value="">전체</option>
                     <?php
                       if(!empty($electron_book_list)){
                         foreach($electron_book_list as $row){?>
                           <option value="<?=$row->electron_book_idx?>" <?=($result->electron_book_idx ==$row->electron_book_idx)?"selected":"";?> >[<?=$row->product_name?>/<?=$row->author?>] <?=number_format($row->product_price)?> </option>
                     <?php    }
                         }
                     ?>
                   </select>
                 
                 </td>             
               </tr>   
              
              <tr>
                <th>
                  사진[가로]
                  <p class="text-info">[670px]</p>
                  <input type="button" class="btn btn-xs btn-default" value="파일업로드" onclick="file_upload_click('img','image','1','100');" style="margin-bottom:10px">
                </th>
                <td colspan="3">
                  <div>
                    <ul class="img_hz" id="img">
                      <?php if($result->img_path != ""){ ?>
                        <li id="id_file_img_0" style="display:inline-block;">
                          <img src="/images/btn_del.gif" style="width:15px "onclick="file_upload_remove('img_0')"/><br>
                          <img src="<?=$result->img_path?>" style="width:100px">
                          <input type="hidden" name="img"  value="<?=$result->img_path?>"/>
                        </li>
                      <?php } ?>
                    </ul>
                  </div>
                </td>
              </tr>

              <tr>
                <th><span class="text-danger">*</span> 내용</th>
                <td colspan="3">
                  <textarea name="contents" id="contents" style="width:100%; height:100px;" placeholder="" class="input_default"><?=$result->contents?></textarea>
                </td>
              </tr>

          
    
            
            </tbody>
          </table>

          <div class="row table_title">
            <div class="col-lg-4"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp; 영상</div>
            <div class="col-lg-8 text-right"> &nbsp;<input class="form-control" style="width:300px" type="text" name="category_name" id="category_name" placeholder="카테고리명 입력해 주세요">            <a class="btn btn-info" onClick="lecture_category_reg_in()"> 카테고리 추가</a>
         
            </div>
          </div>
    
           <div class="bg_wh mb20" id="list_ajax"></div>   

          <input type="hidden" name="lecture_idx" id="lecture_idx" value="<?=$result->lecture_idx?>">
        </form>
      </section>

      <div class="text-right" style="float:right;">
        <a class="btn btn-gray" href="javascript:void(0)" onclick="default_list()">목록</a>
        <a class="btn btn-danger" href="javascript:void(0)" onclick="lecture_del('<?=$result->lecture_idx?>')">삭제</a>
        <a class="btn btn-success" href="javascript:void(0)" onclick="default_mod('<?=$result->lecture_idx?>')">수정</a>
      </div>

    </div>
  </div>
  <!-- body : e -->
</div>
<!-- container-fluid : e -->


  <!-- modal layerpop2 : s -->
  <div class="modal fade" id="layerpop2">
    <div class="modal-dialog" style="width:1050px;">
      <div class="modal-content" style="width:1050px;">
        <!-- header -->
        <div class="modal-header">
          <!-- 닫기(x) 버튼 -->
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title" >영상추가</h4>
        </div>
        <!-- body -->
        <div class="modal-body">
          <div class="table-responsive">
            <table class="search_table">
              <colgroup>
                <col style="width:15%">
                <col style="width:35%">
                <col style="width:15%">
                <col style="width:35%">

              </colgroup>
              <tbody>
                 <tr>
                  <th>카테고리</th>
                  <td colspan="3" id="pop_title"> 
                  </td>
                </tr>
                <tr>
                  <th>목차명</th>
                  <td colspan="3">
                    <input class="form-control" name="movie_name" id="movie_name" value="" placeholder="목차명을 입력해 주세요.">
                  </td>
                </tr>
                <tr>
                  <th>영상시간(분:초)</th>
                  <td colspan="3">
                    <input class="form-control"   style="width:70px"   name="movie_time" id="movie_time" maxlength=10 value="" placeholder="03:50">  예시: 3분 50초               </td>
                </tr>
                <tr>
                  <th>영상url</th>
                  <td colspan="3">
                    <input class="form-control" name="movie_url" id="movie_url" value="" placeholder="youtube 영상url을 입력해주세요.">
                  </td>
                </tr>
              </tbody>

            </table>
            <div class="text-center mt20" style="">
              <a href="javascript:void(0)" class="btn btn-gray" data-dismiss="modal" id="btn_cancel_2">취소</a>
              <a href="javascript:void(0)" onclick="lecture_movie_reg_in();" class="btn btn-success">등록</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal layerpop2 : e -->


    <!-- modal layerpop3 : s -->
    <div class="modal fade" id="layerpop3">
    <div class="modal-dialog" style="width:1050px;">
      <div class="modal-content" style="width:1050px;">
        <!-- header -->
        <div class="modal-header">
          <!-- 닫기(x) 버튼 -->
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">수정</h4>
        </div>
        <!-- body -->
        <div class="modal-body">
          <div class="table-responsive">
            <table class="search_table">
              <colgroup>
                <col style="width:15%">
                <col style="width:35%">
                <col style="width:15%">
                <col style="width:35%">
              </colgroup>
              <tbody>

               <tbody>
                <tr>
                  <th>목차명</th>
                  <td colspan="3">
                    <input type="hidden" name="lecture_movie_idx" id="lecture_movie_idx" value="">                    
                    <input class="form-control" name="_movie_name" id="_movie_name" value="" placeholder="최대 30자">
                  </td>
                </tr>
                <tr>
                  <th>영상시간</th>
                  <td colspan="3">
                    <input class="form-control" name="_movie_time" id="_movie_time" value="" placeholder="최대 30자">
                  </td>
                </tr>
                <tr>
                  <th>영상url</th>
                  <td colspan="3">
                    <input class="form-control" name="_movie_url" id="_movie_url" value="" placeholder="최대 30자">
                  </td>
                </tr>
              </tbody>
              </tbody>
            </table>
            <div class="text-center mt20" style="">
              <a href="javascript:void(0)" class="btn btn-gray" data-dismiss="modal" id="btn_cancel_3">취소</a>
              <a href="javascript:void(0)" onclick="lecture_movie_mod_up();" class="btn btn-success">수정</a>
              <!-- <a href="javascript:void(0)" onclick="lecture_movie_del();" class="btn btn-danger">삭제</a> -->
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal layerpop3 : e -->



<script>
  function default_list(){
    history.back(<?=$history_data?>);
  }
  
  let lecture_idx ="<?=$result->lecture_idx?>";
  let lecture_category_idx ="";
  let sortable_category_idx ="";
  // 교육영상 수정
  function default_mod() {
    if($("#site_code").val() ==""){
      alert("사이트를 선택해 주세요");
      return;
    }

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_mod_up",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : $("#form_default").serialize(),
      success : function(result) {
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == "-2") {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }

  // 교육영상 삭제 
  function lecture_del(){

    if(!confirm("교육영상을 삭제하시겠습니까?")){
      return;
    }

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "lecture_idx" : lecture_idx
      },
      success: function(result) {
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == "-2") {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }

  function default_list_get(){

    var formData = {
      'lecture_idx' : lecture_idx,
    };

    $.ajax({
      url      : "/<?=mapping('lecture')?>/lecture_movie_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  
//등록
  function lecture_category_reg_in() {
    
  	var form_data = {
      'category_name' : $('#category_name').val(),
      'lecture_idx' : lecture_idx,
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_category_reg_in",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {      
          $('#category_name').val('');  
          default_list_get();
        }
      }
   });
  }

    
  //삭제
  function lecture_category_del(lecture_category_idx) {
    if(!confirm('삭제 하시겠습니까?')){
      return;
    }
    
  	var form_data = {      
      'lecture_category_idx' : lecture_category_idx,
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_category_del",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {        
          default_list_get();
        }
      }
   });
  }

  //상세
  function set_category_idx(category_idx,title) {
     $('#pop_title').html(title);
    lecture_category_idx= category_idx   
  } 


//등록
  function lecture_movie_reg_in() {
    
  	var form_data = {
      'movie_name' : $('#movie_name').val(),
      'movie_time' : $('#movie_time').val(),
      'movie_url' : $('#movie_url').val(),
      'lecture_category_idx' : lecture_category_idx,
      'lecture_idx' : lecture_idx,
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_movie_reg_in",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {        
          $('#btn_cancel_2').trigger('click'); 
          $('#movie_name').val('');
          $('#movie_time').val('');
          $('#movie_url').val('');
          default_list_get();
        }
      }
   });
  }

  
  //삭제
  function lecture_movie_del(lecture_movie_idx) {

    if(!confirm('삭제 하시겠습니까?')){
      return;
    }
    
  	var form_data = {
      'lecture_movie_idx' :lecture_movie_idx,
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_movie_del",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {    
          
          default_list_get();
        }
      }
   });
  }
  

  // 드래그 카테고리 키
  function set_sortable_category_idx(category_idx) {    
    sortable_category_idx= category_idx   
  }


  //추천
  function lecture_movie_main_view_yn_mod_up(lecture_movie_idx) {    
    
    var form_data = {
        'lecture_movie_idx' : lecture_movie_idx,
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_movie_main_view_yn_mod_up",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
        //  $('#btn_cancel_3').trigger('click');
        //  $(default_list_get());
        }
      }
   });
  } 

  //순서 변경
  function lecture_movie_order_no_mod_up() {
    let item = "sortable_movie_"+sortable_category_idx;    
    let lecture_movie_idx= get_checkbox_value(item);  	  
    
    var form_data = {
        'lecture_movie_idx' : lecture_movie_idx,
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_movie_order_no_mod_up",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
        //  $('#btn_cancel_3').trigger('click');
          $(default_list_get());
        }
      }
   });
  } 


  //상세
  function lecture_movie_detail(lecture_movie_idx,movie_name,movie_time,movie_url) {
    $('#lecture_movie_idx').val(lecture_movie_idx);
    $('#_movie_name').val(movie_name);
    $('#_movie_time').val(movie_time);
    $('#_movie_url').val(movie_url);
  }
  
  //수정
  function lecture_movie_mod_up() {
    
  	var form_data = {
      'lecture_movie_idx' : $('#lecture_movie_idx').val(),
      'movie_name' : $('#_movie_name').val(),
      'movie_time' : $('#_movie_time').val(),
      'movie_url' : $('#_movie_url').val(),
    };

    $.ajax({
      url: "/<?=mapping('lecture')?>/lecture_movie_mod_up",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: form_data,
      success: function(result) {  
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          $('#btn_cancel_3').trigger('click');
          $(default_list_get());
        }
      }
   });
  }

  $(document).ready(function(){
    default_list_get();
  })

</script>
