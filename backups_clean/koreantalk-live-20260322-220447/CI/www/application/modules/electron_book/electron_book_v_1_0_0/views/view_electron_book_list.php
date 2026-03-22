<div class="body  row">
  <div class="inner_wrap">
    <div class="sub_title"><?=lang('lang_10087','서점')?></div> 
    <div class="wrap_search">
      <input type="text" placeholder='<?=lang('lang_10088','책 제목, 설명을 입력해 주세요.')?>' id="s_text" name="s_text">
      <img src="/images/i_search.png" alt="" class="i_search" onClick="default_list_get('1')">
    </div>
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
        's_text' : $('#s_text').val(),        
        'page_num' : page_num,
    };

    $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('electron_book')?>/electron_book_list_get",
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
