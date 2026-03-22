<div class="table-responsive">

  <div class="row table_title">
    <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong> 건</div>
    <div class="col-lg-6 text-right"> &nbsp;
      <!-- <a class="btn btn-gray" href="javascript:void(0)" onclick="default_select_del()">선택삭제</a> -->
       <a class="btn btn-success" href="/<?=mapping('board_notice')?>/board_notice_reg">등록</a></div>
  </div>

  <form name="form_default" id="form_default" method="post">
    <table class="table table-bordered">
      <thead>
        <tr>
          <!-- <th width="50"><input type="checkbox" onclick="chk_box(this.checked)"></th> -->
          <th width="50">No</th>
          <th width="*">제목</th>
          <th width="100">사이트</th>
          <th width="100">게시판 종류</th>
          <th width="100">노출 여부</th>
          <th width="100">글 순서</th>
          <th width="150">등록일</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if(!empty($result_list)){
            foreach($result_list as $row){
              $category_name = '';
              switch ($row->category) {
                case 1:
                  $category_name = '커뮤니티';
                  break;
                case 2:
                  $category_name = '결혼';
                  break;
                case 3:
                  $category_name = '유학';
                  break;
                case 4:
                  $category_name = '근로자';
                  break;  
                case 5:
                  $category_name = '동포';
                  break;    
                default:
                    // 표현식의 결과가 어떤 case에도 해당하지 않을 때 실행될 코드 (선택 사항)
                    break;
            }
        ?>
        <tr>
          <td>
            <?=$no--?>
          </td>
 
          <td class="td_left">
            <a href="/<?=mapping('board_notice')?>/board_notice_detail?board_idx=<?=$row->board_idx?>&history_data=<?=$history_data?>"><?=$row->title?></a>
          </td>
          <td>
            <?=$row->site_name?>
          </td>   
          <td>
            <?=$category_name?>
          </td>   
          <td>
            <?=($row->display_yn =="Y")?"활성화":"비활성화";?>
          </td>
          <td>
            <?=$row->board_notice_order_no?>
          </td>   
          <td>
            <?=$row->ins_date?>
          </td>
        </tr>

        <?php
            }
          }else{
        ?>
        <tr>
          <td colspan="15">
            <?=no_contents('0')?>
          </td>
        </tr>
        <?php } ?>

      </tbody>
    </table>
  </form>
	<?=$paging?>
</div>
