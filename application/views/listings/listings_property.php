<!DOCTYPE html>
<html lang="en">

<head>	
	<?php
     
    $page_data['page_title']='Mpima Properties for Sale';
  	$page_data['css_links']=array( 'assets/css/listings_styles.css',
                                   'assets/css/listings_responsive.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/listings_custom.js'
      							);
	$this->load->view("template/container2/header",$page_data);
	?>

</head>


<body>

<div class="super_container">
	
	<!-- Home -->
	<div class="home"  style="height: 300px;">
		<!-- Image by: https://unsplash.com/@jbriscoe -->
		<div class="home_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/listings.jpg)"></div>
		
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="home_content" style="margin-top: 270px">
						
					<!-- Search Box -->
					<div class="search_box" >

						<div class="search_box_content" style="padding-right: 15px; padding-bottom: 13px; padding-top: 14px; padding-left: 20px">
							<!-- Search Form -->
							<form class="" action="<?php echo base_url().'listings' ?>" method="GET" >
								<div class="row ">
								  <div class="col-sm-3">
								    <div class="dropdown_item_title">Location</div>
								     <select name="lct" style="border-radius: 0px; height: 34px;" class="dropdown_item_select form-control">
			                            <option value="">Any</option>
			                            <optgroup label="Northern Region">
			                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
			                              <?php if ($value['region']=='Northern Region'): ?>
			                                  <option <?php if(isset($page_data['url_parameters']['params']['lct']) 
				                                         && $page_data['url_parameters']['params']['lct'] == $value['location_id']) echo 'selected="selected"'; ?> 
				                                         value="<?php echo $value['location_id'] ?>" >
				                                         <?php echo $value['location_name'] ?>
				                            </option>
			                              <?php endif ?>
			                            <?php endforeach ?> 
			                            </optgroup>
			                            <optgroup label="Central Region">
			                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
			                              <?php if ($value['region']=='Central Region'): ?>
			                                  <option <?php if(isset($page_data['url_parameters']['params']['lct']) 
				                                         && $page_data['url_parameters']['params']['lct'] == $value['location_id']) echo 'selected="selected"'; ?> 
				                                         value="<?php echo $value['location_id'] ?>" >
				                                         <?php echo $value['location_name'] ?>
				                            </option>
			                              <?php endif ?>
			                            <?php endforeach ?>
			                            </optgroup>
			                            <optgroup label="Southern Region"> 
			                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
			                              <?php if ($value['region']=='Southern Region'): ?>
			                                  <option <?php if(isset($page_data['url_parameters']['params']['lct']) 
				                                         && $page_data['url_parameters']['params']['lct'] == $value['location_id']) echo 'selected="selected"'; ?> 
				                                         value="<?php echo $value['location_id'] ?>" >
				                                         <?php echo $value['location_name'] ?>
				                            </option>
			                              <?php endif ?>
			                            <?php endforeach ?>
			                            </optgroup> 
			                          </select>
								  </div>
								  <div class="col-sm-3">
								  	<div class="dropdown_item_title">Type</div>
								    <select name="typ" id="property_type" class="dropdown_item_select">
										<option value="">Any</option>
										<?php foreach ($page_data['type']['data']['records'] as $key => $value): ?>
				                            <option <?php if(isset($page_data['url_parameters']['params']['typ']) 
				                                         && $page_data['url_parameters']['params']['typ'] == $value['type_id']) echo 'selected="selected"'; ?> 
				                                         value="<?php echo $value['type_id'] ?>" >
				                                         <?php echo $value['type_name'] ?>
				                            </option>
			                            <?php endforeach ?> 
									</select>
								  </div>
								  <div class="col-sm-3">
								  	<div class="dropdown_item_title">Price</div>
								    <select name="prc" id="property_type" class="dropdown_item_select">
										<option value="">Any</option>
										<option <?php if(isset($page_data['url_parameters']['params']['prc']) && $page_data['url_parameters']['params']['prc']=="0-50000")echo 'selected="selected"'; ?> value="0-50000">Min -K50,000</option>
										<option <?php if(isset($page_data['url_parameters']['params']['prc']) && $page_data['url_parameters']['params']['prc']=="50000-100000")echo 'selected="selected"'; ?> value="50000-100000">K50,000-K100,000</option>
										<option <?php if(isset($page_data['url_parameters']['params']['prc']) && $page_data['url_parameters']['params']['prc']=="100000-300000")echo 'selected="selected"'; ?> value="100000-300000">K100,000-K300,000</option>
										<option <?php if(isset($page_data['url_parameters']['params']['prc']) && $page_data['url_parameters']['params']['prc']=="300000-500000")echo 'selected="selected"'; ?> value="300000-500000">K300,000-K500,000</option>
										<option <?php if(isset($page_data['url_parameters']['params']['prc']) && $page_data['url_parameters']['params']['prc']=="500000-1000000")echo 'selected="selected"'; ?> value="500000-1000000">K500,000-K1000,000</option>
										<option <?php if(isset($page_data['url_parameters']['params']['prc']) && $page_data['url_parameters']['params']['prc']=="1000000-100000000000")echo 'selected="selected"'; ?> value="1000000-100000000000">K1000,000 - Max</option>
									</select>
								  </div>
								  <div class="col-sm-3">
								    <input style="margin-top: 25px" value="search" type="submit" class="search_submit_button">
								  </div>
								</div>


							</form>
						</div>	
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

<!-- Listings -->

	<div class="listings" style="padding-top: 170px">
		<div class="container">

			<div class="row">
				<div class="col">
					<div class="section_title text-center">
						<h3>Properties For sale & Rent</h3>
						<span class="section_subtitle">See our best offers</span>
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

			<!-- Pagination -->
			<?php
			 $this->load->view("pagination/pagination",$page_data);
			?>
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
