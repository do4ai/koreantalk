<div class="body  row"> 
  <div class="inner_wrap">
    <table class="tbl_lecture mt100">
      <colgroup>
        <col width='410px'>
        <col width='*'>
      </colgroup>
      <tr>
        <th>
          <div class="img_box">
          <img src="<?=$result->img_path?>" alt="">
          </div>
        </th>
        <td>
          <h2><?=$result->lecture_name?></h2>
          <p class="mt40"><?=nl2br($result->contents)?></p>
        </td>
      </tr>
    </table>
    <h2 class="mt100"><?=lang('lang_10175','커리큘럼')?></h2>

    <div class="tab">
      <ul class="tab_toggle_menu">
        <?php    
        foreach($result_list as $row){
        ?>
        <li class="<?=($lecture_category_idx ==$row->lecture_category_idx)?"active":"";?>">
          <a href="javascript:void(0)" onClick="default_list_get('<?=$row->lecture_category_idx?>')"><?=$row->category_name?> </a>
        </li> 
        <?
        }
        ?>
        <!-- <li>
          <a href="javascript:;">듣기</a>
        </li> 
        <li>
          <a href="javascript:;">읽고 듣기 </a>
        </li>  -->
      </ul>
      <div class="">
        <!-- 탭 영역 1 : s -->
        <div> 
          <ul class="ul_lecture" id="list_ajax">
            
          </ul>
          <?
          if(!empty($ebook_info)){
          ?>
          <div class="box_detail">
            <h2><?=lang('lang_10176','같이 봐야 하는 책')?></h2>
            <table class="tbl_lecture_box mt30">
              <colgroup>
                <col width='210px'>
                <col width='*'>
              </colgroup>
              <tr>
                <th>
                  <div class="img_box">
                    <img src="<?=$ebook_info->product_img_path?>" alt="">
                  </div>
                </th>
                <td>
                  <h5><?=$ebook_info->product_name?></h5>
                  <div class="font_gray_6"><?=lang('lang_10177','저자')?> ‧ <?=$ebook_info->author?> </div>
                  <b class="fs_16"><?=number_format($ebook_info->product_price)?><?=lang('lang_10178','원')?></b>
                  <div class="font_gray_6"><?=$ebook_info->product_contents?></div>
                  <div class="btn_m btn_point mt40">
                    <a href="/<?=$this->current_lang?>/<?=mapping('electron_book')?>/electron_book_detail?electron_book_idx=<?=$ebook_info->electron_book_idx?>"><?=lang('lang_10179','상세보기')?></a>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <?}?>
          
        </div>
      </div>
    </div>  
  </div>
</div>

<div class="modal modal_youtube">
  <img src="/images/icon_close.png" class="md_close" onclick="modal_close('youtube')">
  <div class="iframe_wrap" >
  <iframe src="" id="youtub_play" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
  </div>
</div>
<div class="md_overlay md_overlay_youtube" onclick="modal_close('youtube')"></div>





<script>
  var return_url="<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail&lecture_idx=<?=$lecture_idx?>&lecture_category_idx=<?=$lecture_category_idx?>";
  var lecture_idx ="<?=$lecture_idx?>";
  var lecture_category_idx ="<?=$lecture_category_idx?>";
  //내가본 동영상
  function member_watched_movie_reg_in(lecture_movie_idx,youtube_id){
    if(COM_login_check(return_url)==false){ return;}

    var formData = {
      'lecture_movie_idx' : lecture_movie_idx,
    }

    $.ajax({
        url      : "/<?=$this->current_lang?>/<?=mapping('lecture')?>/member_watched_movie_reg_in",
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
            modal_open('youtube');
            document.getElementById("youtub_play").src = "https://www.youtube.com/embed/"+youtube_id;
           // movie_view(youtube_id);
          }
        }
      });
  }

  //list_get
  function default_list_get(lecture_category_idx){
    $('#list_ajax').html("");
    var formData = {        
      'lecture_category_idx' : lecture_category_idx,                
    };

    $.ajax({
      url      : "/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_movie_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success  : function(result) {
       // alert(result);
        
        $('#list_ajax').html(result);
      }
    });
  }


  $(function(){
    setTimeout("default_list_get('<?=$lecture_category_idx?>')", 10);    
  });

</script>




