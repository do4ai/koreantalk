<div class="row table_title">
	<div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과 : <strong><?=number_format($result_list_count)?></strong> 건</div>
	<div class="col-lg-6 text-right"> &nbsp;  <a href="/<?=mapping('suboperator')?>/suboperator_reg" class="btn btn-success">등록</a></div>
</div>

<table class="table table-bordered">
	<thead>
		<tr>
			<th width="100">No</th>		
			<th width="120">관리자 ID</th>
			<th width="120">관리자명</th>
			<th width="120">전화번호</th>
			<th width="120">사이트권한</th>
			<th width="180">등록일</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = count($result_list);
			foreach ($result_list as $row) {?>
			<tr>
				<td><?=$no--?></td>
				<td><a href="/<?=mapping('suboperator')?>/suboperator_mod?admin_idx=<?=$row->admin_idx?>"><?=$row->admin_id?></a></td>
				<td><?=$row->admin_name?></td>
				<td><?=$row->admin_phone?></td>
				<td><?=$row->site_name?></td>
				<td><?=$this->global_function->dateYmdHyphen($row->ins_date)?></td>
			</tr>
		<?php }?>
	</tbody>
</table>

<?=$paging?>
