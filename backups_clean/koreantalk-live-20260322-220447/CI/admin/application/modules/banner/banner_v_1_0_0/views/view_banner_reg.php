  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
		<div class="page-header">
			<h1> 배너 관리</h1>
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
								<th ><span class="text-danger">*</span> 배너명</th>
								<td colspan=3>
                    <input class="form-control" type="text" name="title" id="title" value="">
                </td>
							</tr>
							<!-- <tr>
								<th >배너 내용</th>
								<td  colspan=3>
                    <input class="form-control" name="contents" id="contents" placeholder="내용을 입력해주세요.">
                </td>
							</tr> -->
							<tr>
								<th >링크 URL</th>
								<td  colspan=3><input class="form-control" type="url"  name ="link_url" id ="link_url" placeholder="http://www.example.co.kr"></td>
							</tr>
              <tr>
								<th >노출상태</th>
								<td  colspan=3>
									<div class="toggle_set">
                    <select name="state" id="state" class="form-control" style="width:100px">
                      <option value="1" selected>활성화</option>
                      <option value="0">비활성화</option>
                    </select>
									</div>
								</td>
							</tr>
              <tr>
								<th > <span class="text-danger">*</span>
                   이미지[가로 * 세로]
                  <p class="text-info">[1920px * 680px]</p>
                  <input type="button" class="btn btn-xs btn-default" id="file1" value="파일업로드" onclick="file_upload_click('img','image','1','150');">
                </th>
                <td style="vertical-align:top;"  colspan=3>
                  <div class="view_img">
                    <ul class="img_hz" id="img"></ul>
                  </div>
								</td>
							</tr>
					
  					</tbody>
  				</table>
          <input type="hidden" name="banner_type" id="banner_type" value="<?=$banner_type?>">
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

    if($("#site_code").val() ==""){
      alert("사이트를 선택해 주세요");
      return;
    }

    $.ajax({
      url      : "/<?=mapping('banner')?>/banner_reg_in",
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


  // 뒤로가기
  function default_list(){   
    location.href ="/<?=mapping('banner')?>/";   
  }


</script>
