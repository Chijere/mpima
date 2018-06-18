  <?php
          /*
              ####################################################
                  Define Default variable 

              ####################################################

           */

            if(!isset($page_data['pagination']))$page_data['pagination']=array();
            if(!isset($page_data['pagination']['current_page']))$page_data['pagination']['current_page']=1;
            if(!isset($page_data['pagination']['total_pages']))$page_data['pagination']['total_pages']=1;
            if(!isset($page_data['pagination']['href']))$page_data['pagination']['href']=base_url();
            if(!isset($page_data['pagination']['page_variable']))$page_data['pagination']['page_variable']='pg';

           #########################
  ?>

  <?php if(!($page_data['pagination']['total_pages']<=1)){ ?>
  <div class="row">
    <div class="col clearfix">
      <div class="listings_nav">
        <ul>
            <?php $disable=false; ?>
    <li class="listings_nav_item <?php if($page_data['pagination']['current_page']<3) {echo 'dsabl'; $disable=true;}else{$disable=false;} ?>">
                <a class="" href="<?php if(!$disable) echo $page_data['pagination']['href'].'&pg='.($page_data['pagination']['current_page']-1); else echo '#'; ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                  <span class="sr-only">Previous</span>
                </a>
    </li>            

    <?php if($page_data['pagination']['current_page']==1)$k=$page_data['pagination']['current_page']; else if($page_data['pagination']['current_page']>1)$k=($page_data['pagination']['current_page']-1);
    for( $i=$k; $i<($k+4); $i++){ ?>
    <li class="listings_nav_item <?php if($page_data['pagination']['current_page']==$i) echo 'active'; if($i>$page_data['pagination']['total_pages']){echo 'disabled'; $disable=true;}else{$disable=false;} ?>">
      <a class="" href="<?php if(!$disable) echo $page_data['pagination']['href'].'&pg='.$i;  else echo "#"; ?>"><?php echo $i; ?></a>
    </li>
    <?php $last_k=$i; } ?>

    <li class="listings_nav_item <?php if(($page_data['pagination']['total_pages']<5)||($page_data['pagination']['total_pages']<=$last_k)) {echo 'dsabl'; $disable=true;}else{$disable=false;} ?>">
        <a class="" href="<?php if(!$disable) echo $page_data['pagination']['href'].'&pg='.($last_k+1); else echo "#"; ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
    </li>  
        </ul>
      </div>
    </div>
  </div>
  <?php }?>