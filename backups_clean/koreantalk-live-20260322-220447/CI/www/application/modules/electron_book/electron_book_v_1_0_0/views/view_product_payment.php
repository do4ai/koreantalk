<div class="body row"> 
  <div class="inner_wrap">
    <div class="w850">
      <div class="sub_title"><?=lang('lang_10097','결제 하기')?></div>
      <h2><?=lang('lang_10098','주문 내역')?></h2>
      <div class="flex_payment">
        <div>
          <table>
            <colgroup>
              <col width='115px'>
              <col width='*'>
            </colgroup>
            <tr>
              <th>
                <div class="img_box">
                  <img src="<?=$result->product_img_path?>" alt="">
                </div></th>
                <td>
                <h5><?=$result->product_name?></h5>
                </td>
            </tr>
          </table>
        </div>
        <div> 
          <h5><?=lang('lang_10099','이용약관동의')?></h5> 
          <div class="all_checkbox row mt10">
            <ul>
              <li>
                <input type="checkbox" name="checkAll" id="checkAll">
                <label for="checkAll">
                  <span></span>
                    <?=lang('lang_10100','전체 약관 동의')?>
                </label>
              </li>
              <?php foreach ($terms_list as $row){ ?>
              <li>
                <input type="checkbox" name="checkOne" id="checkOne_<?=$row->type ?>" value="Y">
                <label for="checkOne_<?=$row->type ?>">
                  <span></span>
                  <p><?=$row->title ?> <i> <?=lang('lang_10101','(필수)')?></i></p>
                </label>
                <span><a href="javascript:void(0)" onclick="terms_detail('<?=$row->type ?>')"><img src="/images/arrow_right.svg"></a></span>
              </li>
              <?}?>
            
            </ul>
          </div>
        </div> 
      </div>
      <div class="txt_center">
        <h5><?=lang('lang_10102','결제 예정 금액')?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b class="point_color"><?=number_format($result->product_price)?> <?=lang('lang_10103','원')?></b></h5>
        <div class="btn_m btn_point mt40"><a href="javascript:void(0)" onClick="order_reg_in()"><?=lang('lang_10104','결제하기')?></a></div>
      </div>
    </div>
  </div>
</div>
<div class="modal modal_terms">
  <div class="title" id="modal_title"><?=lang('lang_10105','이용약관')?></div>
  <img src="/images/i_delete_pop.png" alt="" onclick="modal_close('terms')" class="btn_del">
  <div id="edit">
    <?=lang('lang_10106','약관내용')?>
  </div>
</div>
<div class="md_overlay md_overlay_terms" onclick="modal_close('terms')"></div>


<script> 
var product_swiper = new Swiper(".product_swiper", { 
  pagination: {
    el: ".swiper-pagination",
  },
  slidesPerView:1, 
});
</script>
<script>
var electron_book_idx="<?=$result->electron_book_idx?>";
var return_url="<?=$this->current_lang?>/<?=mapping('electron_book')?>/product_payment&electron_book_idx="+electron_book_idx;

// 주문하기
function order_reg_in(){
  if(COM_login_check(return_url)==false){ return;}  
  var selected_idx = get_checkbox_value('checkOne');

  if(selected_idx !="Y,Y"){
    alert("<?=lang('lang_10107','필수 약관 동의에 체크해주세요.')?>");
    return  false;
  }

  var formData = {
    'electron_book_idx' :electron_book_idx,
  };

  $.ajax({
    url      : "/<?=$this->current_lang?>/<?=mapping('electron_book')?>/order_reg_in",
    type     : 'POST',
    dataType : 'json',
    async    : true,
    data     : formData,
    success: function(result){
      if(result.code == '-1'){

        alert(result.code_msg);
        $("#"+result.focus_id).focus();
        return;
      }
      // 0:실패 1:성공
      if(result.code == "-2") {
        alert(result.code_msg);
      } else {
        alert(result.code_msg); 
        location.href ='/<?=$this->current_lang?>/<?=mapping('electron_book')?>/product_payment_complete?order_number='+result.order_number;
      }
    }
  });
}


function terms_detail(type){
  var form_data = {
    'type' : type
  };

  $.ajax({
    url: "/<?=$this->current_lang?>/<?=mapping('join')?>/terms_detail",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: form_data,
    success: function(result){
      if(result.code == '-1'){
      alert(result.code_msg);
      return;
      }
      // 0:실패 1:성공
      if(result.code == 0) {
      alert(result.code_msg);
      } else {
        $('#modal_title').html(result.title);
        $('#edit').html(result.contents);
        modal_open('terms');
      }
    }
  });
}

</script>