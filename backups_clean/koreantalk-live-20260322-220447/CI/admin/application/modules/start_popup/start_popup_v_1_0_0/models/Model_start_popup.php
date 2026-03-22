<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author :	-
| Create-Date : 2019-07-11
| Memo : 시작 팝업 관리
|------------------------------------------------------------------------
*/

Class Model_start_popup extends MY_Model {

  //시작팝업 상세
	public function start_popup_detail(){
		
    $sql = "SELECT
							start_popup_idx,
							title,
							contents,
							state,
							img_path,
							link_url,
							start_date,
							end_date,
							ins_date,
							upd_date
	        	FROM
          		tbl_start_popup
				";

   	return	$this->query_row($sql,
                            array(
                            )
                            );
	}

  // 시작팝업 수정
	public function start_popup_mod_up($data){

		$title=$data['title'];
		$contents=$data['contents'];
		$img_path=$data['img_path'];
		$start_date=$data['start_date'];
		$end_date=$data['end_date'];
		$link_url=$data['link_url'];
		$state=$data['state'];

		$this->db->trans_begin();

		$sql = "UPDATE
              tbl_start_popup
            SET
              title = ?,
              contents = ?,
              link_url = ?,
              img_path = NULL,
              start_date = ?,
              end_date = ?,
              state = ?,
              upd_date = now()
            WHERE
              start_popup_idx = '1'

            ";

		$this->query($sql,
                array(
                $title,
                $contents,
                $link_url,
                $start_date,
                $end_date,
                $state,
                ),
                $data);

		if(empty($img_path)!=true){

		$sql = "UPDATE
              tbl_start_popup
            SET
              img_url = ?,
              upd_date = now()
            WHERE
              start_popup_idx = '1'
            ";

		$this->query($sql,
                array(
                $img_path[0]
                ),
                $data);
		}

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}

	}

}

?>
