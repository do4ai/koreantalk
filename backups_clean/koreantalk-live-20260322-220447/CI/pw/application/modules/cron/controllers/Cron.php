<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author : 김옥훈
| Create-Date : 2018-02-17
| Memo : cron
|------------------------------------------------------------------------
*/
class Cron extends MY_Controller {

  function __construct(){
    parent::__construct();

    $this->load->model('cron/model_cron');

  }

	//매일자정시(108)
	public function corp_profile_alarm(){
    $this->model_cron->corp_profile_alarm();
	}

  //자동취소(1분마다)
  public function auto_cancel(){
    $this->model_cron->auto_cancel();
  }

  //5초마다 실행
  /*
  * * * * * /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 5; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 10; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 15; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 20; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 25; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 30; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 35; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 40; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 45; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 50; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  * * * * * sleep 55; /usr/bin/curl --silent --compressed p.pw.rocateerdev.co.kr/cron/alarm_send
  */
  public function alarm_send(){
    $this->model_cron->alarm_send();
  }

  // 이메일 발송
  public function email_send(){
    $this->model_cron->email_send();
  }


}
