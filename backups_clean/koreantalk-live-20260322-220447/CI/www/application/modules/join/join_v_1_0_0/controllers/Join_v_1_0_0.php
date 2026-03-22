<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 
| Create-Date : 2023-07-21
| Memo : 회원 가입
|------------------------------------------------------------------------

_input_check 가이드
_________________________________________________________________________________
|  !!. 변수설명
| $key       : 파라미터로 받을 변수명
| $empty_msg : 유효성검사 실패 시 전송할 메세지,
|              ("empty_msg" => "유효성검사 메세지") 로 구분하며 list 타입임.
| $focus_id  : 유효성검사 실패 시 foucus 이동 ID,
|              ("focus_id" => "foucus 대상 ID")
| $ternary  : 삼항 연산자 받을 변수명
|              ("ternary" => "1")
| $esc       : 개행문자 제거 요청시 true, 아닐시 false
|              false를 요청하는 경우-> (ex. 장문의 글 작성 시 false)
|           	 값이 array 형태일 경우 false로 적용
| $regular_msg : 정규표현식 검사 실패 시 전송할 메세지,
|              ("regular_msg" => "정규표현식 메세지","type" => "number")
| $type    	: 유효성검사할 타입
|           	 number   : 숫자검사
|            	email    : 이메일 양식 검사
|            	password : 비밀번호 양식 검사
|            	tel1     : 전화번호 양식 검사 (- 미포함)
|            	tel2     : 전화번호 양식 검사 (- 포함)
|            	custom   : 커스텀 양식, $custom의 양식으로 검사함
|            	default  : default, 검사를 안합니다.
| $custom 	: 유효성검사 custom으로 진행 시 받을 값 (정규표현식)
|
|  !!!. 값이 array형태로 들어올 경우
| $this->input_chkecu("파라미터로 받을 변수명[]");
| 형태로 받는다.
|_________________________________________________________________________________
*/

class Join_v_1_0_0 extends MY_Controller{
	function __construct(){
		parent::__construct(); 
	  $this->load->model(mapping('join').'/model_join');
	}


	// 인덱스
	public function index() {
		$this->join_reg();
	}


	// 회원가입
	public function join_reg(){
		
		$response = new stdClass();

		$response->terms_list = $this->model_join->terms_list();
		$this->_view(mapping('join').'/view_join_reg',$response);
	}


  public function terms_detail(){

    $type = $this->_input_check("type",array());

		$data['type'] = $type;

		$result=$this->model_join->terms_detail($data);//약관 상세 보기

		$response = new stdClass();

    if(empty($result)){
			$response->code = "0";
			$response->code_msg = lang('lang_10363','정보를 불러오지 못했습니다. 잠시 후 다시 시도해주세요.');

		}else{
			$response->code = "1000";
			$response->code_msg = lang('lang_10364','성공');
			$response->title = $result->title;
			$response->contents = $result->contents;
		}

		echo json_encode($response);
		exit;
  }


	// 회원가입 
	public function member_reg_in(){
		$member_id = $this->_input_check("member_id",array("empty_msg"=>lang('lang_10365','아이디를 입력해주세요.'),"regular_msg" => lang('lang_10366','아이디는 이메일 형식으로 입력해 주세요.'),"type" => "email", "focus_id"=>"member_id"));
		$member_pw = $this->_input_check("member_pw",array("empty_msg"=>lang('lang_10367','비밀번호를 입력해주세요.'), "regular_msg" => lang('lang_10368','비밀번호는 영문, 숫자 조합으로 8~15자리로 입력해 주세요.'), "type" => "custom", "custom" => "/^(?=.*[a-zA-Z])(?=.*[0-9])[A-Za-z\d~!@#$%^&*()+|=]{8,15}$/", "focus_id"=>"member_pw"));
		$member_pw_confirm	= $this->_input_check('member_pw_confirm',array("empty_msg"=>lang('lang_10369','비밀번호 확인을 입력해주세요.'),"focus_id"=>"member_pw_confirm"));
		$member_name = $this->_input_check('member_name',array("empty_msg"=>lang('lang_10370','이름을 입력해 주세요.'),"focus_id"=>"member_name"));		
		$member_nickname = $this->_input_check('member_nickname',array("empty_msg"=>lang('lang_10371','외국인 한국이름을 입력해 주세요.'),"focus_id"=>"member_nickname"));		
		$member_phone	= $this->_input_check('member_phone',array("empty_msg"=>lang('lang_10372','휴대폰 번호를 입력해주세요.'),"focus_id"=>"member_phone"));

		$response = new stdClass();

		if(strcmp($member_pw, $member_pw_confirm)) {
			$response->code = -1;
		  $response->code_msg 	= lang('lang_10373','비밀번호와 비밀번호 확인이 일치하지 않습니다. 다시 확인해 주세요.');
			echo json_encode($response);
			exit;
		}

		$data['member_id'] = $member_id;
		$data['member_pw'] = $member_pw;
		$data['member_pw_confirm'] = $member_pw_confirm;
		$data['member_name'] = $member_name;
		$data['member_nickname'] = $member_nickname;
		$data['member_phone'] = $member_phone;

		$member_email_check = $this->model_join->member_id_check($data); //사용 가능 아이디 체크

		if(!empty($member_email_check)){
			$response->code = -1;
		  $response->code_msg = lang('lang_10374','이미 가입된 아이디 입니다. 확인 후 다시 진행해 주세요.');
			echo json_encode($response);
			exit;
		}

		$result = $this->model_join->member_reg_in($data); //업체 정보 등록

		if($result == "0") {
			$response->code = "-2";
			$response->code_msg = error_code_msg('-2'); //실패 하였습니다.
		}else{
			$response->code = "1000";
			$response->code_msg = error_code_msg('1000'); // 정상적으로 처리되었습니다.;
		}

		echo json_encode($response);
		exit;
	}

	// 회원가입
	public function join_complete(){
	  $member_id = $this->_input_check('member_id',array());	
	  $member_name = $this->_input_check('member_name',array());	

  	$response = new stdClass();
		$response->member_id = $member_id;
		$response->member_name = $member_name;

		$this->_view(mapping('join').'/view_join_complete',$response);
	}

}// 클래스의 끝
?>
