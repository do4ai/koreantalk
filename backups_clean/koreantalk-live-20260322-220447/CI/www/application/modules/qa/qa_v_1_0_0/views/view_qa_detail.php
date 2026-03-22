<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative"><?=lang('lang_10282','1:1 상담')?></h2> 
    <hr>
    <div class="qa_detail_top">
       <b><?=$result->qa_title?></b>
      <p class=""><?=nl2br($result->qa_contents)?></p>
      <div class="date"><?=$result->ins_date?></div>
    </div>
    <? if($result->reply_yn=='Y'){ ?>
    <div class="qa_detail_bottom"> 
      <p class=""><?=nl2br($result->reply_contents)?></p>
      <div class="date"><?=$result->reply_date?></div>
    </div>
    <?}?>
    <div class="btn_half_wrap" style="width:414px;margin:40px auto;"> 
      <span class="btn_m btn_gray">
      <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>"><?=lang('lang_10283','목록')?></a>
      </span>
      <span class="btn_m btn_gray_line">
      <a href="javascript:void(0)" onClick="qa_del()"><?=lang('lang_10284','삭제')?></a>
      </span>
    </div>
  </div>
</div>



<input name="qa_idx" id="qa_idx" type="hidden" value="<?=$result->qa_idx?>">

<script type="text/javascript">

// 댓글 삭제
function qa_del(){

  if(confirm("<?=lang('lang_10285','해당 문의글을 삭제 하시겠습니까?\n삭제 하시면 해당 글은 다시 복구 할 수 없습니다.')?>")){

    $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_del",
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
   		  // 0:실패 1:성공
   		  if(result.code == 0) {
     			alert(result.code_msg);
   		  }else {
          alert(result.code_msg);
          location.href ='/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_list';
     		}
      }
    });
  }
}

</script>
