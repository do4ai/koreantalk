<?
  $filename="쓰레기임무관리_".date('Ymd');
	header( "Content-type: application/vnd.ms-excel; charset=utf-8" );
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
	header( "Pragma: public" );
	header( "Content-Disposition: attachment; filename=$filename.xls" );
?>

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

<table class="table table-bordered">
  <thead>
  <tr>
        <th width="50">No</th>
        <th width="130">주문번호</th>
        <th width="150">사용자 아이디</th>
        <th width="100">사용자 닉네임</th>
        <th width="100">사용자 이름</th>
        <th width="150">선택된 지원자 아이디</th>
        <th width="100">선택된 지원자 닉네임</th>
        <th width="100">제선택된 지원자 이름</th>
        <th width="*">서비스제목</th>
        <th width="100">상태</th>
        <th width="50">비용</th>
        <th width="100">요청일</th>
        <th width="100">서비스 완료일</th>
      </tr>
  </thead>
  <tbody>
    <?php
      if(!empty($result_list)){
        $no = count($result_list);
        foreach($result_list as $row){
    ?>
      <tr>
        <td>
          <?=$no--?>
        </td>
        <td>
          <?=$row->order_number?>
        </td>
        <td>
          <?=$row->order_id?>
        </td>
        <td>
          <?=$row->order_nickname?>
        </td>
        <td>
          <?=$row->order_name?>
        </td>
        <td>
          <?=$row->corp_id?>
        </td>
        <td>
          <?=$row->corp_nickname?>
        </td>
        <td>
          <?=$row->corp_name?>
        </td>
        <td>
            <?=$row->order_title?>
          </td>
        <td>
          <?=$this->global_function->get_order_state($row->order_state)?>
        </td>
        <td>
          <?=number_format($row->pay_price)?>
        </td>
        <td>
          <?=$this->global_function->date_Ymd_Hyphen($row->ins_date)?>
        </td>
        <td>
          <?=$this->global_function->date_Ymd_Hyphen($row->order_end_date)?>
        </td>
      </tr>
    <?php
        }
      }else{
    ?>
    <tr>
      <td colspan="13">
        <?=no_contents('0')?>
      </td>
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>
