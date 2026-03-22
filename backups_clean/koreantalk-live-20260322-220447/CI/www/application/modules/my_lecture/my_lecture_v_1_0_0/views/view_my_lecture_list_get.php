<table class="tbl_my_lecture mt40 mb100">
      <colgroup>
        <col width='80px'>
        <col width='140px'>
        <col width='100px'>
        <col width='*'>
        <col width='200px'> 
        <col width='130px'> 
      </colgroup>
      <tr>
        <th><?=lang('lang_10243','NO')?></th>
        <th class="txt_center"><?=lang('lang_10244','TOPIK')?></th>
        <th class="txt_center"><?=lang('lang_10245','커리큘럼')?></th>
        <th class="txt_center"><?=lang('lang_10246','목차')?></th>
        <th><?=lang('lang_10247','시청일')?></th>
        <th></th>
      </tr>
      <?php    
      foreach($result_list as $row){
      ?> 
      <tr>
        <td> <?=$no--?></td>
        <td> <?=$row->lecture_name?></td>
        <td> <?=$row->category_name?></td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail?lecture_idx=<?=$row->lecture_idx?>&lecture_category_idx=<?=$row->lecture_category_idx?>&lecture_movie_idx=<?=$row->lecture_movie_idx?>"> <?=$row->movie_name?></a></td>
        <td class="txt_center"><?=$row->ins_date?></td>
        <td>
        <div class="btn_m btn_point_line"><a style="width:82px" href="/<?=$this->current_lang?>/<?=mapping('lecture')?>/lecture_detail?lecture_idx=<?=$row->lecture_idx?>&lecture_category_idx=<?=$row->lecture_category_idx?>&lecture_movie_idx=<?=$row->lecture_movie_idx?>"><?=lang('lang_10248','시청')?></a></div>
        </td>
      </tr> 
      <?
      }
      if(empty($result_list)){
      ?> 
      <tr>
        <td colspan="6">
          <div class="no_data">
            <p><?=lang('lang_10249','시청한 교육 영상이 없습니다.')?></p>
          </div>
        </td>
      </tr>
      <?}?>
    </table>
    <?=$paging?>
 
  

 