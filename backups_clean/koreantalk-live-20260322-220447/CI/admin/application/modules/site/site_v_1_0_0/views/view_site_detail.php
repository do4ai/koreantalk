<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>사이트  관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <section>
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
                <th> <span class="text-danger">*</span> 사이트 코드</th>
                <td colspan="3">
                  <input name="site_code" id="site_code" value="<?=$result->site_code?>" maxlength=10 type="text"   class="form-control" style="width:500px">
                </td>
              </tr>
               <tr>
                <th> <span class="text-danger">*</span> 사이트 명</th>
                <td colspan="3">
                  <input name="site_name" id="site_name" value="<?=$result->site_name?>" type="text"   class="form-control" style="width:500px">
                </td>
              </tr>       

           

            </tbody>
          </table>
          <input type="hidden" name="site_idx" id="site_idx" value="<?=$result->site_idx?>">
        </form>
      </section>

      <div class="text-right" style="float:right;">
        <a class="btn btn-gray" href="javascript:void(0)" onClick="default_list()">목록</a> 
        <a class="btn btn-info" href="javascript:void(0)" onclick="default_mod()">수정</a>
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

  // 공지사항 수정
  function default_mod() {

    $.ajax({
      url      : "/<?=mapping('site')?>/site_mod_up",
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
        if(result.code == '-2') {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }


</script>
