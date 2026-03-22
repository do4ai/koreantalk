<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1>FAQ 관리</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <!-- search : s -->
  	<div class="table-responsive">

      <form name="form_default" id="form_default" onkeypress="enter_form();">
    		<table class="search_table">
          <colgroup>
            <col style="width:15%;">
            <col style="width:35%;">
            <col style="width:15%;">
            <col style="width:35%;">
          </colgroup>
          <tbody>
            <tr>
              <th>제목</th>
              <td>
                <input name="title" id="title" class="form-control" placeholder="">
              </td>
              <th>등록일</th>
              <td>
                <input name="s_date" id="s_date" class="form-control" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input name="e_date" id="e_date" class="form-control" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
              </td>
            </tr>
            <?
            if($this->site_code ==""){
            ?>
            <tr>
              <th>사이트</th>
              <td colspan=3>
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
            </tr>
            <?}?>

           
          </tbody>
        </table>
        <?
          if($this->site_code !=""){
        ?>
        <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
        <?}?>
      </form>

      <div class="text-center mt20">
        <a href="javascript:void(0)" onclick="default_list_get(1);" class="btn btn-success" id="btn_search">검색</a>
      </div>
      
      <input type="text" style="display:none" id="page_num" value="1">
    </div>
    <!-- search : e -->

    <div class="bg_wh mb20">
      <div class="table-responsive">
        <div id="list_ajax"></div>
      </div>
    </div>
  </div>

<script type="text/javascript">
  var page_num = $("#page_num").val();
  $(function(){
    default_list_get(page_num);
  });

  function enter_form(){
    if(window.event.keyCode == 13){
      default_list_get(page_num);
    }
  }

  function default_list_get(page){

    var formData = {
      'title' : $('#title').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'site_code' : $('#site_code').val(),      
      'page_num' : page,
      'history_data' : window.history.length
    };

    $.ajax({
      url      : "/<?=mapping('faq')?>/faq_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
        $("#page_num").val(page)
      }
    });
  }


</script>
