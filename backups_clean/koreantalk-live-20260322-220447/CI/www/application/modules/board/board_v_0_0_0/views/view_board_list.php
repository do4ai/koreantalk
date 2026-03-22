<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative">커뮤니티
      
      <div class="btn_full_thin btn_point_line btn_board_reg">
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/board_reg">글쓰기</a>
      </div>
    </h2>
    <div class="wrap_search">
      <input type="text" placeholder='제목, 내용을 입력해 주세요.'>
      <img src="/images/i_search.png" alt="" class="i_search">
    </div>
    

    <div class="no_data">
      <p>등록된 책이 없습니다.</p>
    </div>

    <ul class="ul_board">
      <li>
        <a href="/<?=$this->current_lang?>/<?=mapping('board')?>/board_detail">
          <table class="tbl_board">
            <colgroup>
              <col width='*'>
              <col width='110px'>
            </colgroup>
            <tr>
              <th><h5 class="text-overflow">한국어 학습 네트워크: 언어와 문화의 공간</h5></th>
              <td rowspan="3">
                <div class="img_box thumbnail">
                  <img src="/p_images/s2.png" alt="">
                </div>
              </td>
            </tr>
            <tr>
              <th>
                <p class="contents">다양한 국적의 친구들과 언어 교환을 통해 새로운 문화와 친밀감을 만나보세요. 해외여행을 한 번도 못가봤는데...가게되면 그냥 패키지같은걸루 유럽한달 이런거가보고싶은데다들 언어공부하고가나?</p>
              </th>
            </tr>
            <tr>
              <th>
                <ul class="ul_board_info">
                  <li>
                    <img src="/images/i_comment.png" alt="">
                    100
                  </li>
                  <li>
                    <img src="/images/i_view.png" alt="">
                    100
                  </li>
                  <li>
                  2023-08-08
                  </li>
                  <li>
                  <div class="point_color">폴인아이유</div>
                  </li>
                </ul>
              </th>
            </tr>
          </table>
        </a>
      </li>
    </ul>
     
    <?=$paging?>
  </div>
</div>