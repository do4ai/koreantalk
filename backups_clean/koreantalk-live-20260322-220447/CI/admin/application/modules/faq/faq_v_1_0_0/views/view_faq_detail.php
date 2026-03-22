<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>FAQ 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <!-- search : s -->
  	<div class="table-responsive">

      <form name="form_default" id="form_default" method="post">
        <input type="hidden" name="faq_idx" id="faq_idx" value="<?=$result->faq_idx?>">
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
                    <option value="<?=$row->site_code?>" <?=($result->site_code ==$row->site_code)?"selected":"";?>>
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
            </tr>
            <?}?>

            <tr>
              <th><span class="text-danger">*</span> 제목 </th>
              <td colspan="3">
                <input name="title" id="title" type="text" class="form-control" value="<?=$result->title?>">
              </td>
            </tr>
       
            <tr>
              <th><span class="text-danger">*</span> 내용 </th>
              <td class="table_left" colspan="3">
                <textarea class="input_default textarea_box" name="contents" id="contents" placeholder=""><?=$result->contents?></textarea>
              </td>
            </tr>
            <tr>
              <th>노출 상태</th>
              <td colspan=3>
                <div class="toggle_set">
                  <select name="display_yn" id="display_yn" class="form-control w_auto">
                    <option value="Y" <?php if($result->display_yn =="Y") echo 'selected'; ?>>활성화</option>
                    <option value="N" <?php if($result->display_yn =="N") echo 'selected'; ?>>비활성화</option>
                  </select>
                </div>
              </td>
            </tr>

          </tbody>
        </table>

        <div class="text-right mt15">
          <a class="btn btn-gray" href="javascript:void();" onclick="history.back(<?=$history_data?>)" >목록</a>
          <a class="btn btn-danger" href="javascript:void(0)" onClick="default_del('<?=$result->faq_idx?>')">삭제</a>
          <a class="btn btn-info" href="javascript:void(0)" onclick="default_mod();">수정</a>
        </div>

      </div>
    </div>
  </form>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->

<script>

  function default_list(){
    history.back(<?=$history_data?>);
  }

  function default_mod(){
    if($("#site_code").val() ==""){
      alert("사이트를 선택해 주세요");
      return;
    }

    $.ajax({
      url      : "/<?=mapping('faq')?>/faq_mod_up",
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
          default_list();
        }
      }
    });
  }

  function default_del(faq_idx){

    if(!confirm("정말 삭제하시겠습니까?")){
      return;
    }

    $.ajax({
      url      : "/<?=mapping('faq')?>/faq_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "faq_idx": faq_idx
      },
      success: function(result) {
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
          default_list();
        }
      }
    });

  }

</script>
