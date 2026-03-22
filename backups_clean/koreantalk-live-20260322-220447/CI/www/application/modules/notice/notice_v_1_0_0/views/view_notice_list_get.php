<table class="tbl_notice">
      <colgroup>
        <col width='80px'>
        <col width='*'>
        <col width='200px'>
      </colgroup>
      <tr>
        <th><?=lang('lang_10261','NO')?></th>
        <th class="txt_center"><?=lang('lang_10262','제목')?></th>
        <th><?=lang('lang_10263','작성일')?></th>
      </tr>
      <?php
        foreach($result_list as $row){
      ?>
      <tr>
        <td><?=$no--?></td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('notice')?>/notice_detail?notice_idx=<?=$row->notice_idx?>"><?=$row->title?></a></td>
        <td class="txt_center"><?=$row->ins_date?></td>
      </tr>
      <?
      }
      if(empty($result_list)){
      ?> 
      <tr>
        <td colspan="3">
          <div class="no_data">
            <p><?=lang('lang_10264','등록된 공지사항이 없습니다.')?></p>
          </div>
        </td>
      </tr>
      <?}?>
    </table>
    <?=$paging?>
  
  
  