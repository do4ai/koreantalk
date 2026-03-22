<div class="row table_title">
  <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=number_format($result_list_count)?></strong> 건</div>
</div>


<!-- top  -->

  <table class="table table-bordered check_wrap">
    <thead>
      <tr>
        <th width="50">No</th>
        <th width="100">사이트</th>
        <th width="*">제목</th>
        <th width="300">아이디</th>

        <th width="100">답변상태</th>
        <th width="150">등록일</th>
      </tr>
    </thead>
    <tbody>

      <?php
        if(!empty($result_list)){
          foreach($result_list as $row){
      ?>
        <tr>
          <td>
            <?=$no--?>
          </td>
          <td>
            <?=$row->site_name?>
          </td>
          <td>
            <a href="/<?=mapping('qa')?>/qa_detail?qa_idx=<?=$row->qa_idx?>&history_data=<?=$history_data?>"><?=$row->qa_title?></a>
          </td>
           <td>
            <?=$row->member_id?>
          </td>

          <td>
            <?php
              if($row->reply_yn == "Y") echo "<span class='state_02'>답변완료</span>";
              else echo "<span class='state_01'>미답변</span>";
            ?>
          </td>
          <td>
            <?=$this->global_function->date_YmdHi_hyphen($row->ins_date)?>
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

      <?php
        }
      ?>

    </tbody>
  </table>


<?=$paging?>
