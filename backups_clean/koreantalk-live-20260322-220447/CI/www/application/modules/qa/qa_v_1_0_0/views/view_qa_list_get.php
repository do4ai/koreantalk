
<table class="tbl_notice">
      <colgroup>
        <col width='80px'>
        <col width='*'>
        <col width='150px'>
        <col width='200px'>
      </colgroup>
      <tr>
        <th><?=lang('lang_10286','NO')?></th>
        <th class="txt_center"><?=lang('lang_10287','제목')?></th>
        <th><?=lang('lang_10288','답변')?></th>
        <th><?=lang('lang_10289','작성일')?></th>
      </tr>
      <?
      foreach($result_list as $row){    
      ?>
      <tr>
        <td class="txt_center"><?=$no--?></td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_detail?qa_idx=<?=$row->qa_idx?>"><?=$row->qa_title?></a></td>
        <td class="txt_center"><div class="<?=($row->reply_yn=="Y")?"point_color":"";?>"><?=($row->reply_yn=="Y")?"<?=lang('lang_10290','답변완료')?>":"<?=lang('lang_10291','미답변')?>";?></div></td>
        <td class="txt_center"><?=$this->global_function->date_Ymd_Hyphen($row->ins_date)?></td>
      </tr>
      <?}
    if(empty($result_list)){

    ?>

      <tr>
        <td colspan="4">
          <div class="no_data">
            <p><?=lang('lang_10292','등록된 1:1 상담 사항이 없습니다.')?></p>
          </div>
        </td>
      </tr>
      <?}?>
    </table> 
  <?=$paging?> 


