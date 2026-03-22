  <!-- container-fluid : s -->
  <div class="container-fluid wide">
    <!-- Page Heading -->
		<div class="page-header">
			<h1>관리자관리</h1>
		</div>

    <!-- body : s -->
    <div class="bg_wh mt20">
      <div class="table-responsive">
        <form name="form_default" id="form_default" method="post">

        <table class="search_table">
          <colgroup>
        	<col style="width:15%">
        	<col style="width:35%">
        	<col style="width:15%">
        	<col style="width:35%">
          </colgroup>
          <tbody>
            <tr>
              <th>관리자명</th>
              <td>
                <input name="admin_name" id="admin_name" class="form-control">
              </td>
              <th>관리자 ID</th>
              <td>
                <input name="admin_id" id="admin_id" class="form-control">
              </td>
            </tr>
            <tr>
             <th>사이트권한</th>
              <td colspan=3>
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">관리자</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
              <!-- <th>등록일</th>
              <td >
                <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
              </td> -->
            </tr>

          </tbody>
        </table>
        </form>
        <div class="text-center mt20">
          <a class="btn btn-success" href="javascript:void(0)" onclick="default_list_get(1);">검색</a>
        </div>
      </div>

			<div class="table-responsive">
				<div id="list_ajax">
					<!--리스트-->
				</div>
			</div>
    </div>
    <!-- body : e -->
  </div>
  <!-- container-fluid : e -->
  <input type="text" name="page_num" id="page_num" value="1"  style="display:none">
<script>
  //검색
  var default_list_get = function(page_num) {
    $('#page_num').val(page_num);

    var form_data = {
    'admin_id' : $('#admin_id').val(),
    'admin_name' : $('#admin_name').val(),
    's_date' : $('#s_date').val(),
    'e_date' : $('#e_date').val(),    
    'site_code' : $('#site_code').val(),    
    'page_num' : page_num
    };

    $.ajax({
      url: "/<?=mapping('suboperator')?>/suboperator_list_get",
      type: 'POST',
      dataType: 'html',
      async: true,
      data: form_data,
      success: function(dom) {
        $('#list_ajax').html(dom);
      }
    });
  }

  $(document).ready(function(){
    setTimeout("default_list_get($('#page_num').val())", 100);
});

  //관리자 상태 변경
	function admin_state_up (admin_state,admin_idx){
    if(!confirm("관리자 상태를 변경하시겠습니까?")){
      return;
    }
		$.ajax({
			url: "/<?=mapping('suboperator')?>/suboperator_state_up",
			type: 'POST',
			dataType: 'json',
			async: false,
			data: {
			 "admin_state": admin_state,
			 "admin_idx":admin_idx
		  },
			success: function(result) {
				if(result =='1'){
					alert('관리자 상태가 변경되었습니다.');

				}else if(result =='0'){
					alert('관리자 상태 변경에 실패하였습니다.');
				}
			}
		});
	}
</script>
