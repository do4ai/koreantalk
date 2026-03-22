<form name="form_default" id="form_default" method="post">
  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>시작팝업 관리</h1>
    </div>

    <!-- body : s -->
    <div class="bg_wh mt20">
      <div class="table-responsive">

        <table class="table table-bordered td_left">
          <tbody>
            <tr>
              <th width="100">제목</th>
              <td>
                <input name="title" id="title" type="text" class="form-control" value="<?=$result->title?>">
              </td>
            </tr>
            <tr>
              <th>활성여부</th>
              <td>
                <select name="state" id="state" class="form-control w_auto">
                  <option value="1" <?php if($result->state == 1) { echo('selected'); }?> >활성</option>
                  <option value="0" <?php if($result->state == 0) { echo('selected'); }?> >비활성</option>
                </select>
              </td>
            </tr>
            <tr>
              <th> 기간</th>
              <td>
                <input class="form-control datepicker" value="<?=$result->start_date?>" name="start_date" id="start_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input class="form-control datepicker" value="<?=$result->end_date?>" name="end_date" id="end_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
              </td>
            </tr>
            <tr>
              <th>내용</th>
              <td>
                <textarea class="form-control" rows="8" cols="80" name="contents" id ="contents" placeholder="내용"><?=$result->contents?></textarea>
              </td>
            </tr>
            <tr>
              <th>이미지
                <input type="button" class="btn btn-xs btn-default" id="file1" value="사진등록" onclick="file_upload_click('img','image','1','500');">
              </th>
              <td>

                <div class="view_img mg_btm_20">
                  <ul id="img" class="img_hz">
                  <?php
                  $file_cnt=1;
                  if(count((array)$result->img_path)>0){
                  ?>
                  <li id="id_file_<?=$file_cnt?>">
                    <img src="/images/btn_del.gif" style="width:15px "onclick="file_upload_remove('<?=$file_cnt?>')"/><br>
                    <img  src="<?=$result->img_path?>" style="width:500px">
                    <input type='hidden' name='img_path[]' id='img_path' value='<?=$result->img_path?>'/>
                  </li>
                  <?php
                  $file_cnt++;
                  }
                  ?>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
            	<th>URL</th>
            	<td>
            	  <input name="link_url" id="link_url" class="form-control" style="width:500px" value="<?=$result->link_url?>">
            	</td>
            </tr>
          </tbody>
        </table>

        <div class="text-right">
          <a href="/<?=mapping('start_popup')?>" class="btn btn-gray">취소</a>
          <a href="javascript:void(0)"  onclick="default_mod();" class="btn btn-success">수정</a>
        </div>

      </div>
    </div>
    <!-- body : s -->
    <input name="start_popup_idx" id="start_popup_idx" type="hidden" value="<?=$result->start_popup_idx?>">
  </div>
  <!-- container-fluid : e -->
</form>


<script>

	$(function(){

	  $("#start_date").datepicker({
	    defaultDate: "+0w",
			dateFormat: "yy-mm-dd",
			prevText: '이전 달',
			nextText: '다음 달',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			showMonthAfterYear: true,
			changeMonth: true,
			changeYear: true,
	    changeMonth: true,
	    numberOfMonths: 1,
	    onClose: function(selectedDate) {
	      $("#end_date").datepicker( "option", "minDate", selectedDate );

	    }
	  });
	  $("#end_date").datepicker({
	    defaultDate: "+0w",
		  dateFormat: "yy-mm-dd",
			prevText: '이전 달',
			nextText: '다음 달',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			showMonthAfterYear: true,
			changeMonth: true,
			changeYear: true,
	    changeMonth: true,
	    numberOfMonths: 1,
	    onClose: function(selectedDate) {
	      $("#start_date").datepicker( "option", "maxDate", selectedDate );
	    }
	  });

	});

function default_mod() {

  $.ajax({
    url: "/<?=mapping('start_popup')?>/start_popup_mod_up",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: $("#form_default").serialize(),
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
  			location.href = '/<?=mapping('start_popup')?>/start_popup';
		  }
    }
  });
}


</script>
