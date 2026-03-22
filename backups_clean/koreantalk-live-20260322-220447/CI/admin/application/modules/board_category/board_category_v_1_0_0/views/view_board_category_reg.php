  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
		<div class="page-header">
			<h1>게시판 카테고리  > 등록</h1>
		</div>

    <!-- body : s -->
    <div class="bg_wh mt20">
			<div class="table-responsive">
        <form name="form_default" id="form_default" method="post">
    			<table class="table table-bordered td_left">
            <colgroup>
          	<col style="width:15%">
          	<col style="width:35%">
          	<col style="width:15%">
          	<col style="width:35%">
            </colgroup>

							<tbody>
  							<tr>
  								<th ><span class="text-danger">*</span> 카테고리명</th>
  								<td colspan=3>
                    <div class="form-group">
                      <input class="form-control" type="text" name="category_name" id="category_name" value="">
                    </div>
                  </td>
  							</tr>               
    					</tbody>
    				</table>
          </form>
          <div class="text-right">
						<a href="javascript:void(0)" onclick="default_list();" class="btn btn-gray">취소</a>
						<a href="javascript:void(0)" onclick="default_reg();" class="btn btn-success">등록</a>
  				</div>
      </div><!-- table-responsive -->
    </div>
    <!-- body : e -->
  </div>
  <!-- container-fluid : e -->
<script>
//등록
function default_reg() {


  $.ajax({
    url      : "/<?=mapping('board_category')?>/board_category_reg_in",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data : $('#form_default').serialize(),
    success  : function(result) {
			if(result.code == '-1'){
				alert(result.code_msg);
				$("#"+result.focus_id).focus();
				return;
			}
			// 0:실패 1:성공
			if(result.code == 0) {
				alert(result.code_msg);
			} else {
				alert(result.code_msg);
				location.href = "/<?=mapping('board_category')?>/board_category_list";
			}
    }

  });
}
// 뒤로가기
function default_list(){
  location.href ="/<?=mapping('board_category')?>/board_category_list";
}







</script>
