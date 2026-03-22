<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1> 1:1 문의 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
  	<div class="table-responsive">

      <!-- top -->
      <div class="row table_title">
        <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;회원질문</div>
      </div>

      <!-- top  -->
      <section>
      	<table class="table table-bordered td_left">
          <colgroup>
        	<col style="width:15%">
        	<col style="width:35%">
        	<col style="width:15%">
        	<col style="width:35%">
          </colgroup>
      		<tbody>
      			<tr>
              <th>회원명</th>
              <td>
                <?=$result->member_name?>
              </td>
              <th>등록일</th>
              <td>
                <?=$this->global_function->date_YmdHi_hyphen($result->ins_date)?>
              </td>
            </tr>
            <tr>
              <th>아이디</th>
              <td colspan=3>
                <?=$result->member_id?>
              </td>
              <!-- <th>구분</th>
              <td>
                <?php
                  if($result->qa_category == "0"){
                    echo '앱 사용';
                  }else if($result->qa_category == "1"){
                    echo '환불';
                  }else if($result->qa_category == "2"){
                    echo '구매취소';
                  }else if($result->qa_category == "3"){
                    echo '정보오류';
                  }
                ?>
              </td> -->
            </tr>


            <tr>
              <th>제목</th>
              <td colspan="3">
                <?=$result->qa_title?>
              </td>
            </tr>
            <tr>
              <th>질문내용</th>
              <td colspan="3">
                <div class="board_box">
                  <?=nl2br($result->qa_contents)?>
                </div>
              </td>
            </tr>
          </tbody>
      	</table>
      </section>

      <!-- top -->
      <div class="row table_title">
        <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;답변 등록</div>
      </div>

      <!-- top  -->
      <section>
      	<table class="table table-bordered td_left">
          <colgroup>
        	<col style="width:15%">
        	<col style="width:35%">
        	<col style="width:15%">
        	<col style="width:35%">
          </colgroup>
      		<tbody>
            <tr>
              <th>답변내용</th>
              <td colspan=3>
                <textarea name="reply_contents" style="width:100%; height:200px;" id="reply_contents" placeholder="내용" class="input_default"><?=$result->reply_contents?></textarea>
              </td>
            </tr>
          </tbody>
      	</table>
      </section>

      <div class=" text-right" style="float:right; width:50%;">
        <a href="/<?=mapping('qa')?>/qa_list" class="btn btn-gray">목록</a>
        <a href="javascript:void(0)" onclick="reply_del()" class="btn btn-danger">답글 삭제</a>
        <a href="javascript:void(0)" onclick="reply_reg();" class="btn btn-success" style="vertical-align: middle">답글 등록</a>
      </div>

  	</div>
  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->

<input name="qa_idx" id="qa_idx" type="hidden" value="<?=$result->qa_idx?>">
<input name="member_idx" id="member_idx" type="hidden" value="<?=$result->member_idx?>">
<script>
  // 목록
  function default_list(){
    history.back(<?=$history_data?>);
  }

  // 답변 등록
  function reply_reg(){

    var formData = {
      'qa_idx' : $('#qa_idx').val(),
      'member_idx' : $('#member_idx').val(),
      'reply_contents' : $('#reply_contents').val()
    }

    $.ajax({
      url : "/<?=mapping('qa')?>/qa_reply_reg_in",
      type : 'POST',
      dataType : 'json',
      async : true,
      data : formData,
      success : function(result){
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

  // 댓글 삭제
  function reply_del(){

    if(confirm("답변을 삭제하시겠습니까?")){

      $.ajax({
        url      : "/<?=mapping('qa')?>/qa_reply_del",
        type     : 'POST',
        dataType : 'json',
        async    : true,
        data     : {
          "qa_idx" : $('#qa_idx').val()
        },
        success : function(result){
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
            location.reload();            
          }
          
        }
      });
    }
  }

</script>
