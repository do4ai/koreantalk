<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative">공지사항</h2>
    <table class="tbl_notice">
      <colgroup>
        <col width='80px'>
        <col width='*'>
        <col width='200px'>
      </colgroup>
      <tr>
        <th>NO</th>
        <th class="txt_center">제목</th>
        <th>작성일</th>
      </tr>
      <tr>
        <td>-</td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('notice')?>/notice_detail">상세</a></td>
        <td class="txt_center">2023-08-22 19:50</td>
      </tr>
      <tr>
        <td>-</td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('notice')?>/notice_detail">상세</a></td>
        <td class="txt_center">2023-08-22 19:50</td>
      </tr>
      <tr>
        <td colspan="3">
          <div class="no_data">
            <p>등록된 공지사항이 없습니다.</p>
          </div>
        </td>
      </tr>
    </table>
    <?=$paging?>
  </div>
</div>