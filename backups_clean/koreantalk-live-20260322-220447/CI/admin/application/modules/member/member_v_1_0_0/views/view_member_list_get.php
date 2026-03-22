<div class="row table_title">
	<div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=$result_list_count?></strong>  건</div>
	<p class="col-lg-6 text-right">
  <!-- <a href="javascript:void(0);" class="btn btn-primary" onclick="do_excel_down();">엑셀 다운로드</a> -->
  </p>
</div>

<table class="table table-bordered">
	<thead>
    <tr>
      <th width="50">No</th>
      <th width="*">아이디</th>
			<th width="100">국적</th>
      <th width="100">이름</th>
      <th width="150">외국어 한글이름</th>
      <th width="120">휴대폰번호</th>
			<th width="100">회원 상태</th>
      <th width="150">가입일</th>
    </tr>
	</thead>
	<tbody>
    <?php
			if(!empty($result_list)){
    		foreach ($result_list as $row){
         switch($row->del_yn){
          case "N" : $del_yn ='이용중';break; 
          case "Y" : $del_yn ='탈퇴';break; 
          case "P" : $del_yn ='이용정지';break; 
          default : $del_yn ='';break; 
         
         }
    ?>
					<tr>
						<td>
							<?=$no--?>
						</td>
						<td>
							<a href="/<?=mapping('member')?>/member_detail?member_idx=<?=$row->member_idx?>&history_data=<?=$history_data?>"><?=$row->member_id?></a>
						</td>
						<td><?=$row->site_name?></td>
						<td><?=$row->member_name?></td>
						<td><?=$row->member_nickname?></td>
		
			      <td>
							<?=$this->global_function->format_phone($row->member_phone);?>
						</td>
            <td>
							<?=$del_yn?>
						</td>
			      <td>
							<?=$this->global_function->date_YmdHi_dot($row->ins_date);?>
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
		<?php
			}
	  ?>
	</tbody>
</table>

<?=$paging?>
