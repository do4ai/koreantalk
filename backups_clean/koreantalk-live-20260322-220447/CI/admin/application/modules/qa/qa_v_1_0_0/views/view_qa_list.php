<!-- container-fluid : s -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="page-header">
		<h1>1:1 문의 관리</h1>
	</div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">
      <form name="form_default" id="form_default" method="post">

       <input type="text" name="page_num" id="page_num" value="1"  style="display:none">
      
    		<table class="search_table">
					<colgroup>
						<col style="width:15%">
						<col style="width:35%">
						<col style="width:15%">
						<col style="width:35%">
					</colgroup>
    			<tbody>


            <tr>
              <th width="150">아이디</th>
              <td>
                <input name="member_id" id="member_id" class="form-control" style="width:200px" placeholder="">
              </td>
              <th width="150">문의일</th>
              <td>
                <input name="s_date" id="s_date" class="form-control" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input name="e_date" id="e_date" class="form-control" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
              </td>
            </tr>

             <?
            if($this->site_code ==""){
            ?>
            <tr>
              <th>사이트</th>
              <td >
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
              <th width="150">답변 상태</th>
              <td >
                <input type="radio" name="reply_yn" id="reply_yn_1" checked value=""> 전체
                <input type="radio" name="reply_yn" id="reply_yn_2" value="N"> 미답변
                <input type="radio" name="reply_yn" id="reply_yn_3" value="Y"> 답변완료
              </td>
            </tr>
      
            <?}else{?>

              <th width="150">답변 상태</th>
              <td colspan=3>
                <input type="radio" name="reply_yn" id="reply_yn_1" checked value=""> 전체
                <input type="radio" name="reply_yn" id="reply_yn_2" value="N"> 미답변
                <input type="radio" name="reply_yn" id="reply_yn_3" value="Y"> 답변완료

                <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
              </td>
            </tr>
            <?}?>

    			</tbody>
    		</table>
      </form>

  		<div class="text-center mt20">
  			<a href="javascript:void(0)" class="btn btn-success" onclick="default_list_get(1);">검색</a>
  		</div>

  	</div>
    <!-- search : e -->

  	<div class="table-responsive">
      <div id="list_ajax"></div>
  	</div>
  </div>
  <!-- body : e -->
</div>

<script>

  $(function(){
		default_list_get(1);
	});

  function default_list_get(page){

    $('#page_num').val(page);
		var form_data = {
			'site_code' : $('#site_code').val(),
			's_date' : $('#s_date').val(),
			'e_date' : $('#e_date').val(),
			'member_id' : $('#member_id').val(),
			'qa_title' : $('#qa_title').val(),
      'qa_category' : $('#qa_category').val(),
			'reply_yn' :  $("input[name='reply_yn']:checked").val(),
      'history_data': window.history.length,
			'page_num' : page
		};

    $.ajax({
      url : "/<?=mapping('qa')?>/qa_list_get",
      type : "POST",
      dataType : "html",
      async : true,
      data : form_data,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  function chkBox(bool) {
	  var obj = document.getElementsByName("checkbox");
	  for (var i=0; i<obj.length; i++) {
	    obj[i].checked = bool;
	  }
  }

  //선택삭제
  var default_select_del = function(){

		var selected_idx =  get_checkbox_value('checkbox');

		if(selected_idx.length<1){
		  alert("선택된 항목가 없습니다.");
		  return  false;
		}

	  if(!confirm("선택된 항목를 삭제하시겠습니까?")){
	    return;
	  }

	  $.ajax({
	    url : "/<?=mapping('qa')?>/qa_del",
	    type : 'POST',
	    dataType : 'json',
	    async : true,
	    data : {
	      "qa_idx" : selected_idx
	    },
	    success: function(result) {
	      if(result.code == "-1"){
	        alert(result.code_msg);
	      }else{
	        alert("삭제 되었습니다.");
	        get_current_page();
	      }
	    }
	  });

  }

	//현재페이지 가져오기
	function get_current_page(){
		var page =	$('#page_num').val();
		default_list_get(page);
	}

</script>
