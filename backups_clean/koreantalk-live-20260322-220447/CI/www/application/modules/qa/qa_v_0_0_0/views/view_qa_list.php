<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative">1:1 상담
      <div class="btn_full_thin btn_point_line btn_board_reg">
      <a href="/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_reg">등록</a>
      </div>
    </h2> 
    <table class="tbl_notice">
      <colgroup>
        <col width='80px'>
        <col width='*'>
        <col width='150px'>
        <col width='200px'>
      </colgroup>
      <tr>
        <th>NO</th>
        <th class="txt_center">제목</th>
        <th>답변</th>
        <th>작성일</th>
      </tr> 
      <tr>
        <td class="txt_center">10</td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_detail">상세</a></td>
        <td class="txt_center"><div class="point_color">답변완료</div></td>
        <td class="txt_center">-</td>
      </tr>
      <tr>
        <td class="txt_center">9</td>
        <td><a href="/<?=$this->current_lang?>/<?=mapping('qa')?>/qa_detail">상세</a></td>
        <td class="txt_center"><div class="">미답변</div></td>
        <td class="txt_center">2023-08-22 19:50</td>
      </tr>
      <tr>
        <td colspan="4">
          <div class="no_data">
            <p>등록된 1:1 상담 사항이 없습니다.</p>
          </div>
        </td>
      </tr>
    </table> 
  <?=$paging?> 
  </div>
</div>