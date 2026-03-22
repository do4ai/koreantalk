<?php
  $filename="회원관리_".date('Ymd');
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
      <th>No</th>
      <th>아이디</th>
      <th>이름</th>
      <th>닉네임</th>
      <th>전화번호</th>
      <th>가입경로</th>
      <th>가입일</th>
			<th>상태</th>
    </tr>
	</thead>
	<tbody>
    <?php
			if(!empty($result_list)){
    		foreach ($result_list as $row){
    ?>
					<tr>
						<td>
							<?=$no--?>
						</td>
						<td>
							<?=$row->member_id?>
						</td>
						<td>
							<?=$row->member_name?>
						</td>
            <td>
							<?=$row->member_nickname?>
						</td>
			      <td>
							<?=$this->global_function->format_phone($row->member_phone);?>
						</td>
						<td>
							<?php
								switch($row->member_join_type) {
									case "C": echo "일반"; break;
									case "K": echo "카카오톡"; break;
									case "F": echo "페이스북"; break;
									case "T": echo "트위터"; break;
									default: echo "일반"; break;
								}
							?>
						</td>
			      <td>
							<?=$this->global_function->date_YmdHi_dot($row->ins_date);?>
						</td>
			      <td>
							<?php if($row->del_yn == 'N'){ echo "이용중"; }else{ echo "회원탈퇴"; } ?>
						</td>
					</tr>
		<?php
		    }
			}
		?>
	</tbody>
</table>
