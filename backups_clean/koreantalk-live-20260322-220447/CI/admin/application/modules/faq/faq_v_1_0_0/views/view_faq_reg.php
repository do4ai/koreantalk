<!-- container-fluid : s -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="page-header">
    <h1>FAQ 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">

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
              <th><span class="text-danger">*</span> 제목 </th>
              <td colspan=3>
                <input name="title" id="title" type="text" class="form-control">
              </td>
            </tr>           
            <tr>
              <th><span class="text-danger">*</span> 내용 </th>
              <td colspan=3>
                <textarea class="input_default textarea_box" name="contents" class="textarea_box" id = "contents" placeholder="내용"></textarea>
              </td>
            </tr>
            <tr>
              <th >노출상태</th>
              <td  colspan=3>
                <div class="toggle_set">
                  <select name="display_yn" id="display_yn" class="form-control" style="width:100px">
                    <option value="Y" selected>활성화</option>
                    <option value="N">비활성화</option>
                  </select>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </form>

      <div class="text-right mt15">
        <a class="btn btn-gray" href="/<?=mapping('faq')?>">취소</a>
        <a class="btn btn-success" href="javascript:void(0)" onclick="default_reg();">등록</a>
      </div>
    </div>
  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->

<script>

  function default_reg(){
    if($("#site_code").val() ==""){
      alert("사이트를 선택해 주세요");
      return;
    }
  
    $.ajax({
  		url      : "/<?=mapping('faq')?>/faq_reg_in",
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
          location.href ='/<?=mapping('faq')?>/faq_list';
        }
  		}
  	});
  }

</script>
