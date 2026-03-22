<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative">시청한 교육 영상</h2>
    <table class="tbl_my_lecture mt40 mb100">
      <colgroup>
        <col width='80px'>
        <col width='100px'>
        <col width='100px'>
        <col width='*'>
        <col width='200px'> 
        <col width='130px'> 
      </colgroup>
      <tr>
        <th>NO</th>
        <th class="txt_center">TOPIK</th>
        <th class="txt_center">커리큘럼</th>
        <th class="txt_center">목차</th>
        <th>시청일</th>
        <th></th>
      </tr>
      <tr>
        <td>-</td>
        <td>대화</td>
        <td>대화</td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('notice')?>/notice_detail">상세</a></td>
        <td class="txt_center">2023-08-22 19:50</td>
        <td>
        <div class="btn_m btn_point_line"><a style="width:82px" href="">시청</a></div>
        </td>
      </tr> 
      <tr>
        <td colspan="6">
          <div class="no_data">
            <p>시청한 교육 영상이 없습니다.</p>
          </div>
        </td>
      </tr>
    </table>
    <?=$paging?>
  </div>
</div>