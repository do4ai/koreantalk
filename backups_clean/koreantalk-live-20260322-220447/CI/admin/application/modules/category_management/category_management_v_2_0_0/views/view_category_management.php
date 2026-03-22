<style>
  .ui-state-highlight { height: 3em; line-height: 1.2em; }
</style>

  <!-- container-fluid : s -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>카테고리관리</h1>
    </div>
    <form id="form_default" name="form_default" method="post">
      <!-- body : s -->
      <div class="bg_wh mb20 mt20">
        <div class="table-responsive">

          <div class="row category_col">
            <div class="col-md-4">
              <h4>장르</h4>
              <ul class="category sortable" >
              <?php
                $i = 0;
                foreach($first_cate_list as $row){ ?>
                <li <?php if($i==0){ echo 'class="active"'; } ?>>
                  <a href="#" class="move"><i class="fa fa-arrows" aria-hidden="true"></i></a>
                  <input type="text" id="<?=$row->category_management_idx?>" value="<?=$row->category_name?>" readonly>
                  <input type="hidden" name="first_cate_list_idx[]" value="<?=$row->category_management_idx?>">
                  <span class="category_btn">
                    <?php if($row->state == 1) {?>
                      <a href="javascript:void(0)" class="btn-sm btn-info active"><i class='fa fa-eye'></i></a>
                    <?php } else { ?>
                      <a href="javascript:void(0)" class="btn-sm btn-info"><i class='fa fa-eye-slash'></i></a>
                    <?php } ?>
                    <a href="javascript:void(0)" class="btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                    <a href="javascript:void(0)" class="btn-sm btn-default"><i class="fa fa-pencil"></i></a>
                  </span>
                </li>
                <?php
                  $i++;
                }
              ?>
              </ul>

              <h4>장르 추가</h4>
              <input type="text" class="form-control add_item_val" placeholder=""> <a href="#" class="btn btn-info" onclick="category_management_reg_in(0)"> 추가</a>
            </div>
            <div class="col-md-4">
              <h4>분위기</h4>
              <ul class="category sortable">
              <?php
                $i = 0;
                foreach($second_cate_list as $row){ ?>
                <li <?php if($i==0){ echo 'class="active"'; } ?>>
                  <a href="#" class="move"><i class="fa fa-arrows" aria-hidden="true"></i></a>
                  <input type="text" id="<?=$row->category_management_idx?>" value="<?=$row->category_name?>" readonly>
                  <input type="hidden" name="second_cate_list_idx[]" value="<?=$row->category_management_idx?>">
                  <input type="hidden" name="parent_category_management_idx" value="<?=$row->parent_category_management_idx?>">
                  <span class="category_btn">
                    <?php if($row->state == 1) {?>
                      <a href="javascript:void(0)" class="btn-sm btn-info active"><i class='fa fa-eye'></i></a>
                    <?php } else { ?>
                      <a href="javascript:void(0)" class="btn-sm btn-info"><i class='fa fa-eye-slash'></i></a>
                    <?php } ?>
                    <a href="javascript:void(0)" class="btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                    <a href="javascript:void(0)" class="btn-sm btn-default"><i class="fa fa-pencil"></i></a>
                  </span>
                </li>
                <?php
                  $i++;
                }
              ?>
              </ul>
              <h4>분위기 추가</h4>
              <input type="text" class="form-control add_item_val" placeholder=""> <a href="#" class="btn btn-info" onclick="category_management_reg_in(1)"> 추가</a>
            </div>
            <div class="col-md-4">
              <h4>속도</h4>
              <ul class="category sortable">
              <?php
                $i = 0;
                foreach($third_cate_list as $row){ ?>
                <li <?php if($i==0){ echo 'class="active"'; } ?>>
                  <a href="#" class="move"><i class="fa fa-arrows" aria-hidden="true"></i></a>
                  <input type="text" id="<?=$row->category_management_idx?>" value="<?=$row->category_name?>" readonly>
                  <input type="hidden" name="third_cate_list_idx[]" value="<?=$row->category_management_idx?>">
                  <input type="hidden" name="parent_category_management_idx" value="<?=$row->parent_category_management_idx?>">
                  <span class="category_btn">
                    <?php if($row->state == 1) {?>
                      <a href="javascript:void(0)" class="btn-sm btn-info active"><i class='fa fa-eye'></i></a>
                    <?php } else { ?>
                      <a href="javascript:void(0)" class="btn-sm btn-info"><i class='fa fa-eye-slash'></i></a>
                    <?php } ?>
                    <a href="javascript:void(0)" class="btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                    <a href="javascript:void(0)" class="btn-sm btn-default"><i class="fa fa-pencil"></i></a>
                  </span>
                </li>
                <?php
                  $i++;
                }
              ?>
              </ul>
              <h4>속도 추가</h4>
              <input type="text" class="form-control add_item_val" placeholder=""> <a href="#" class="btn btn-info" onclick="category_management_reg_in(2)"> 추가</a>
            </div>
          </div>
          <input type="hidden" name ="select_depth" id="select_depth">

        </div>
      </div>
      <!-- body : e -->
    </form>
  </div>
  <!-- container-fluid : e -->

<script>

  //리스트 클릭 활성화
  $(document).on("click",".category li",function(){
    // 선택된 카테고리 표시
    $(this).siblings("li").removeClass("active");
    $(this).addClass("active");

    // 클릭한 카테고리의 뎁스
    var category_depth = $(".category").index($(this).parent()) + 1;

    var category_management_idx = null;
    category_management_idx = $(this).find('input[type="text"]').attr('id');
    $("#select_depth").val(category_depth-1);


  });

  //삭제, 수정 기능
  $(document).on("click",".category_btn a",function(){
    var $item = $(this).parents("li");
    var category_depth = $('.category').index($item.parent()) + 1;
    var category_management_idx = $item.find('input').attr('id');

		if($(this).hasClass("btn-danger")){ //삭제버튼
			var result = confirm("삭제하시겠습니까?");
			if(result){
				category_management_del(category_management_idx);
				$item.remove();
			}
    } else if($(this).hasClass("btn-info")){ // 비활성화
      var result = confirm("수정 하시겠습니까?");
			if(result){
				category_state_up(category_management_idx);
        if($(this).hasClass("active")){
          $(this).removeClass("active");
          $(this).html("<i class='fa fa-eye-slash'></i>");
        }else{
          $(this).addClass("active")
          $(this).html("<i class='fa fa-eye'></i>");
        }
			}
    } else {
      if($(this).hasClass("btn-confirm")){ //수정 후 확인버튼
				var result = confirm("수정된 내용을 저장 하시겠습니까?");
				if(result){
					$item.find("input").attr("readonly",true)
					$(this).html("<i class=\"fa fa-pencil\"></i>").removeClass("btn-confirm");
					var category_management_idx = $item.find("input").attr('id');
					var category_name = $item.find("input").val();

					category_management_mod_up(category_management_idx, category_name);
				}
      } else { //수정 버튼
        $item.find("input").attr("readonly",false).focus();
        $(this).html("<i class=\"fa fa-check\"></i>").addClass("btn-confirm");
      }
    }
  });

// 카테고리 리스트 가져오기
  function category_management_list(parent_category_management_idx, category_depth){

    $.ajax({
  		url: "/<?=mapping('category_management')?>/category_management_list",
  		type: "post",
  		data : {parent_category_management_idx: parent_category_management_idx},
  		dataType: 'json',
  		async: true,
  		success: function(result){
        if(result.category_management_list){
          for(var i=0; i<result.category_management_list.length; i++){
            var category_management_idx = result.category_management_list[i].category_management_idx;
            var category_name = result.category_management_list[i].category_name;
            var parent_category_management_idx = result.category_management_list[i].parent_category_management_idx;
            var state = result.category_management_list[i].state;
            add_item(category_management_idx, category_name, state);
          }
        }
  		}
  	});
  }

  // 분류 추가 기능
  function category_management_reg_in(type){
    var target_list = $(".category").eq(type);
    var category_name = $(".add_item_val").eq(type).val();

    if(category_name == ""){
      alert("분류명을 입력해주세요.");
      return;
    }

    $.ajax({
      url: "/<?=mapping('category_management')?>/category_management_reg_in",
      type: "POST",
      dataType: "json",
      async: true,
      data: {
        "type": type,
        "category_name": category_name
      },
      success: function(result) {
        if(result.code == 1){
          var category_management_idx = result.category_management_idx;
          add_item(category_management_idx, category_name,type);
          $(".add_item_val").eq(type).val("");
					alert("카테고리가 추가되었습니다.");
        }else{
          alert(result.msg);
        }
      },
      error: function(request,status,error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });
  }

// 상품 카테고리 삭제
  function category_management_del(category_management_idx){
    $.ajax({
      url: "/<?=mapping('category_management')?>/category_management_del",
      type: "POST",
      dataType: "json",
      async: true,
      data: {
        "category_management_idx": category_management_idx
      },
      success: function(result) {
        console.log(result);
      },
      error: function(request,status,error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });
  }

// 상품 카테고리 수정
  function category_management_mod_up(category_management_idx, category_name){
    $.ajax({
      url: "/<?=mapping('category_management')?>/category_management_mod_up",
      type: "POST",
      dataType: "json",
      async: true,
      data: {
        category_management_idx: category_management_idx,
        category_name: category_name
      },
      success: function(result) {
        console.log(result);
      },
      error: function(request,status,error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });
  }

// 카테고리  활성 / 활성화
  function category_state_up(category_management_idx){

    $.ajax({
      url: "/<?=mapping('category_management')?>/category_state_up",
      type: "POST",
      dataType: "json",
      async: true,
      data: {
        category_management_idx: category_management_idx
      },
      success: function(result) {

      },
      error: function(request,status,error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });
  }

// 카티고리 화면에서 추가
  function add_item(category_management_idx, category_name,type,state){

    var category_state ="";
    if( state == 0){
      category_state = "<a href='javascript:void(0)' class='btn-sm btn-info'><i class='fa fa-eye-slash'></i></a> "
    } else {
      category_state = "<a href='javascript:void(0)' class='btn-sm btn-info active'><i class='fa fa-eye'></i></a> "
    }

    var $item = $("<li>" +
                  "<a href='#' class='move'><i class='fa fa-arrows' aria-hidden='true'></i></a>" +
                  " <input type='text' name='name' id='" + category_management_idx + "'value='" + category_name + "' readonly>" +
                  " <span class='category_btn'>" +
                  category_state +
                  "   <a href='#' class='btn-sm btn-danger'><i class='fa fa-trash-o'></i></a>" +
                  "   <a href='#' class='btn-sm btn-default'><i class='fa fa-pencil'></i></a>" +
                  " </span>" +
                  "</li>");

    $('.category').eq(type).append($item);


  }

  var category_order_set = function (){

    $.ajax({
      url: "/<?=mapping('category_management')?>/category_order_set",
      type: "POST",
      dataType: "json",
      async: true,
      data: $("#form_default").serialize(),
      success: function(result) {
        if(result.code == 0){
          alert(result.msg);
        }
      }

    });
  }

  $(function() {
    $( ".sortable" ).sortable({
      placeholder: "ui-state-highlight",
      axis: "y",
      update: function() {
        $("#select_depth").val($(this).index('.sortable'));
        category_order_set();
      }
    }).disableSelection();

  });
</script>
