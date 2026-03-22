  <!-- container-fluid : s -->
  <div class="container-fluid wide">
		<!-- Page Heading -->
		<div class="page-header">
			<h1>시작팝업 등록</h1>
		</div>

    <!-- body : s -->
    <div class="bg_wh mt20">
    	<div class="table-responsive">
        <form name="form_default" id="form_default" autocomplete="off">
        <section>
        	<table class="table table-bordered td_left">
            <colgroup>
            	<col style="width:15%">
            	<col style="width:35%">
            	<col style="width:15%">
            	<col style="width:35%">
            </colgroup>
        		<tbody>
        			<tr>
                <th><span class="text-danger">*</span> 제목</th>
        				<td colspan="3"><input type="text" name="title"  id="title" class="form-control" ></td>
              </tr>

              <tr>
                <th><span class="text-danger">*</span>
                  이미지<br>
                  <p class="text-info">[600px * 852px]</p>
                  <input type="button" class="btn btn-xs btn-default" id="file1" value="파일업로드" onclick="file_upload_click('img','image','1','240');" >

                </th>
                <td colspan="3">
                  <div class="view_img mg_btm_20">
                    <ul class="img_hz" id="img"  style="min-height:250px;">

                    </ul>
                  </div>
                  이미지의 파일 형식은 png 또는 jpg로 1장만 등록이 가능합니다.
                </td>
              </tr>
              <tr>
                <th>노출 여부</th>
                <td colspan="3">
                  노출안함
                  <label class="switch">
                    <input type="checkbox" name="state" id="state" value="1">
                    <span class="check_slider"></span>
                  </label>
                  노출
                </td>
              </tr>
            </tbody>
        	</table>
        </section>
        </form>

        <div class="row">
          <div class="col-lg-12 text-right">
            <a href="javascript:history.go(-1)" class="btn btn-gray">목록</a>
            <a href="javascript:void(0)" class="btn btn-success" onclick="default_reg()">등록</a>
          </div>
        </div>
    	</div>
    </div>
    <!-- body : e -->

  </div>


<script>
  function default_reg(){

    $.ajax({
      url: "/<?=mapping('start_popup')?>/start_popup_reg_in",
      type: "post",
      data : $('#form_default').serialize(),
      dataType: 'json',
      async: true,
      success: function(result){
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == -2) {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.href ='/<?=mapping('start_popup')?>/start_popup_list';
        }
      }
    });
  }


  </script>
