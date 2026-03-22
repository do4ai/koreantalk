<!-- container-fluid : s -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="page-header">
    <h1> 커뮤니티 공지사항</h1>
  </div>

  <!-- body : s -->
  <div class="bg_wh mt20">
    <div class="table-responsive">
      <form name="form_default" id="form_default" method="post">
       <?
      if($this->site_code !=""){
      ?>
        <input type="text" style="display:none" id="site_code" name="site_code" value="<?=$this->site_code?>">
      <?}?>
      <table class="search_table">
        <colgroup>
          <col style="width:10%">
          <col style="width:35%">
          <col style="width:10%">
          <col style="width:35%">
        </colgroup>
        <tbody>
          <tr>
            <th>제목</th>
            <td>
              <input name="title" id="title" class="form-control">
            </td>
            <th>등록일</th>
            <td>
              <input name="s_date" id="s_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
              <input name="e_date" id="e_date" class="form-control datepicker">&nbsp;<i class="fa fa-calendar-o"></i>
            </td>
          </tr>
            <?
            if($this->site_code ==""){
            ?>
            <tr>
              <th>사이트</th>
              <td>
               <select name="site_code" id="site_code" class="form-control w_auto">
                <option value="">선택</option>
                  <?php foreach($site_list as $row){ ?>
                    <option value="<?=$row->site_code?>"  >
                      <?=$row->site_name?>
                    </option>
                  <?php } ?>
              </select>
              </td>
              <th>게시판 종류</th>
              <td>
                <select name="category" id="category" class="form-control ">
                  <option value="">선택</option>
                    <option value="1">커뮤니티</option>
                    <option value="2">결혼</option>
                    <option value="3">유학</option>
                    <option value="4">근로자</option>
                    <option value="5">동포</option>
                </select>
              </td>
            </tr>
            <?}?>

        </tbody>
      </table>
      </form>
      <div class="text-center mt20">
        <a class="btn btn-success" href="javascript:void(0)" onclick="default_list_get(1);">검색</a>
      </div>
    </div>
    <!-- search : e -->

    <div class="bg_wh mb20" id="list_ajax"></div>

  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->
<input type="text" name="page_num" id="page_num" value="1"  style="display:none">
<script>

$(document).ready(function(){
  var page_num =  $('#page_num').val();
  default_list_get(page_num);
})

  function default_list_get(page){
    $('#page_num').val(page);

    var formData = {
      'title' :  $('#title').val(),
      's_date' : $('#s_date').val(),
      'e_date' : $('#e_date').val(),
      'site_code' : $('#site_code').val(),
      'category' : $('#category').val(),
      'history_data' : window.history.length,
      'page_num' : page
    };

    $.ajax({
      url      : "/<?=mapping('board_notice')?>/board_notice_list_get",
      type     : "POST",
      dataType : "html",
      async    : true,
      data     : formData,
      success: function(result) {
        $('#list_ajax').html(result);
      }
    });
  }


</script>
