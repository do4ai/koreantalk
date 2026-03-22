<!-- container-fluid : s -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="page-header">
    <h1>게시판 카테고리  > 수정</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <form name="form_default" id="form_default" method="post">
      <input type="hidden" name="board_category_idx" id="board_category_idx" value="<?=$result->board_category_idx?>">
        <table class="table table-bordered td_left">
            <colgroup>
          	<col style="width:15%">
          	<col style="width:35%">
          	<col style="width:15%">
          	<col style="width:35%">
            </colgroup>
            <tbody>
              <tr>
                <th ><span class="text-danger">*</span> 배너명</th>
                <td colspan=3>
                  <div class="form-group">
                    <input class="form-control" type="text" name="category_name" id="category_name" value="<?=$result->category_name?>">
                  </div>
                </td>
              </tr>
              <tr>
                <th > 등록된 게시물수</th>
                <td colspan=3>
                 <?=$result->board_cnt?>
                </td>
              </tr>
            
            </tbody>
          </table>
        </form>
        <div class="text-right">
          <a href="javascript:void(0)" onclick="list_page();" class="btn btn-gray">취소</a>
          <a href="javascript:void(0)" onclick="default_mod();" class="btn btn-success">수정</a>
        </div>
    </div><!-- table-responsive -->
  </div>
  <!-- body : e -->
</div>
<!-- container-fluid : e -->
<script>

$(document).ready(function() {
$("#board_category_s_date").datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
});

$("#board_category_e_date").datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
});
}) // end ready()

//수정
function default_mod(){
  $.ajax({
    url      : "/<?=mapping('board_category')?>/board_category_mod_up",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data : $('#form_default').serialize(),
    success  : function(result){
      if(result.code == "-1"){
        alert(result.code_msg);
      }else{
        alert("배너가 수정 되었습니다.");
        list_page();
      }
    }
  });
}

//삭제
function default_del(){
  if(!confirm("삭제하시겠습니까?\n삭제이후에는 복원되지 않습니다.")){
    return;
  }
  var form_data = {
  'board_category_idx' : $('#board_category_idx').val()
  };

  $.ajax({
    url      : "/<?=mapping('board_category')?>/board_category_del",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data : form_data,
    success  : function(result){
      if(result.code == "-1"){
        alert(result.code_msg);
      }else{
        alert("배너가 삭제 되었습니다.");
        list_page();
      }
    }
  });
}

function list_page(){
  history.back(<?=$history_data?>);
}


</script>
