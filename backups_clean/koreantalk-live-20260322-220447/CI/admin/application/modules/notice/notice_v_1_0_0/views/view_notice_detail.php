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
                    <option value="<?=$row->site_code?>" <?=($result->site_code ==$row->site_code)?"selected":"";?>>
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
            </tr>
            <?}?>
            
              <tr>
                <th><span class="text-danger">*</span> 제목</th>
                <td colspan="3">
                  <input class="form-control" type="text" name="title" id="title" value="<?=$result->title?>">
                </td>
              </tr>

              <tr>
                <th><span class="text-danger">*</span> 내용</th>
                <td colspan="3">
                  <textarea name="contents" id="contents" style="width:100%; height:100px;"placeholder="" class="input_default"><?=$result->contents?></textarea>
                </td>
              </tr>
              <tr>
                <th>
                  사진[가로]
                  <p class="text-info">[670px]</p>
                  <input type="button" class="btn btn-xs btn-default" value="파일업로드" onclick="file_upload_click('img','image','1','150');" style="margin-bottom:10px">
                </th>
                <td colspan="3">
                  <div>
                    <ul class="img_hz" id="img">
                      <?php if($result->img_path != ""){ ?>
                        <li id="id_file_img_0" style="display:inline-block;">
                          <img src="/images/btn_del.gif" style="width:15px "onclick="file_upload_remove('img_0')"/><br>
                          <img src="<?=$result->img_path?>" style="width:150px">
                          <input type="hidden" name="img"  value="<?=$result->img_path?>"/>
                        </li>
                      <?php } ?>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr>
                <th>상태</th>
        				<td colspan="3">
                  <select name="display_yn" class="form-control w_auto">
                    <option value="N" <?php if($result->display_yn =="N"){ echo "selected";}?>>비활성화</option>
                    <option value="Y" <?php if($result->display_yn =="Y"){ echo "selected";}?>>활성화</option>
                  </select>
                </td>
              </tr>
    
            
            </tbody>
          </table>
          <input type="hidden" name="notice_idx" id="notice_idx" value="<?=$result->notice_idx?>">
        </form>
      </section>

      <div class="text-right" style="float:right;">
        <a class="btn btn-gray" href="javascript:void(0)" onclick="default_list()">목록</a>
        <a class="btn btn-danger" href="javascript:void(0)" onclick="notice_del('<?=$result->notice_idx?>')">삭제</a>
        <a class="btn btn-success" href="javascript:void(0)" onclick="default_mod('<?=$result->notice_idx?>')">수정</a>
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
    if($("#site_code").val() ==""){
      alert("사이트를 선택해 주세요");
      return;
    }

    $.ajax({
      url      : "/<?=mapping('notice')?>/notice_mod_up",
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
        if(result.code == "-2") {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }

  // 공지사항 삭제 
  function notice_del(notice_idx){

    if(!confirm("공지사항을 삭제하시겠습니까?")){
      return;
    }

    $.ajax({
      url      : "/<?=mapping('notice')?>/notice_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : {
        "notice_idx" : notice_idx
      },
      success: function(result) {
        if(result.code == '-1'){
          alert(result.code_msg);
          $("#"+result.focus_id).focus();
          return;
        }
        // -2:실패 1:성공
        if(result.code == "-2") {
          alert(result.code_msg);
        } else {
          alert(result.code_msg);
          default_list();
        }
      }
    });
  }
</script>
