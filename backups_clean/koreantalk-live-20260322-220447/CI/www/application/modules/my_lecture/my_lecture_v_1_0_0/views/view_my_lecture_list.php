<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative"><?=lang('lang_10250','시청한 교육 영상')?></h2>
    <div id="list_ajax"></div>
  </div>
</div>



<input name="page_num" id="page_num" type="text" value="1" style="display:none">
<script>

  $(function(){
    setTimeout("default_list_get($('#page_num').val())", 10);    
  });

  //list_get
  function default_list_get(page_num){

    $('#page_num').val(page_num);

    var formData = {                     
        'page_num' : page_num,
    };

    $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('my_lecture')?>/lecture_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success  : function(result) {
        $('#list_ajax').html(result);
      }
    });
  }


  </script>
