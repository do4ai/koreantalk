<?php
foreach($result_list as $row){
?>
 <li>
    <table>
      <colgroup>
        <col width='40px'>
        <col width='60px'>
        <col width='*'>
        <col width='100px'>
        <col width='100px'>
      </colgroup>
      <tr>
        <th>
          <img src="/images/i_file.png" alt="">
        </th>
        <th><b class="fs_16">01</b></th>
        <th><?=$row->movie_name?></th>
        <td><?=$row->movie_time?></td>
        <td><div class="btn_full_thin btn_point_line"><a herf="javascript:void(0)" onClick="member_watched_movie_reg_in('<?=$row->lecture_movie_idx?>','<?=$row->youtube_id?>')"><?=lang('lang_10180','영상 보기')?></a></div></td>
      </tr>
    </table>
  </li>
  <?
  }
  
  if(empty($result_list)){
  ?> 
 <li>
 <p><?=lang('lang_10181','조회된 영상이 없습니다.')?></p>
  </li>
<?}?>


