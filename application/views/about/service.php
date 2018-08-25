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

    $page_data['page_title']='Mpima Services';
  	$page_data['css_links']=array( 'assets/css/service_styles.css',
                                   'assets/css/service_responsive.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/service_custom.js',
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
							<h2>Our services</h2>
						</div>
						<div class="breadcrumbs">
							<span><a href="index.html">Home</a></span>
							<span><a href="#"> Services</a></span>
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
	            <div class="card-item panel col-sm-5 ">
	                <div class="card-head panel-heading">Build a better Malawi with high quality standards and designs at cheap rates<span class="pull-right"></spant></span></div>
	                <div class="card-body panel-body">Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero. Its words and letters have been changed by addition or removal, so to deliberately render its content nonsensical; it's not genuine, correct, or comprehensible Latin anymore. </div>
	                <div class="ln_solid"></div>
	                <div class="card-foot panel-footer">
	                	<a href="<?php echo base_url().'service/3'; ?>" class="btn btn-basic">Read more</a>
	                	<a href="#" class="btn btn-basic">Download Brochure</a>
	                </div>
	            </div>
	            <div class="card-item panel col-sm-5 ">
	                <div class="card-head panel-heading">Build a better Malawi with high quality standards and designs at cheap rates<span class="pull-right"></spant></span></div>
	                <div class="card-body panel-body">Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero. Its words and letters have been changed by addition or removal, so to deliberately render its content nonsensical; it's not genuine, correct, or comprehensible Latin anymore. </div>
	                <div class="ln_solid"></div>
	                <div class="card-foot panel-footer">
	                	<a href="<?php echo base_url().'service/3'; ?>" class="btn btn-basic">Read more</a>
	                	<a href="#" class="btn btn-basic">Download Brochure</a>
	                </div>
	            </div>
	            <div class="card-item panel col-sm-5 ">
	                <div class="card-head panel-heading">Build a better Malawi with high quality standards and designs at cheap rates<span class="pull-right"></spant></span></div>
	                <div class="card-body panel-body">Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero. Its words and letters have been changed by addition or removal, so to deliberately render its content nonsensical; it's not genuine, correct, or comprehensible Latin anymore. </div>
	                <div class="ln_solid"></div>
	                <div class="card-foot panel-footer">
	                	<a href="<?php echo base_url().'service/3'; ?>" class="btn btn-basic">Read more</a>
	                	<a href="#" class="btn btn-basic">Download Brochure</a>
	                </div>
	            </div>
	            <div class="card-item panel col-sm-5 ">
	                <div class="card-head panel-heading">Build a better Malawi with high quality standards and designs at cheap rates<span class="pull-right"></spant></span></div>
	                <div class="card-body panel-body">Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero. Its words and letters have been changed by addition or removal, so to deliberately render its content nonsensical; it's not genuine, correct, or comprehensible Latin anymore. </div>
	                <div class="ln_solid"></div>
	                <div class="card-foot panel-footer">
	                	<a href="<?php echo base_url().'service/3'; ?>" class="btn btn-basic">Read more</a>
	                	<a href="#" class="btn btn-basic">Download Brochure</a>
	                </div>
	            </div>
	            <div class="card-item panel col-sm-5 ">
	                <div class="card-head panel-heading">Build a better Malawi with high quality standards and designs at cheap rates<span class="pull-right"></spant></span></div>
	                <div class="card-body panel-body">Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero. Its words and letters have been changed by addition or removal, so to deliberately render its content nonsensical; it's not genuine, correct, or comprehensible Latin anymore. </div>
	                <div class="ln_solid"></div>
	                <div class="card-foot panel-footer">
	                	<a href="<?php echo base_url().'service/3'; ?>" class="btn btn-basic">Read more</a>
	                	<a href="#" class="btn btn-basic">Download Brochure</a>
	                </div>
	            </div>
	            <div class="card-item panel col-sm-5 ">
	                <div class="card-head panel-heading">Build a better Malawi with high quality standards and designs at cheap rates<span class="pull-right"></spant></span></div>
	                <div class="card-body panel-body">Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero. Its words and letters have been changed by addition or removal, so to deliberately render its content nonsensical; it's not genuine, correct, or comprehensible Latin anymore. </div>
	                <div class="ln_solid"></div>
	                <div class="card-foot panel-footer">
	                	<a href="<?php echo base_url().'service/3'; ?>" class="btn btn-basic">Read more</a>
	                	<a href="#" class="btn btn-basic">Download Brochure</a>
	                </div>
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