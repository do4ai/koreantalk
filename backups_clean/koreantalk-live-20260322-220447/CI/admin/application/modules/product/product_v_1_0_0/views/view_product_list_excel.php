<?php
  $filename="상품리스트_".date('Ymd');
	header( "Content-type: application/vnd.ms-excel; charset=utf-8" );
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
	header( "Pragma: public" );
	header( "Content-Disposition: attachment; filename=$filename.xls" );
?>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <table class="table table-bordered tr_link">
      <thead>
        <tr>
          <th width="30">No</th>
          <th width="30">No</th>
          <th>분류</th>
          <th>상품명</th>
          <th>브랜드</th>
          <th>소비자가</th>
          <th>판매가</th>
          <th width="100">전시여부</th>
          <th width="100">판매여부</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = count($result_list);
          if(!empty($result_list)){
            foreach($result_list as $row){
        ?>

        <tr>
          <td>
            <?=$no--?>
          </td>
          <td>
            <?php
              $temp = explode(",",$row->product_category_list);
              for($i=0;$i<count($temp);$i++){
                $arr_temp = explode(">",$temp[$i]);
                $temp_=$arr_temp[0];
                if(!empty($arr_temp[1])){ $temp_ .=">".$arr_temp[1];}
                if(!empty($arr_temp[2])){ $temp_ .=">".$arr_temp[2];}
                echo $temp_."<br>";
              }
            ?>
          </td>
          <td>
            <a href="/<?=mapping('product')?>/product_mod?product_idx=<?=$row->product_idx?>"><?=$row->product_name?></a>
          </td>
           <td><?=$row->brand_name?></td>
          <td><?=number_format($row->product_st_price)?></td>
          <td><?=number_format($row->product_price)?></td>
          <td>
            <?php
              if($row->product_display == '0') echo "미전시";
              else if($row->product_display == '1') echo "전시";
            ?>
          </td>
          <td>
            <?php
              if($row->product_state == '0') echo "판매안함";
              else if($row->product_state == '1') echo "판매안함";
            ?>
          </td>
        </tr>
        <?php
            }
          }else{
        ?>
         <tr>
          <td colspan="14"><?=no_contents('0')?></td>
        </tr>

        <?php
          }
        ?>
      </tbody>
    </table>
  </div>
