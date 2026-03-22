<div class="body  row">
  <div class="inner_wrap">
    <div class="sub_title"><?=lang('lang_10258','전자책 구매내역')?></div> 
    <div class="wrap_search">
      <input type="text" placeholder='<?=lang('lang_10259','책 제목, 설명을 입력해 주세요.')?>' id="s_text" name="s_text">
      <img src="/images/i_search.png" alt="" class="i_search" onClick="default_list_get('1')">
    </div>
     <div id="list_ajax"> </div>
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
      url      : "/<?=$this->current_lang?>/<?=mapping('my_order')?>/order_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : {        
        "s_text":$('#s_text').val(),     
        "page_num":page_num,
      },
      success  : function(result) {
        $('#list_ajax').html(result);
      }
    });
  }

  //상세보기
  function do_view(product_idx){
   location.href ='/<?=$this->current_lang?>/<?=mapping('electron_book')?>/electron_book_detail?electron_book_idx='+product_idx;
  
  }
  //PDF 다운로드
  function file_download(product_auth_code){   
    location.href ='/<?=$this->current_lang?>/<?=mapping('my_order')?>/file_download?product_auth_code='+product_auth_code;
    $('#li_'+product_auth_code).removeClass('btn_point').addClass('btn_grad');
  }   


  </script>

