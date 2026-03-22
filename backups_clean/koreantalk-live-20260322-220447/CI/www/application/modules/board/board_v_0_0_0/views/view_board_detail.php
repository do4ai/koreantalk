<div class="body  row"> 
  <div class="inner_wrap"> 
    <table class="tbl_board_detail">
      <colgroup>
      <col width='*'>
      <col width='160px'>
      </colgroup>
      <tr>
        <th colspan="2"><div class="sub_title">한국어 학습 네트워크: 언어와 문화의 공간</div></th>
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
        <td>
          <div class="wrap_btn">
            <div class="btn_full_thin btn_gray_line">
              <a href="">수정</a>
            </div>
            <div class="btn_full_thin btn_gray_line">
              <a href="">삭제</a>
            </div>
          </div>
        </td>
      </tr>
    </table>
    <div class="wrap_board_detail">
      <img src="/p_images/s1.png" class="img_block">
      <p>다양한 국적의 친구들과 언어 교환을 통해 새로운 문화와 친밀감을 만나보세요. 해외여행을 한 번도 못가봤는데...가게되면 그냥 패키지같은걸루 유럽한달 이런거가보고싶은데다들 언어공부하고가나? 다양한 국적의 친구들과 언어 교환을 통해 새로운 문화와 친밀감을 만나보세요. 해외여행을 한 번도 못가봤는데...가게되면 그냥 패키지같은걸루 유럽한달 이런거가보고싶은데다들 언어공부하고가나?</p>
    </div>

    <div class="wrap_comment">
      <div class="wrap_input">
        <h2>댓글 120</h2>
        <div class="btn_send" onclick="modal_open('send')">댓글 등록</div>
      </div>
      <div class="wrap_result">
        <ul>
          <li>
            <table>
              <tr>
                <th>
                  <button class="i_writer">작성자</button>
                  <b class="name">정민석</b>
                </th>
              </tr>
              <tr>
                <td>
                  <p class="contents">이런 커뮤니티가 있어서 너무 기뻐요. 다른 분들과 함께 언어 공부를 하며 새로운 친구들을 만나고 싶어요.</p>
                  <div class="date">2023-08-11 14:13</div>
                </td>
              </tr>
            </table>
            <img src="/images/_i_dot.png" alt="" class="btn_dot"> 
            <ul class="ul_mod">
              <li>
                <a href="">수정</a>
              </li>
              <li>
                <a href="">삭제</a>
              </li>
            </ul>
          </li>
          <li>
            <table>
              <tr>
                <th> 
                  <b class="name point_color">정민석</b>
                </th>
              </tr>
              <tr>
                <td>
                  <p class="contents">이런 커뮤니티가 있어서 너무 기뻐요. 다른 분들과 함께 언어 공부를 하며 새로운 친구들을 만나고 싶어요.</p>
                  <div class="date">2023-08-11 14:13</div>
                </td>
              </tr>
            </table>
            <img src="/images/_i_dot.png" alt="" class="btn_dot"> 
            <ul class="ul_mod">
              <li>
                <a href="">수정</a>
              </li>
              <li>
                <a href="">삭제</a>
              </li>
            </ul>
          </li>
          <li>
            <div class="no_data">
              <p>운영규정 미준수로 인해 삭제된 댓글 입니다.</p>
            </div>
          </li>
          <li>
            <div class="no_data">
              <p>삭제된 댓글 입니다.</p>
            </div>
          </li>
        </ul>
        <div class="btn_m btn_gray_line"><a href="">댓글 더보기 <img src="/images/icon_arrow_down.png" alt=""></a></div>
        <div class="btn_m btn_gray"><a href="">목록</a></div>
      </div>
    </div>
    <div class="modal modal_send">
      <h2>댓글 등록</h2>
      <textarea name="" id="" cols="" rows=""></textarea>
      <div class="row f_right">
        <div class="btn_m btn_gray">
          <a href="">취소</a>
        </div>
        <div class="btn_m btn_point">
          <a href="">등록</a>
        </div>
      </div>
    </div>
    <div class="md_overlay md_overlay_send" onclick="modal_close('send')"></div>
  </div>
</div>
<div class="back"></div>
<script>
  $('.btn_dot').click(function(){
    $(this).siblings('.ul_mod').toggle();
    if($(this).siblings('.ul_mod').show()){
      $('.back').show();
    }else{
      $('.back').hide();
    }
  });
  $('.back').click(function(){
    $('.ul_mod').hide();
    $('.back').hide();
  });
</script>