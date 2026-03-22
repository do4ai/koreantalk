<?php defined('BASEPATH') OR exit('No direct script access allowed');

  $seg1 = $this->uri->segment(1);

  //언어 추가시 수정필요  
  $lang_check = array(
      "kr",        
      "us",
  );

  if(!in_array($this->uri->segment(1), $lang_check) || empty($seg1) ){
    if(!empty($this->current_lang)){
      redirect("/".$this->current_lang."/home");
      exit;  
    }else{
      redirect("/kr/home");
      exit;
    }  
  } 
 
  #####################  기본 언어 세팅 ######################
  //언어 추가시 수정필요  
  switch($seg1) {
      case "kr" : $lang = "KOR";$this->current_lang = "kr"; break;            
      case "us" : $lang = "ENG";$this->current_lang = "us"; break;
      default : $lang = "KOR";$this->current_lang = "kr"; break;
  }

  set_cookie('current_lang', $this->current_lang, 3600*24*365);
  $_SESSION['current_lang'] = $this->current_lang;

  $this->load->helper("language");
  $this->lang->load("all", $lang);
