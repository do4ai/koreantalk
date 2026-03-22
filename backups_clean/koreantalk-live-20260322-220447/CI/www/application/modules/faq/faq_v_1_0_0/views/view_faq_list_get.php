    <?php
    if(!empty($result_list)){
    foreach($result_list as $row){
    ?>
    <li>
      <p class="trigger"><?=$row->faq_category_name?></p>
      <div class="panel hide"> 
        <?=nl2br($row->contents)?>
      </div>
    </li>
    <?}?>
    <?}?>




    