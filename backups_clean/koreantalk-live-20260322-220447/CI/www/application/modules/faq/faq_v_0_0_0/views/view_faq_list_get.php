
        <table class="board_table accordion mt30">
          <thead>
            <tr>
              <th class="text_center" width="30">번호</th>
              <th class="text_center" width="90">구분</th>
              <th class="text_center">제목</th>
            </tr>
          </thead>
          <tbody>
            <?php
               if(!empty($result_list)){
                 foreach($result_list as $row){
             ?>
            <tr>
              <td class="text_center"><?=$no--?></td>
              <td class="text_center"><?=$row->faq_category_name?></td>
              <td>
                <p class="trigger"><?=$row->title?></p>
                <div class="panel">
                 <?=$row->contents?>
                </div>
              </td>
            </tr>
            <?php
                 }
               }else{
             ?>
              <tr>
               <td colspan=11> 검색된 데이타가 없습니다.</td></tr>
             <?php
               }
             ?>
          </tbody>
        </table>

        <!-- paging : s -->
        <div class="paging">
          <?=$paging?>
        </div>
        <!-- paging : e -->
