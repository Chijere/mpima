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

    $page_data['page_title']='Mpima About';
  	$page_data['css_links']=array( 'assets/css/about_styles.css',
                                   'assets/css/about_responsive.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/about_custom.js',
      								'assets/vendors/parallax-js-master/parallax.min.js',
      							);
	$this->load->view("template/container2/header",$page_data);
	?>

</head>
<body>

<div class="super_container">
	
	<!-- Home -->
	<div class="home" style="height: 350px;">
		<!-- Image by: https://unsplash.com/@jbriscoe -->
		<div class="home_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/news.jpg)"></div>
		
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="home_content">
						<div class="home_title">
							<h2>Our Services</h2>
						</div>
						<div class="breadcrumbs">
							<span><a href="<?php echo base_url(); ?>">Home</a></span>
							<span><a href="#"> Service</a></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Header -->
	<?php
	 $this->load->view("template/container2/nav_bar",$page_data);
	?>
	
	<!-- Intro -->

	<div class="intro">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 order-lg-1 order-2">
					<h3 class="intro_title"><?php echo $page_data['item']['data']['records'][0]['title']; ?></h3>
					<p class="intro_text"><?php echo $page_data['item']['data']['records'][0]['item_description']; ?></p>
					
				</div>
			</div>
		</div>
	</div>

	<!-- Credits -->
	<?php
	 $this->load->view("template/container2/credits",$page_data);
	?>

</div>

</body>

</html>