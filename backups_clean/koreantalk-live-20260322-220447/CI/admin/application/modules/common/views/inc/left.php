<div id="wrapper">

  <!-- Navigation : s-->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"><?=SERVICE_NAME;?> 중앙관리시스템</a>
    </div>

    <ul class="nav navbar-right top-nav" style="margin-right:20px">
      <li class="dropdown"><a href="/admin_password/pw_mod"><i class="fa fa-cog"></i> 비밀번호변경</a></li>
      <li class="dropdown"><a href="/logout"><i class="fa fa-power-off"></i> 로그아웃</a></li>
    </ul>

    <div class="collapse navbar-collapse navbar-ex1-collapse">

      <!-- side-nav : s -->
      <ul class="nav navbar-nav side-nav">

        <li class="side_nav_top">
          <p><strong><?=$this->admin_name?></strong> <span>님 환영합니다.</span></p>
          <p><span>ID : <?=$this->admin_id?></span></p>
        </li>

        <!-- 홈 -->
        <!-- <li <?php if($this->uri->segment(1)==mapping('main')){ echo "class='active';";}?>>
          <a href="/<?=mapping('main')?>">
            <i class="fa fa-genderless"></i>
            <span>홈</span>
          </a>
        </li> -->

        <!-- 회원관리 -->
        <li class="<?php if($this->uri->segment(1)==mapping('member')) echo "active";?>">
          <a href="/<?=mapping('member')?>">
            <i class="fa fa-genderless"></i>
            <span>회원관리</span>
          </a>
        </li>

        <!-- 카테고리관리 -->
        <!-- <li class="<?php if($this->uri->segment(1)==mapping('category_management')) echo "active";?>">
          <a href="/<?=mapping('category_management')?>">
            <i class="fa fa-genderless"></i>
            <span>카테고리관리</span>
          </a>
        </li> -->
      
          <!-- 사이트관리 -->
        <?
        if($this->admin_idx =="1"){
        ?>   
        <!-- <li class="<?php if($this->uri->segment(1)==mapping('site')) echo "active";?>">
          <a href="/<?=mapping('site')?>">
            <i class="fa fa-genderless"></i>
            <span>사이트 관리</span>
          </a>
        </li> -->
        <?}?>
         <!-- 상품관리 -->
        <?
        if($this->site_code =="kr" || $this->admin_grade =="A"  ){
        ?>    
        <li class="<?php if($this->uri->segment(1)==mapping('product')) echo "active";?>">
          <a href="/<?=mapping('product')?>">
            <i class="fa fa-genderless"></i>
            <span>상품 관리</span>
          </a>
        </li>
        <?}?>

        <!-- 전차책관리 -->
        <li class="<?php if($this->uri->segment(1)==mapping('electron_book')) echo "active";?>">
          <a href="/<?=mapping('electron_book')?>">
            <i class="fa fa-genderless"></i>
            <span>전차책 관리</span>
          </a>
        </li>
        
        <!-- 전차책 구매 관리 -->
        <li class="<?php if($this->uri->segment(1)==mapping('order')) echo "active";?>">
          <a href="/<?=mapping('order')?>">
            <i class="fa fa-genderless"></i>
            <span>전자책 구매 관리</span>
          </a>
        </li>

         <!-- 교육영상관리 -->
        <li class="<?php if($this->uri->segment(1)==mapping('lecture')) echo "active";?>">
          <a href="/<?=mapping('lecture')?>">
            <i class="fa fa-genderless"></i>
            <span>TOPIK 영상 관리</span>
          </a>
        </li>


      
   
        <!-- 커뮤니티 -->
        <li class="<?php if( $this->uri->segment(1)==mapping('board') || $this->uri->segment(1)==mapping('board_notice') || $this->uri->segment(1)==mapping('board_category')) echo "active";?>">
          <a href="#" data-toggle="collapse" data-target="#admin_community">
            <i class="fa fa-genderless"></i>
            <span>게시판 관리</span> &nbsp;<i class="fa fa-caret-down"></i>
          </a>
          <ul id="admin_community" class="collapse <?php if( $this->uri->segment(1)==mapping('board') || $this->uri->segment(1)==mapping('board_notice') ||  $this->uri->segment(1)==mapping('board_category') ){ echo "in";}?>" aria-expanded="true">
            <?
            if($this->admin_idx =="1"){
            ?>  
            <!-- <li><a href="/<?=mapping('board_category')?>/" class="<?php if($this->uri->segment(1)==mapping('board_category') ) echo "active";?>" id="board_detail"> 카테고리  관리</a></li> -->
            <?}?>
            <li><a href="/<?=mapping('board_notice')?>" class="<?php if($this->uri->segment(1)==mapping('board_notice') ) echo "active";?>">커뮤니티 공지 관리</a></li>
            <?foreach($board_category_list as $row){?>
              <li><a href="/<?=mapping('board')?>/?category=<?=$row->board_category_idx?>" class="<?php if($this->uri->segment(1)==mapping('board') ) echo "active";?>"><?=$row->category_name?> 관리</a></li>
            <?}?>
          </ul>
        </li>

        <!-- QA -->
        <li class="<?php if($this->uri->segment(1)==mapping('notice')  ||  $this->uri->segment(1)==mapping('faq') ||  $this->uri->segment(1)==mapping('qa') ) echo "active";?>">
          <a href="#" data-toggle="collapse" data-target="#admin_cscenter">
            <i class="fa fa-genderless"></i>
            <span>고객센터</span> &nbsp;<i class="fa fa-caret-down"></i>
          </a>
          <ul id="admin_cscenter" class="collapse <?php if( $this->uri->segment(1)==mapping('notice')  ||  $this->uri->segment(1)==mapping('faq') ||  $this->uri->segment(1)==mapping('qa')) echo "in";?>" aria-expanded="true">
            <li><a href="/<?=mapping('notice');?>">공지사항 관리</a></li>
            <li><a href="/<?=mapping('faq');?>">FAQ 관리</a></li>
            <li><a href="/<?=mapping('qa');?>">1:1문의 관리</a></li>
          </ul>
        </li>


        <!-- 교육영상관리 -->
        <li class="<?php if($this->uri->segment(1)==mapping('banner')) echo "active";?>">
          <a href="/<?=mapping('banner')?>">
            <i class="fa fa-genderless"></i>
            <span>배너 관리</span>
          </a>
        </li>

        <!-- 교육영상관리 -->
        <li class="<?php if($this->uri->segment(1)==mapping('terms')) echo "active";?>">
          <a href="/<?=mapping('terms')?>">
            <i class="fa fa-genderless"></i>
            <span>약관 관리</span>
          </a>
        </li>

          <!-- 관리자  -->
        <?
        if($this->admin_grade =="A"){
        ?>  
        <li class="<?php if($this->uri->segment(1)==mapping('suboperator')) echo "active";?>">
          <a href="/<?=mapping('suboperator')?>">
            <i class="fa fa-genderless"></i>
            <span>관리자 관리</span>
          </a>
        </li>
        <?}?> 



     
      </ul>
      <!-- side-nav : e -->

    </div>
  </nav>
  <!-- Navigation : e -->

  <div id="page-wrapper">
