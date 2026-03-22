
<style>
@charset "utf-8";
body,div,th,td,input,select,textarea,button {margin:0;padding:0}
table {border-collapse:collapse;border-spacing:0;width: 100%;text-align: left; font-weight: normal; }
th.th_top{vertical-align: text-top:10px;}
/* th{font-weight: normal;height:40px} */
table{table-layout: fixed; border-spacing: 0 6px;}
.tbl_3 th{padding: 8px;color:#CB3A27;border:1px solid #CB3A27;text-align: center;height: 40x; line-height: 15px;font-size: 12px; }
.tbl_3:first-child tr:first-child th, .tbl_3:first-child tr:first-child td{border-bottom: 0;}
.tbl_3:nth-child(2) th, .tbl_3:nth-child(2) td{border-bottom: 0;}
.tbl_3:last-child th, .tbl_3:last-child td{border-top: 0;}
.tbl_3 td{padding: 8px;color:#000;border:1px solid #CB3A27;height: 40x;font-size: 12px;}
.tbl_3_wrap{width: 1000px;margin:  auto;}

/* layout */
.wrap{position: relative}
.mt10{margin-top: 10px}
.mt14{margin-top: 14px}
.mt20{margin-top: 20px!important;}
.mt30{margin-top: 30px!important}
.mt40{margin-top: 40px}
.mt50{margin-top: 50px}
.mt60{margin-top: 60px}
.mb10{margin-bottom: 10px;}
.mb20{margin-bottom: 20px;}
.mb30{margin-bottom: 30px;}
.mb40{margin-bottom: 40px;}
.mb140{margin-bottom: 140px;}
.img_block{display: block;width: 100%;}
</style>

<table class="tbl_3 mb30">
  <tr>
    <th width="60px">일자</th>
    <td width="170px"><?=$result->order_date?></td>
    <td style="border: none; width:178px"><div class="main_title" style="font-size: 20px; line-height: 15px; text-align: center; font-weight:bold;">거 래 명 세 표</div></td>
    <th width="60px">인수자</th>
    <td width="170px"><?=$result->acceptor?></td>
  </tr>
</table>

<table class="tbl_3 mt30" >
  <!-- <colgroup>
    <col width="30px">
    <col width="45px">
    <col width="*">
    <col width="45px">
    <col width="*">
    <col width="30px">
    <col width="45px">
    <col width="*">
    <col width="45px">
    <col width="*">
  </colgroup>  -->
  <tr >
    <th rowspan="3"  width="30px" >공<br><br><br>급<br><br><br>자</th>
    <th width="40px" style="height: 48px;  ">등록번호</th>
    <td colspan="3"  width="249px" style="line-height: 22px; " ><?=$result->corp_reg_no?></td>
    <th rowspan="3"   width="30px"  style="line-height: 11px;" >공<br><br>급<br><br>받<br><br>는<br><br>자</th>
    <th width="40px" style="line-height: 12px; ">등록번호</th>
    <td colspan="3" width="249px" style="line-height: 20px;"><?=$result->order_corp_reg_no?></td>
  </tr>
  <tr>
    <th style="height: 30px;line-height: 4px; "><br><br><br><br>상호</th>
    <td width="126px"><?=$result->corp_name?></td>
    <th width="40px" style="height: 30px;line-height: 4px; "><br><br><br><br>성명</th>
    <td><?=$result->corp_ceo?></td>

    <th style="height: 30px;line-height: 4px; "><br><br><br><br>상호</th>
    <td width="126px"><?=$result->order_corp_name?></td>
    <th width="40px" style="height: 30px;line-height: 4px; "><br><br><br><br>성명</th>
    <td><?=$result->order_name?></td>
  </tr>
  <tr>
    <th style="height: 30px;line-height: 4px; "><br><br><br><br>주소</th>
    <td colspan="3"><?=$result->corp_addr?></td>
    <th style="height: 30px;line-height: 4px; "><br><br><br><br>주소</th>
    <td colspan="3"><?=$result->order_addr?></td> 
  </tr>
</table>

<table class="tbl_3 " style="border-spacing: 0;">
  <tr >
    <th style="font-size:12px; width:200px; line-height: 5px; "><br><br><br>품목</th>
    <th style="font-size:12px; width:80px;line-height: 5px; "><br><br><br>수량</th>
    <th style="font-size:12px; width:100px;line-height: 5px; "><br><br><br>규격</th>
    <th style="font-size:12px; width:80px;line-height: 5px; "><br><br><br>단가</th>
    <th style="font-size:12px; width:80px; line-height: 4px;"><br><br>금액<br><br><br>(vat 포함)</th>
    <th style="font-size:12px; width:98px;line-height: 5px; "><br><br><br>비고</th>
  </tr>
  <?

  for($i=0;$i<count($data_array);$i++){
  ?>
  <tr>

    <td><?=$data_array[$i]['product_name']?></td>
    <td><?=$data_array[$i]['product_ea']?></td>
    <td><?=$data_array[$i]['product_standard']?></td>
    <td><?=number_format($data_array[$i]['product_price'])?></td>
    <td><?=number_format($data_array[$i]['tot_product_price'])?></td>
    <td class="relative"><?=$data_array[$i]['etc']?></td>
  </tr>
  <?
  }
  ?> 

</table>

<table class="tbl_3">
  <tr>
    <th class="txt_left"  style="width:130px; line-height: 5px; "><br><br>공급가액<br><br><br> (부가세 제외)</th>
    <td style="width:189px; line-height: 5px; "><br><br><br><?=number_format($result->total*.909)?></td>
    <th class="txt_left" style="width:130px; line-height: 5px; "><br><br>공급가액<br><br><br> (부가세 제외)</th>
    <td style="width:189px; line-height: 5px; "><br><br><br><?=number_format($result->total)?></td>
  </tr>
</table> 
