<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative">
			<? 
				if($this->current_lang=='kr'){
					echo $board_category_detail->category_name;
				}else{
					echo $board_category_detail->category_name_eng;
				}	
		  ?>
      
      <div class="btn_full_thin btn_point_line btn_board_reg">
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/board_reg?category=<?=$category?>"><?=lang("lang_10001","글쓰기")?></a>
      </div>
    </h2>
    <div class="wrap_search">
      <input type="text" placeholder='<?=lang("lang_10002","제목, 내용을 입력해 주세요.")?>' name="s_text" id="s_text">
      <img src="/images/i_search.png" alt="" class="i_search" onClick="default_list_get('1')"> 
    </div>
    

    <div class="no_data"  id="no_data" style="display:none">
      <p><?=lang("lang_10003","조회된 글이 없습니다.")?></p>
    </div>
    <div id="list_ajax"></div>

  </div>
</div>

<input type="text" name="page_num" id="page_num" value="1" style="display:none">
<input type="text" name="category" id="category" value="<?=$category?>" style="display:none">
<script type="text/javascript">
$(function(){
	setTimeout("default_list_get('1')", 10);
});

function default_list_get(page_num){

	$('#page_num').val(page_num);

	var formData = {
		'category' : $('#category').val(),
		's_text' : $('#s_text').val(),
		'orderby' : $('#orderby').val(),
		'page_num' : page_num,
	};

  $.ajax({
	  url      : "/<?=$this->current_lang?>/<?=mapping('board')?>/board_list_get",
		type     : "POST",
		dataType : "html",
		async    : true,
		data     : formData,
		success: function(result) {
		 $("#list_ajax").html(result);
		}
	});

}



</script>
