<!DOCTYPE html>
<html lang="en">

<head>	
	<?php
     
    $page_data['page_title']='Mpima';
  	$page_data['css_links']=array( 'assets/css/main_styles.css',
                                   'assets/css/responsive.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/home_custom.js'
      							);
	$this->load->view("template/container2/header",$page_data);
	?>

</head>


<body>

<div class="super_container">
	
	<!-- Home -->
	<?php 
	 $this->load->view("template/container2/home_banner",$page_data);
	?>
	<!-- Header -->
	<?php
	 $this->load->view("template/container2/nav_bar",$page_data);
	?>

	<!-- Featured Properties -->

	<div class="featured">
		<div class="container">
			<div id="chawezi" class="row">
				<div class="col">
					<div class="section_title text-center">
						<a href="<?php echo base_url(); ?>listings" style=" h3:hover color:#fd5524; span:hover {color:#fd5524;}
						">
						<h3>featured properties</h3>
						<span class="section_subtitle">See more</span></a>
					</div>
				</div>
			</div>

			<div class="row featured_row">
	            <?php   foreach ($page_data['item']['data']['records'] as $key => $value) { ?>
					<div class="col-sm-3 featured_card_col">						
							<div class="featured_card_container">
								<div class="card featured_card trans_300">
									<a href="<?php echo base_url().'listings_single/'.$value['item_id']; ?>">
									<img class="card-img-top" src="<?php echo IMAGE_SRC_URL.$value['item_pic']['main']['path'].'_m.jpg'; ?>" alt="property">
									</a>
									<div class="card-body">
										<div class="card-title"><a href="<?php echo base_url().'listings_single/'.$value['item_id']; ?>"><?php echo $value['item_name']; ?></a></div>
										<div class="card-text"><?php echo $value['summary']; ?></div>
										<div class="rooms">

											<div class="room">
												<span class="room_title">Bedrooms</span>
												<div class="room_content">
													<div class="room_image"><img src="<?php echo IMAGE_SRC_URL;?>media/default/images/bedroom.png" alt=""></div>
													<span class="room_number">4</span>
												</div>
											</div>

											<div class="room">
												<span class="room_title">Area</span>
												<div class="room_content">
													<div class="room_image"><img src="<?php echo IMAGE_SRC_URL;?>media/default/images/area.png" alt=""></div>
													<span class="room_number">23x15 Sq M</span>
												</div>
											</div>
										</div>

									</div>

								</div>

								<div class="featured_card_box d-flex flex-row align-items-center trans_300">
									<a href="<?php echo base_url().'listings_single/'.$value['item_id']; ?>">
									<img src="<?php echo IMAGE_SRC_URL;?>media/default/images/tag.svg" alt="https://www.flaticon.com/authors/lucy-g">
									<div class="featured_card_box_content">
										<div class="featured_card_price_title">For Sale</div>
										<div class="featured_card_price"><?php echo $value['price']; ?></div>
									</div>
								   </a>
								</div>

							</div>
					    

					</div>
				<?php } ?>

			</div>
		</div>
	</div>


	<!-- Workflow -->

	<div class="workflow">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="section_title text-center">
						<a href="<?php echo base_url(); ?>about" style=" h3:hover color:#fd5524; span:hover {color:#fd5524;}
						">
						<h3>See Our Services</h3>
						<span class="section_subtitle">What you need to do and know</span>
					</a>
					</div>
				</div>
			</div>

			<div class="row workflow_row" style="padding-top: 40px">

				<!-- Workflow Item -->
				<div class="col-lg-4 workflow_col">
					<div class="workflow_item">
						<div class="workflow_image_container d-flex flex-column align-items-center justify-content-center">
							
							<div class="workflow_image">
								<img src="<?php echo IMAGE_SRC_URL;?>media/default/images/workflow_1.png" alt="">
							</div>
							
						</div>
						<div style="padding-top: 20px"  class="workflow_item_content text-center">
							<div class="workflow_title">Choose a Location</div>
							<p class="workflow_text">Then tell us whats in your imagination for a house design</p>
						</div>
					</div>
				</div>

				<!-- Workflow Item -->
				<div class="col-lg-4 workflow_col">
					<div class="workflow_item">
						<div class="workflow_image_container d-flex flex-column align-items-center justify-content-center">
							<div class="workflow_image">
								<img src="<?php echo IMAGE_SRC_URL;?>media/default/images/workflow_2.png" alt="">
							</div>
							
						</div>
						<div style="padding-top: 20px"  class="workflow_item_content text-center">
							<div class="workflow_title">We do the design sketches</div>
							<p class="workflow_text">we do the house designs and land valuations and site survey</p>
						</div>
					</div>
				</div>

				<!-- Workflow Item -->
				<div class="col-lg-4 workflow_col">
					<div class="workflow_item">
						<div class="workflow_image_container d-flex flex-column align-items-center justify-content-center">
							<div class="workflow_image">
								<img src="<?php echo IMAGE_SRC_URL;?>media/default/images/workflow_3.png" alt="">
							</div>
							
						</div>
						<div style="padding-top: 20px"  class="workflow_item_content text-center">
							<div class="workflow_title">commision the design</div>
							<p class="workflow_text">commision all the designs and paper work for a cheap cost</p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>



	<!-- Call to Action -->

	<div class="cta_1">
		<div class="cta_1_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/cta_1.jpg)"></div>
		<div class="container">
			<div class="row">
				<div class="col">
					
					<div class="cta_1_content d-flex flex-lg-row flex-column align-items-center justify-content-start">
						<h3 class="cta_1_text text-lg-left text-center">Do you want to talk with one of our <span>company experts, Urgently?</span></h3>
						<div class="cta_1_phone">Call now:   +265991843315</div>
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


            <!-- custom scripts-->
      <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>

</body>

</html>