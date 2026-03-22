 <table class="table table-bordered">
  <thead>
      <tr>                
      <th width="250" >카테고리</th>
      <th width="*" >동영상 정보</th>

    </tr>

  </thead>
  <tbody >
  <?php
    $result_list = $result['result_list']; 
    $movie_list = $result['movie_list']; 


    if(!empty($result_list)){
      foreach($result_list as $row){
  ?>       
    <tr >

        <td >
         <?=$row->category_name?>  <a class="btn-sm btn-danger" href="#" onclick="lecture_category_del('<?=$row->lecture_category_idx?>')">삭제</a> <a style="cursor:pointer;" class="btn-sm btn-success" data-target="#layerpop2" data-toggle="modal" onclick="set_category_idx('<?=$row->lecture_category_idx?>','<?=$row->category_name?>')">영상추가</a>
      </td>
      <td class="td_left" >
      
        <table class="table table-bordered">
          <thead>
            <tr>                
              <th width="50" >추천</th>
              <th width="*" >동영상 목록( [시간] 목차명 <span style="color:blue">동영상 url</span>)  </th>
            </tr>
          </thead>
          <tbody class="sortable">
            <?
              $filter_array = array_filter($movie_list, function ($item) use ($row) {
                return $item->lecture_category_idx === $row->lecture_category_idx;
              });

              foreach($filter_array as $row2) {

            ?>
            <tr  >
              <td  onmousedown ="set_sortable_category_idx('<?=$row->lecture_category_idx?>')">                   
                <input type="checkbox"  name="checkbox" value="<?=$row->lecture_category_idx?>"  onclick="lecture_movie_main_view_yn_mod_up('<?=$row2->lecture_movie_idx?>')" <?=($row2->main_view_yn =="Y")?"checked":"";?>  >
              </td>
              <td  onmousedown ="set_sortable_category_idx('<?=$row->lecture_category_idx?>')"  class="td_left" >                   
                <input type='checkbox' name='sortable_movie_<?=$row->lecture_category_idx?>'  value='<?=$row2->lecture_movie_idx?>' checked style='display:none' />
                  [<?=$row2->movie_time?>] <?=$row2->movie_name?> <span style="color:blue"><?=$row2->movie_url?></span> 
                  <a class="btn-sm btn-success"  href="#"  data-target="#layerpop3" data-toggle="modal" onclick="lecture_movie_detail('<?=$row2->lecture_movie_idx?>','<?=$row2->movie_name?>','<?=$row2->movie_time?>','<?=$row2->movie_url?>')" >수정</a> 
                  <a class="btn-sm btn-danger" href="#" onclick="lecture_movie_del('<?=$row2->lecture_movie_idx?>')">삭제</a>

              </td>
            </tr>
            <?}?>


          </tbody>
        </table>


      </td>
    </tr>
   <?php
        }
      }else{
    ?>
    <tr>
      <td colspan="15">
        <?=no_contents('0')?>
      </td>
    </tr>
    <?php } ?>
    </tbody>
  </table>

<script>
  $( function() {
    $( ".sortable" ).sortable({
      placeholder: "ui-state-highlight",
      axis: "y",
      update: function() {
        lecture_movie_order_no_mod_up();
      }

    });
    $( ".sortable" ).disableSelection();
  } );
</script>