<!-- top -->
<div class="row table_title">
  <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <?= number_format($result_list_count) ?> 건</div>
  <div class="col-lg-6 text-right"><a href="/<?=mapping('start_popup')?>/start_popup_reg" class="btn btn-success">등록</a></div>
</div>
<!-- top  -->
<!-- list_get : s -->
<div >
  <table class="table table-bordered check_wrap">
  	<thead>
  		<tr>
  			<th width="100">No</th>
  			<th width="*">제목</th>
        <th width="200">노출 여부</th>
        <th width="120">등록일</th>
  		</tr>
  	</thead>
  	<tbody>
      <?php
      	foreach ($result_list as $row ) {
      ?>
        <tr>
          <td><?=$no--?></td>
          <td><a href="/<?=mapping('start_popup')?>/start_popup_detail?start_popup_idx=<?= $row->start_popup_idx ?>&history_data=<?=$history_data?>"><?=$row->title ?></a></td>
          <td>
            <?php if($row->state == "0"){ ?>
              노출안함
              <label class="switch">
                <input type="checkbox" onchange="start_popup_state_mod_up(<?=$row->start_popup_idx?>, '1');">
                <span class="check_slider"></span>
              </label>
              노출
            <?php }else if($row->state == "1"){ ?>
              노출안함
              <label class="switch">
                <input type="checkbox" onchange="start_popup_state_mod_up(<?=$row->start_popup_idx?>, '0');" checked>
                <span class="check_slider"></span>
              </label>
              노출
            <?php } ?>
          </td>
          <td><?=$row->ins_date ?></td>
        </tr>
      <?php } ?>
      <?php if(empty($result_list)){ ?>
      <tr>
        <td colspan="15">
          <?=no_contents('0')?>
        </td>
      </tr>
      <?php } ?>

  	</tbody>
  </table>

<?=$paging?>
