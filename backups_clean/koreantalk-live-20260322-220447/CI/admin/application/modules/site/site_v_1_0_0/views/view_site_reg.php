<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>사이트 관리</h1>
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
                  <input name="site_code" id="site_code" maxlength=10 type="text"   class="form-control" style="width:500px">
                </td>
              </tr>
               <tr>
                <th> <span class="text-danger">*</span> 사이트 명</th>
                <td colspan="3">
                  <input name="site_name" id="site_name"  type="text"   class="form-control" style="width:500px">
                </td>
              </tr>

           
           

            </tbody>
          </table>
          
        </form>
      </section>

      <div class="row">
        <div class="col-lg-12 text-right">
          <a href="/<?=mapping('site')?>" class="btn btn-gray">취소</a>
          <a href="javascript:void(0)" onclick="default_reg();" class="btn btn-success">등록</a>
        </div>
      </div>

    </div>
  </div>
  <!-- body : e -->

</div>
<script>
  // 공지사항 등록
  function default_reg(){

    $.ajax({
      url      : "/<?=mapping('site')?>/site_reg_in",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : $("#form_default").serialize(),
      success: function(result){
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
          location.href ='/<?=mapping('site')?>/site_list';
        }
      }
    });
  }

</script>
