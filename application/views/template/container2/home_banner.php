 
  <div class="home">
    
    <!-- Home Slider -->
    <div class="home_slider_container">
      <div class="owl-carousel owl-theme home_slider">

        <?php   foreach ($page_data['banner']['data']['records'] as $key => $value) { ?>
        <!-- Home Slider Item -->
        <div class="owl-item home_slider_item">
          <!-- Image by https://unsplash.com/@aahubs -->
          <div class="home_slider_background" style="background-image:url(<?php echo IMAGE_SRC_URL.$value['item_pic']['main']['path'].'.jpg'; ?>)"></div>
          <div class="home_slider_content_container text-center">
            <div class="home_slider_content">
              <h1 data-animation-in="flipInX" data-animation-out="animate-out fadeOut"> <?php echo $value['summary']; ?></h1>
            </div>
          </div>
        </div>
        <?php } ?>

      </div>

        <ul class="home-sidelinks col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <?php $n=0;  foreach ($page_data['service']['data']['records'] as $key => $value) { if($value['addTo_front_Banner'] && $n<2){ ?>
            <li><a  class="smoothscroll" href="<?php echo base_url()."service/".$value['item_id']; ?>"><?php echo $value['title']; ?><span><?php echo $value['item_description']; ?></span></a></li> 
          <?php $n++;}} ?>
        </ul> <!-- end home-sidelinks -->

       
       <div class="home-scroll smoothscroll">
        <a href="#chawezi" class="">
          <center>
            <div class="home-scroll__text">Scroll Down</div>
            <div class="home-scroll__icon"></div>
          </center>
        </a> <!-- end home-scroll --> 
        </div>

      <!-- Home Slider Nav -->
      <div class="home_slider_nav_left home_slider_nav d-flex flex-row align-items-center justify-content-end">
        <img src="<?php echo IMAGE_SRC_URL; ?>media/default/images/nav_left.png" alt="">
      </div>

    </div>

  </div>