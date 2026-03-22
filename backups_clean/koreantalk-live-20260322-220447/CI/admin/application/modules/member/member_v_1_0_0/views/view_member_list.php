  <!-- container-fluid : s -->
  <div class="container-fluid">
  	<!-- Page Heading -->
  	<div class="page-header">
  		<h1>회원 관리</h1>

  	</div>

    <!-- body : s -->
    <div class="bg_wh mt20">
      <!-- search : s -->
    	<div class="table-responsive">
        <form name="form_default" id="form_default">
      		<table class="search_table">
          
            <colgroup>
              <col style="width:15%">
              <col style="width:35%">
              <col style="width:15%">
              <col style="width:35%">
            </colgroup>
      			<tbody>
      				<tr>
                <th >아이디</th>
      					<td>
                  <input class="form-control w_auto" name="member_id" id="member_id">
                </td>
                <th >이름</th>
                <td>
                 <input class="form-control w_auto" name="member_name" id="member_name">
                </td>
              </tr>
      				<tr>
                <th>국적</th>
                <td>
                  <input class="form-control w_auto" name="site_name" id="site_name">
                </td>
                <th>외국어 한글이름</th>
                <td>
                  <input class="form-control w_auto" name="member_nickname" id="member_nickname">
                </td>
              
              </tr>
              <tr>
        
                 <th>휴대폰 번호</th>
                <td>
                  <input class="form-control w_auto" name="member_phone" id="member_phone">
                </td>
                <th >가입일</th>
                <td>
                  <input class="form-control datepicker" name="s_date" id="s_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input class="form-control datepicker" name="e_date" id="e_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>            
              </tr>
              <tr>
                <th>회원 상태</th>
                <td colspan="3">
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="" checked> 전체</label>
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="N"> 이용중</label>
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="P"> 이용정지</label>
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_yn" value="Y"> 탈퇴</label>
                </td>
              </tr>
      			</tbody>
      		</table>
        </form>

    		<div class="text-center mt20">
          <a href="javascript:void(0)" class="btn btn-success" onclick="default_list_get('1');">검색</a>
    		</div>

    	</div>
      <!-- search : e -->

      <div class="table-responsive">
        <!-- list_get : s -->
        <div id="list_ajax">
          <!--리스트-->
        </div>
        <!-- list_get : e -->
      </div>
    </div>
    <!-- body : e -->
  </div>
  <!-- container-fluid : e -->

<script language="javascript">
  $(document).ready(function(){
      setTimeout("default_list_get($('#page_num').val())", 100);
  });

  var default_list_get = function(page_num) {

    $('#page_num').val(page_num);

  	var form_data = {
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'member_id' : $('#member_id').val(),
      'member_name' : $('#member_name').val(),
      'member_phone' : $('#member_phone').val(),
      'member_nickname' : $('#member_nickname').val(),
      'site_name' : $('#site_name').val(),
      'del_yn' :  $("input[name='del_yn']:checked").val(),
      'history_data': window.history.length,
      'page_num' : page_num
    };

  	$.ajax({
  		url: "/<?=mapping('member')?>/member_list_get",
  		type: 'POST',
  		dataType : 'html',
  		async: true,
      data: form_data,
      beforeSend: function(){
       $('.loader').show();
      },
      complete: function(){
       $('.loader').hide();
      },
      success: function(dom){
        $('#list_ajax').html(dom);
       // document.location.hash = "#" + page_num;
      },
      error: function(request,status,error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
  	});
  }


  var do_excel_down = function() {
    document.form_default.action ="/<?=mapping('member')?>/member_list_excel";
    document.form_default.submit();
  }

</script>
