  <div class="container-fluid wide">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>전자책 관리</h1>

    </div>

    <!-- body : s -->   
       <form name="form_default" id="form_default">
  
      <div class="bg_wh mt20">
        <!-- search : s -->
        <div class="table-responsive">
          <table class="search_table">
            <colgroup>
            <col style="width:15%">
            <col style="width:35%">
            <col style="width:15%">
            <col style="width:35%">
            </colgroup>
            <tbody>
              <tr>
                <th>상품명</th>
                <td >
                  <input type="text" class="form-control" name="product_name" id="product_name">
                </td>
                <th>노출여부</th>
                <td>
                  <input type="radio" name="display_yn" value="" checked > 전체
                  <input type="radio" name="display_yn" value="Y" > 활성화
                  <input type="radio" name="display_yn" value="N" > 비활성화
                </td>
               
              </tr>
              <?
              if($this->site_code ==""){
              ?>
              <tr>
                <!-- <th> 사이트 </th>
                <td >
                <select name="site_code" id="site_code" class="form-control w_auto">
                  <option value="">선택</option>
                    <?php foreach($site_list as $row){ ?>
                      <option value="<?=$row->site_code?>"  >
                        <?=$row->site_name?>
                      </option>
                    <?php } ?>
                </select>
                </td> -->
                 <th>등록일</th>
                <td colspan=3>
                  <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>
              </tr>
               <?}else{?> 
              <tr>
                 <th>등록일</th>
                <td colspan=3>
                  <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>

                  <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
                </td>              
              </tr>
              <?}?>

           
            </tbody>
          </table>
          </form>



          <div class="text-center mt20">
            <a href="javascript:void(0);" onclick="default_list_get(1);" class="btn btn-success">검색</a>
          </div>
        </div>
      </div>
      <!-- search : e -->



      <div class="bg_wh mt20">
        <div class="table-responsive">
          <!-- list_get : s -->
          <div id="list_ajax"></div>
          <!-- list_get : e -->
        </div>
      </div>
      <input type="text" style="display:none" id="page_num" name="page_num" value="1">
    </form>
  </div>
  <!-- container-fluid : e -->

<script>
  $(function(){
    default_list_get($("#page_num").val());
  });

  function default_list_get(page_num){

    var form_data = {
      'product_name' : $('#product_name').val(),
      'display_yn' :  $("input[name='display_yn']:checked").val(),    
      'site_code' : $('#site_code').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'page_num' : page_num,
      'history_data' : window.history.length
    };

    $.ajax({
      url      : "/<?=mapping('electron_book')?>/product_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : form_data,
      success  : function(result) {
        $('#list_ajax').html(result);
        $("#page_num").val(page_num)
      }
    });
  }

</script>
