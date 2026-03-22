  <!-- container-fluid : s -->
  <div class="container-fluid wide">
		<!-- Page Heading -->
		<div class="page-header">
			<h1>시작팝업 상세</h1>
		</div>

    <!-- body : s -->
    <div class="bg_wh mt20">
    	<div class="table-responsive">
        <div class="col-lg-6 text-right" style="float:right;margin-bottom:10px;">등록일 : <?= $result->ins_date ?></div>
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
        			<tr >
                <th><span class="text-danger">*</span> 제목</th>
        				<td colspan="3"><input type="text" name="title" id="title" class="form-control" value="<?= $result->title ?>"></td>
              </tr>

              <tr>
                <th><span class="text-danger">*</span>
                  이미지<br>[600px * 852px]
                  <input type="button" class="btn btn-xs btn-default" id="file1" value="파일업로드" onclick="file_upload_click('img','image','1','240');" >
                </th>
                <td colspan="3">
                  <div class="view_img mg_btm_20" style="min-height:250px;">
                    <ul class="img_hz" id="img">
                      <?php if($result->img_path != ""){ ?>
                      <li id="id_file_img_0" style="display:inline-block;">
                        <img src="/images/btn_del.gif" style="width:15px "onclick="file_upload_remove('img_0')"/></br>
                        <a id="single_image" href="<?=$result->img_path?>">
                          <img src="<?=$result->img_path?>" style="width:240px">
                        </a>
                        <input type="hidden" name="img_path[]" id="img_path_0" value="<?=$result->img_path?>"/>
                      </li>
                    <?php }?>
                    </ul>
                  </div>
                  이미지의 파일 형식은 png 또는 jpg로 1장만 등록이 가능합니다.
                </td>
              </tr>
              <tr>
                <th>상태</th>
                <td colspan="3">
                  노출안함
                  <label class="switch">
                    <input type="checkbox" name="state" id="state" value="1" <?php echo $result->state=='1'? "checked":""; ?>>
                    <span class="check_slider"></span>
                  </label>
                  노출
                </td>
              </tr>
            </tbody>
        	</table>
        </section>
        <input type="hidden" name="start_popup_idx" value="<?= $result->start_popup_idx ?>" />
        </form>

        <div class="row">
          <div class="col-lg-12 text-right">
            <a href="javascript:void(0);" onclick="default_list();" class="btn btn-gray">목록</a>
            <a href="javascript:void(0);" onclick="default_del('<?=$result->start_popup_idx?>');" class="btn btn-danger">삭제</a>
            <a href="javascript:void(0)" class="btn btn-info" onclick="default_mod()">수정</a>
          </div>
        </div>
    	</div>
    </div>
    <!-- body : e -->

  </div>
  <!-- container-fluid : e -->



<script>
function default_list(){
  history.back(<?=$history_data?>);
}

function default_mod(){

  $.ajax({
    url: "/<?=mapping('start_popup')?>/start_popup_mod_up",
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
			if(result.code == '-2') {
				alert(result.code_msg);
			} else {
				alert(result.code_msg);
				location.reload();
			}
    }
  });
}

function default_del(start_popup_idx){

  if(!confirm("시작 팝업을 삭제하시겠습니까?")){
    return;
  }

  $.ajax({
    url      : "/<?=mapping('start_popup')?>/start_popup_del",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data     : {
      "start_popup_idx" : start_popup_idx
    },
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
				 location.href ="/<?=mapping('start_popup')?>/start_popup_list";
			}
    }
  });
}

</script>
