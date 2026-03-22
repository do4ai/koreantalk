  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
      <h1><span class="text-info">회원 정보</h1>
    </div>

    <!-- body : s -->
    <div class="bg_wh mt20">
    	<div class="table-responsive">
        <section>
          <!-- top -->
          <div class="row table_title">
            <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;기본정보</div>
            <p class="col-lg-6 text-right"></p>
          </div>
          <!-- top  -->
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
                <th>아이디</th>
                <td ><?=$result->member_id?></td>
                <th>이름</th>
                <td > <?=$result->member_name?> </td>
              </tr>
              <tr>
                <th>휴대폰 번호</th>
                <td ><?=$result->member_phone?></td>               
                <th>국적</th>
                <td ><?=$result->site_name?></td>
              </tr>
              <tr>
                <th>외국어 한글 이름</th>
                 <td ><?=$result->member_nickname?></td>
                
                <th>가입일</th>
                <td ><?=$this->global_function->date_YmdHi_dot($result->ins_date);?></td>
              </tr>
              
              <tr>
             
                <th>회원 상태</th>
                <td colspan=3>
                    <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="N" <?=($result->del_yn=='N')?"checked":"";?> > 이용중</label>
                    <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="P" <?=($result->del_yn=='P')?"checked":"";?> > 이용정지</label>
                    <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="Y" <?=($result->del_yn=='Y')?"checked":"";?>  disabled> 탈퇴</label>
                </td>
              </tr>
              <tr>
                <th>탈퇴일</th>
                <td ><?=$result->member_leave_date?></td>
                <th>탈퇴 사유</th>
                <td ><?=$result->member_leave_reason?></td>
              </tr>
            </tbody>
        	</table>


         
        </section>
        <input type="hidden" name="member_idx" id="member_idx" value="<?=$result->member_idx?>">
        </form>

        <div class="row">
          <div class="col-lg-12 text-right">
            <a href="javascript:default_list()" class="btn btn-gray">목록</a>
            <?if($result->del_yn!='Y'){?>
            <a href="javascript:void(0)" onclick="default_mod()" class="btn btn-info">수정</a>
            <?}?>
          </div>
        </div>
    	</div>
    </div>
    <!-- body : e -->
  </div>
  <!-- container-fluid : e -->

  <script>

  //프로필 확대보기
  $(document).ready(function() {
    $("a[name='single_image']").fancybox();
  });

  // 리스트 이동
  function default_list(){
    history.back('<?=$history_data?>');
  }


// 회원 정보 수정
  function default_mod() {

    $.ajax({
      url      : "/<?=mapping('member')?>/member_mod_up",
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
          location.href="/<?=mapping('member')?>";
        }
      }
    });
  }

  </script>
