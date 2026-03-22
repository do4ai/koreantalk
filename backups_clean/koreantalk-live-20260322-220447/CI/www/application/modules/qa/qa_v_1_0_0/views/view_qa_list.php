<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative"><?=lang('lang_10293','1:1 상담')?>
      <div class="btn_full_thin btn_point_line btn_board_reg btn_consultation_reg">
      <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_reg"><?=lang('lang_10294','등록')?></a>
      </div>
    </h2>
    
    <div id="list_ajax"></div>

   
  </div>
</div>


<input name="page_num" id="page_num" type="hidden" value="1">
<script>

  $(function(){
    default_list_get($('#page_num').val());
  });

  //list_get
  function default_list_get(page_num){

     $('#page_num').val(page_num);

    $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : {
               "page_num":page_num
      },
      success  : function(result) {
        $('#list_ajax').html(result);
      }
    });
  }


  </script>
