<div class="body  row"> 
  <div class="inner_wrap">
    <h2 class="sub_title relative"><?=lang('lang_10111','FAQ')?></h2>
    <ul class="faq_list accordion">
      <?php    
      foreach($result_list as $row){
      ?>
      <li>
        <p class="trigger"><?=$row->title?></p>
        <div class="panel hide"> 
        <?=nl2br($row->contents)?>
        </div>
      </li>
      <?}?>
    </ul>
  </div>
</div>
