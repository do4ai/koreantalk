<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>공지사항 관리</h1>
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
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
            </tr>
            <?}?>
              <tr>
                <th> <span class="text-danger">*</span> 제목</th>
                <td colspan="3">
                  <input name="title" id="title" type="text" class="form-control" >
                </td>
              </tr>
              <tr>
                <th>
                  사진[가로]
                  <p class="text-info">[670px ]</p>
                  <input type="button" class="btn btn-xs btn-default" value="파일업로드" onclick="file_upload_click('img','image','1','150');" style="margin-bottom:10px">
                </th>
                <td colspan="3">
                  <div class="view_img mg_btm_20">
                    <ul class="img_hz" id="img"></ul>
                  </div>
                </td>
              </tr>
              <tr>
                <th><span class="text-danger">*</span> 내용</th>
                <td colspan="3">
                  <textarea name="contents" id="contents" style="width:100%; height:100px;" placeholder="" class="input_default"></textarea>
                </td>
              </tr>
              <tr>
                <th>상태</th>
                <td colspan="3">
                  <select id="display_yn" name="display_yn" class="form-control w_auto">
                    <option value="N">비활성화</option>
                    <option value="Y">활성화</option>
                  </select>
                </td>
              </tr>
            
              
            </tbody>
          </table>
        </form>
      </section>

      <div class="row">
        <div class="col-lg-12 text-right">
          <a href="/<?=mapping('notice')?>" class="btn btn-gray">취소</a>
          <a href="javascript:void(0)" onclick="default_reg();" class="btn btn-success">등록</a>
        </div>
      </div>

    </div>
  </div>
  <!-- body : e -->

</div>
<script>
  function default_reg(){
    if($("#site_code").val() ==""){
      alert("사이트를 선택해 주세요");
      return;
    }

    $.ajax({
      url      : "/<?=mapping('notice')?>/notice_reg_in",
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
        if(result.code == -2) {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          location.href ='/<?=mapping('notice')?>/notice_list';
        }
      }
    });
  }

</script>
