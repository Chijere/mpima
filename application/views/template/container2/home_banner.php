 
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

        <ul class="home-sidelinks">
            <li><a class="smoothscroll" href="#about">About<span>who we are</span></a></li>
            <li><a class="smoothscroll" href="#services">Services<span>what we do</span></a></li>
            <li><a  class="smoothscroll" href="#contact">Contact<span>get in touch</span></a></li>
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