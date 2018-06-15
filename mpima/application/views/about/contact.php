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
  	$page_data['css_links']=array( 'assets/css/contact_styles.css',
                                   'assets/css/contact_responsive.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/contact_custom.js',
      							);
	$this->load->view("template/container2/header",$page_data);
	?>

</head>

<body>

<div class="super_container">
	
	<!-- Home -->
	<div class="home">
		<!-- Image by: https://unsplash.com/@breather -->
		<div class="home_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/contact.jpg)"></div>
		
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="home_content">
						<div class="home_title">
							<h2>single listings</h2>
						</div>
						<div class="breadcrumbs">
							<span><a href="index.html">Home</a></span>
							<span><a href="#"> Contact</a></span>
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

	<!-- Contact -->

	<div class="contact">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-6 contact_col">
					<div class="estate_contact_form">
						<div class="contact_title">say hello</div>
						<div class="estate_contact_form_container">
							<form id="estate_contact_form" class="estate_contact_form" action="post">
								<input id="estate_contact_form_name" class="estate_input_field estate_contact_form_name" type="text" placeholder="Name" required="required" data-error="Name is required.">
								<input id="estate_contact_form_email" class="estate_input_field estate_contact_form_email" type="email" placeholder="E-mail" required="required" data-error="Valid email is required.">
								<input id="estate_contact_form_subject" class="estate_input_field estate_contact_form_subject" type="email" placeholder="Subject" required="required" data-error="Subject is required.">
								<textarea id="estate_contact_form_message" class="estate_text_field estate_contact_form_message" name="message" placeholder="Message" required="required" data-error="Please, write us a message."></textarea>
								<button id="estate_contact_send_btn" type="submit" class="estate_contact_send_btn trans_200" value="Submit">send</button>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-3 contact_col">
					<div class="contact_title">contact info</div>
					<ul class="contact_info_list estate_contact">
						<li class="contact_info_item d-flex flex-row">
							<div><div class="contact_info_icon"><img src="images/placeholder.svg" alt=""></div></div>
							<div class="contact_info_text">Near Ginnery Conour, opposite NBS Bank</div>
						</li>
						<li class="contact_info_item d-flex flex-row">
							<div><div class="contact_info_icon"><img src="images/phone-call.svg" alt=""></div></div>
							<div class="contact_info_text">+265991843315</div>
						</li>
						<li class="contact_info_item d-flex flex-row">
							<div><div class="contact_info_icon"><img src="images/message.svg" alt=""></div></div>
							<div class="contact_info_text"><a href="mailto:contactme@gmail.com?Subject=Hello" target="_top">mpimainvest@info.com</a></div>
						</li>
						<li class="contact_info_item d-flex flex-row">
							<div><div class="contact_info_icon"><img src="images/planet-earth.svg" alt=""></div></div>
							<div class="contact_info_text"><a href="https://colorlib.com">www.mpico.com</a></div>
						</li>
					</ul>
					<div class="estate_social">
						<ul class="estate_social_list">
							<li class="estate_social_item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
							<li class="estate_social_item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
							<li class="estate_social_item"><a href="#"><i class="fab fa-twitter"></i></a></li>
							<li class="estate_social_item"><a href="#"><i class="fab fa-dribbble"></i></a></li>
							<li class="estate_social_item"><a href="#"><i class="fab fa-behance"></i></a></li>
						</ul>
					</div>
				</div>

				<div class="col-lg-3 contact_col">
					<div class="contact_title">about</div>
					<div class="estate_about_text">
						<p>MPICO Investments designs modern building designs, house plans, monitor, control, operate oversee and account for property, survey sites and establishes the market value of urban and rural area lands</p>
						<p>Build a better Malawi with high quality standards and designs at cheap rates</p>
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