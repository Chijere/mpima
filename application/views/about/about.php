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
		<div class="home_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/home_background.jpg)"></div>
		
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="home_content">
						<div class="home_title">
							<h2>about us</h2>
						</div>
						<div class="breadcrumbs">
							<span><a href="index.html">Home</a></span>
							<span><a href="#"> About Us</a></span>
							<span><a href="#"> Our Agents</a></span>
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
					<h2 class="intro_title">MPIMA Investments</h2>
					<div class="intro_subtitle">Build a better Malawi with high quality standards and designs at cheap rates</div>
					<p class="intro_text">MPIMA Investments designs modern building designs, house plans, monitor, control, operate oversee and account for property, survey sites and establishes the market value of urban and rural area lands</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Agents -->

	<div class="agents">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="section_title text-center">
						<h3>our team</h3>
						<span class="section_subtitle">The best out there</span>
					</div>
				</div>
			</div>

			<div class="row agents_row">
				
		        <?php 
                  foreach ($page_data['item']['data']['records'] as $key => $value) {
                ?>
				<!-- Agent -->
				<div class="col-lg-3 agent_col text-center">
					<div class="agent_image mx-auto">
						<img src="<?php echo IMAGE_SRC_URL.$value['item_pic']['main']['path'].'_t.jpg'; ?>" alt="chipala 2016">
					</div>
					<div class="agent_content">
						<div class="agent_name"><?php echo $value['item_name']; ?></div>
						<div class="agent_role"><?php echo $value['title']; ?></div>
						<div class="agent_social">
							<ul class="agent_social_list">
								<li class="agent_social_item"><a href="<?php echo $value['link_linkedin']; ?>"><i class="fab fa-pinterest"></i></a></li>
								<li class="agent_social_item"><a href="<?php echo $value['link_facebook']; ?>"><i class="fab fa-facebook-f"></i></a></li>
								<li class="agent_social_item"><a href="<?php echo $value['link_twitter']; ?>"><i class="fab fa-twitter"></i></a></li>
							</ul>
						</div>
					</div>
				</div> 
				<?php } ?>
				
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