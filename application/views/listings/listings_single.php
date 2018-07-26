<!DOCTYPE html>
<html lang="en">
<head>	
	<?php
    
    /* Already Loaded CSS & JS Links
    
	  ##CSS	
	    assets/vendors/bootstrap4/bootstrap.min.css
	    assets/vendors/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css
	    assets/vendors/OwlCarousel2-2.2.1/owl.carousel.css
	    assets/vendors/OwlCarousel2-2.2.1/owl.theme.default.css
	    assets/vendors/OwlCarousel2-2.2.1/animate.css
	      
      ##JS
        assets/vendors/jquery-3.2.1.min.js
        assets/vendors/bootstrap4/bootstrap.min.js
        assets/vendors/bootstrap4/popper.js
        assets/vendors/greensock/TweenMax.min.js
	    assets/vendors/greensock/TimelineMax.min.js
	    assets/vendors/scrollmagic/ScrollMagic.min.js
	    assets/vendors/greensock/animation.gsap.min.js
	    assets/vendors/greensock/ScrollToPlugin.min.js
	    assets/vendors/OwlCarousel2-2.2.1/owl.carousel.js
	    assets/vendors/scrollTo/jquery.scrollTo.min.js
	    assets/vendors/easing/easing.js
     */

    $page_data['page_title']='Mpima Listsings';
  	$page_data['css_links']=array( 'assets/css/listings_single_styles.css',
                                   'assets/css/listings_single_responsive.css',
                                   'assets/vendors/lightslider-master/dist/css/lightslider.min.css',
                                   'assets/css/magnific-popup/magnific-popup.css',
                                   'assets/vendors/PhotoSwipe-master/dist/photoswipe.css',
                                   'assets/vendors/PhotoSwipe-master/dist/default-skin/default-skin.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/listings_single_custom.js',
      								'assets/vendors/magnific-popup/jquery.magnific-popup.min.js',
      								'assets/vendors/lightslider-master/dist/js/lightslider.min.js',
      								'assets/vendors/PhotoSwipe-master/dist/photoswipe.min.js',
      								'assets/vendors/PhotoSwipe-master/dist/photoswipe-ui-default.min.js',
      							);
	$this->load->view("template/container2/header",$page_data);
	?>

</head>

<body>

<div class="super_container">
	
	<!-- Header -->
	<?php
	 $this->load->view("template/container2/nav_bar",$page_data);
	?>
	<!-- Home -->
	<div class="home" style="height: 300px;">
		<!-- Image by: https://unsplash.com/@jbriscoe -->
		<div class="home_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/listings_single.jpg)"></div>
		
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="home_content">
						<div class="home_title">
							<h2>single listings</h2>
						</div>
						<div class="breadcrumbs">
							<span><a href="<?php echo base_url(); ?>">Home</a></span>
							<span><a href="#"> Search</a></span>
							<span><a href="<?php echo base_url(); ?>listings"> Listings</a></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Listing -->

	<div class="listing" style="padding-top: 50px">
		<div class="container">
			<div class="row">
				<div class="col-lg-7">
					
					<!-- Listing Title -->
					<div class="listing_title_container">
						<div class="listing_title"><?php echo $page_data['item']['data']['records'][0]['item_name']; ?></div>
						<p class="listing_text"><?php echo $page_data['item']['data']['records'][0]['summary']; ?></p>
					</div>
				</div>
				<div class="col-lg-4 listing_price_col clearfix">
					<div class="featured_card_box d-flex flex-row align-items-center trans_300 float-lg-left">
						<img src="<?php echo IMAGE_SRC_URL;?>media/default/images/tag.svg" alt="https://www.flaticon.com/authors/lucy-g">
						<div class="featured_card_box_content">
							<div class="featured_card_price_title trans_300">For Sale</div>
							<div class="featured_card_price trans_300"><?php echo $page_data['item']['data']['records'][0]['price']; ?></div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col">		
					<!-- Listing Image Slider -->
							    
			            <div class="clearfix listing_video" style="max-width:474px;">
			            	<div class="listing_video_link" style="margin-top: 10px">
				                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
				  						<?php foreach($page_data['item']['data']['records'][0]['item_pic'] as $key =>$value): ?>
									      <li  data-size="<?php echo $value['dimension']; ?>" data-thumb="<?php echo IMAGE_SRC_URL.$value['path'].'_t.jpg'; ?>" data-src="<?php echo IMAGE_SRC_URL.$value['path'].'.jpg'; ?>" >
									        <img src="<?php echo IMAGE_SRC_URL.$value['path'].'.jpg'; ?>" />
									      </li>
										<?php endforeach ?>
				                </ul>
			            	</div>
			            </div>

				</div>
			</div>

			<div class="row listing_content_row" style="padding-top: 0">
				
				<!-- Listing -->
				<div class="col-lg-8 listing_col">

					<!-- Listing Additional Details -->
					<div class="listing_additional_details">
						<div class="listing_subtitle">Details</div>
						<ul class="additional_details_list">
							<li class="additional_detail"><span>Number of Bedrooms:</span> 3</li>
							<li class="additional_detail"><span>Area:</span> 7100 sq ft</li>
						</ul>
					</div>
					
					<!-- Listing Description -->
					<div class="listing_description">
						<div class="listing_subtitle">Description</div>
						<p class="listing_description_text"><?php echo $page_data['item']['data']['records'][0]['item_description']; ?></p>
					</div>	
				</div>

			</div>
		</div>
	</div>

	<!-- Newsletter -->

	<div class="newsletter">
		<div class="container">
			<div class="row row-equal-height">

				<div class="col-lg-6">
					<div class="newsletter_title">
						<h3>Say Hello</h3>
						<span class="newsletter_subtitle">Contact us</span>
					</div>
					<div class="newsletter_form_container">
						<form action="#">
							<div class="newsletter_form_content d-flex flex-row">
								<input id="newsletter_email" class="newsletter_email" type="email" placeholder="Your email here" required="required" data-error="Valid email is required.">
								<button id="newsletter_submit" type="submit" class="newsletter_submit_btn trans_200" value="Submit">submit</button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Footer -->
	<?php
	 $this->load->view("template/container2/footer",$page_data);
	?>

</div>

<!-------- PhotoSwipe Plugin ------------>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
 
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
 
        <div class="pswp__ui pswp__ui--hidden" style="position: static;">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--share" title="Share"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div> 
</div>


</body>

</html>