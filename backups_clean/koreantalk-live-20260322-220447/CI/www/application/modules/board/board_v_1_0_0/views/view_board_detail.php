<div class="body  row"> 
  <div class="inner_wrap"> 
    <table class="tbl_board_detail">
      <colgroup>
      <col width='*'>
      <col width='160px'>
      </colgroup>
      <tr>
        <th colspan="2"><div class="sub_title"> <?=$result->title?></div></th>
      </tr>
      <tr>
        <th>
          <ul class="ul_board_info">
            <? if($result->notice_yn=='N'){?>
              <li>
                <img src="/images/i_comment.png" alt="">
                <?=number_format($result->reply_cnt)?>
              </li>
            <? }?>
            <li>
              <img src="/images/i_view.png" alt="">
			         <?=number_format($result->view_cnt)?>
            </li>
            <li>
            <?=$result->ins_date?>
            </li>
            <li>
            <div class="point_color"><?=$result->member_name?></div>
            </li>
          </ul>
        </th>
        <td>
          <div class="wrap_btn">
		    <?
			if($result->member_idx ==$this->member_idx && $result->notice_yn=='N'){
			?>
            <div class="btn_full_thin btn_gray_line">
            <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/board_mod?board_idx=<?=$result->board_idx?>"><?=lang('lang_10004','수정')?></a>
            </div>
            <div class="btn_full_thin btn_gray_line">
            <a href="javascript:void(0)" onclick="board_del()"><?=lang('lang_10005','삭제')?></a>
            </div>
			<?}?>
          </div>
        </td>
      </tr>
    </table>
    <div class="wrap_board_detail">
	  <?
	   if($result->board_img !=""){
          $img_arr = explode(",",$result->board_img);
		  foreach($img_arr as $img){

	  ?>
			 <img src="<?=$img?>" class="img_block">
          <?}?>
      <?}?>
	  <p>

      <style>
      .terms span{color:inherit;font-size: inherit}
      .terms a{color:inherit;font-size: inherit}
      .terms p{padding: 0; margin: 0;}
      .terms h1{color:inherit;font-family: inherit; font-weight: inherit;}
      .terms h2, .terms h3, .terms h4, .terms h5, .terms h6{color:inherit;font-family: inherit; font-weight: inherit;}
      .terms body, .terms div, .terms dl, .terms dt, .terms dd, .terms ul, .terms ol, .terms li, .terms h1, .terms h2, .terms h3, .terms h4, .terms h5, .terms h6, .terms pre, .terms code,
      .terms form, .terms fieldset, .terms legend, .terms textarea, .terms p, .terms blockquote, .terms th, .terms td, .terms input, .terms select, .terms textarea, .terms button{padding:revert;}
      .terms dl, .terms ul, .terms ol, .terms menu, .terms li{list-style: revert;}
      .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{border:1px solid #ddd; vertical-align: middle;}
      .terms .table > thead > tr > th, .terms .table > tbody > tr > th,
      .terms .table > tfoot > tr > th, .terms .table > thead > tr > td, .terms .table > tbody > tr > td, .terms .table > tfoot > tr > td{padding: 8px 12px;line-height: 1.5;}
      .terms iframe, .terms img{max-width: 100%}
      </style>
      <div class="terms">

          <?=$result->contents?>
   
      </div>   

    </p>
    </div>
   
    <div class="wrap_comment">
  
        <div class="wrap_input">
          <? if($result->notice_yn=='N'){ ?>
            <h2 id="list_cnt"><?=lang('lang_10006','댓글')?> <?=number_format($result->reply_cnt)?> </h2>
            <div class="btn_send" onclick="modal_open('send');set_title();"><?=lang('lang_10007','댓글 등록')?></div>
          <? } ?>
        </div>
    
      <div class="wrap_result" >
        <div  class="wrap_result" id="list_ajax"></div>  
        <div class="btn_m btn_gray_line" id="load" style="display:<?=($result->reply_cnt>10)?"block":"none";?>"><a href=""><?=lang('lang_10008','댓글 더보기')?> <img src="/images/icon_arrow_down.png" alt=""></a></div>
        <div class="btn_m btn_gray"><a href="javascript:void(0)" onClick="default_list();"><?=lang('lang_10009','목록')?></a></div>
      </div>
    </div>
  
    <div class="modal modal_send">
      <h2 id="_reply_title"><?=lang('lang_10010','댓글 등록')?></h2>
      <textarea name="cmt_input" id="cmt_input" cols="" rows=""></textarea>
      <div class="row f_right">
        <div class="btn_m btn_gray">
          <a href="javascript:void(0)"  onclick="modal_close('send')"><?=lang('lang_10011','취소')?></a>
        </div>
        <div class="btn_m btn_point">
          <a href="javascript:void(0)"  onclick="board_comment_reg_in()" id="_reply_btn"><?=lang('lang_10012','등록')?></a>
        </div>
      </div>
    </div>
    <div class="md_overlay md_overlay_send" onclick="modal_close('send')"></div>


    
    
  </div>
</div>
 <!-- <div class="back"></div> -->
<script>

</script> 

<style> li.my_review_li { display:none; }</style>
<script type="text/javascript">
$(function(){
  $(".my_review_li").slice(0, 10).show(); 
  $("#load").click(function(e){
    e.preventDefault();
    $(".my_review_li:hidden").slice(0, 10).show(); 
    if($(".my_review_li:hidden").length == 0){ 
      $("#load").hide();
    }
  });
});
</script>

<input type="hidden" name="board_idx" id="board_idx" value="<?=$result->board_idx?>">
<input type="hidden" name="owner_idx" id="owner_idx" value="<?=$result->member_idx?>">
<input type="hidden" name="board_reply_idx" id="board_reply_idx" value="">
<input type="hidden" name="report_part" id="report_part" value="0">
<input type="hidden" name="total_block" id="total_block" value="1">

<script type="text/javascript">

$(function(){
	setTimeout("default_list_get('1')", 10);
});


var board_idx="<?=$result->board_idx?>";
var category="<?=$result->category?>";
var return_url="<?=$this->current_lang?>/<?=mapping('board')?>/board_detail&board_idx="+board_idx;


//댓글키 세팅
function default_list(){
  location.href ='/<?=$this->current_lang?>/<?=mapping('board')?>/?category='+category;
}



function default_list_get(page_num){
 
  $("#next_page").remove();

	var formData = {
		'page_num' : page_num,
		'board_idx' : $("#board_idx").val(),
		'owner_idx' : $("#owner_idx").val(),
	};

	$.ajax({
		url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/board_reply_list_get",
		type     : "POST",
		dataType : "html",
		async    : true,
		data     : formData,
		success: function(result) {
     if(page_num =="1"){
      $("#list_ajax").html(result);
     }else{ 
      $("#list_ajax").append(result);
     } 	

		}
	});
}

//삭제
function board_del(){
	if(COM_login_check(return_url)==false){ return;}

  if(!confirm('<?=lang('lang_10325','삭제 하시겠습니까?')?>')){
    return false;
  }

  var formData = {
		'board_idx' : $('#board_idx').val(),
		'reply_comment' : $('#reply_comment').val(),
		'board_reply_idx' : $('#board_reply_idx').val(),
  }

  $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/board_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success : function(result){
        if(result.code == '-1'){
     			alert(result.code_msg);
     			$("#"+result.focus_id).focus();
     			return;
   		  }
   		  // 0:실패 1:성공
   		  if(result.code == 0) {
     			alert(result.code_msg);
   		  }else {
          alert(result.code_msg);
          location.href ='/<?=$this->current_lang?>/<?=mapping('board')?>?category='+category;
     		}
      }
    });
}

//댓글키 세팅
function set_board_reply_idx(board_reply_idx,reply_comment){
	$('#board_reply_idx').val(board_reply_idx);
  $('#cmt_input').val(reply_comment);
	$('#_reply_title').html("<?=lang('lang_10326','댓글 수정')?>");
	$('#_reply_btn').html("<?=lang('lang_10327','수정')?>");

	 modal_open('send');
}


//댓글키 세팅
function set_title(){	
	$('#_reply_title').html("<?=lang('lang_10328','댓글 등록')?>");
	$('#_reply_btn').html("<?=lang('lang_10329','등록')?>");
}


//댓글등록
function board_comment_reg_in(){
  
	if(COM_login_check(return_url)==false){ return;}
  let board_reply_idx =$('#board_reply_idx').val();
  var formData = {
		'board_idx' : $('#board_idx').val(),
		'reply_comment' : $('#cmt_input').val(),
		'board_reply_idx' :board_reply_idx ,
  }

  if($('#board_reply_idx').val() ==""){
    target_url ="board_comment_reg_in";
  }else{
    target_url ="reply_comment_mod_up";
  }

  $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/"+target_url,
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success : function(result){
        if(result.code == '-1'){
     			alert(result.code_msg);
     			$("#"+result.focus_id).focus();
     			return;
   		  }
   		  // 0:실패 1:성공
   		  if(result.code == 0) {
     			alert(result.code_msg);
   		  }else {
					$('#cmt_input').val('');
          $('#board_reply_idx').val('');
          
          modal_close('send');
          $('.ul_mod').hide();

          if(board_reply_idx ==""){
            default_list_get('1');          
          }else{
            $("#reply_contents_"+board_reply_idx).html(result.reply_comment);     
          }
     		}
      }
    });
}


//댓글삭제
function reply_comment_del(board_reply_idx){
 // alert(board_reply_idx);

	if(COM_login_check(return_url)==false){ return;}
  
  if(!confirm('<?=lang('lang_10330','삭제 하시겠습니까?')?>')){
    return false;
  }
  //let board_reply_idx = $('#board_reply_idx').val();

  var formData = {
		'board_reply_idx' : board_reply_idx,
  }

  $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/reply_comment_del",
      type     : 'POST',
      dataType : 'json',
      async    : true,
      data     : formData,
      success : function(result){
        if(result.code == '-1'){
     			alert(result.code_msg);
     			$("#"+result.focus_id).focus();
     			return;
   		  }
   		  // 0:실패 1:성공
   		  if(result.code == 0) {
     			alert(result.code_msg);
   		  }else {
					modal_close('send');

          $("#reply_"+board_reply_idx).html("<div ><p><?=lang('lang_10331','삭제된 댓글 입니다.')?></p></div>");
       
    
     		}
      }
    });
}



</script>
