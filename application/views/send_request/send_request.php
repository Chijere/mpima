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

    $page_data['page_title']='Mpima Send a request';
  	$page_data['css_links']=array( 'assets/css/send_request.css',
                                   'assets/css/contact_responsive.css',
                                   'assets/vendors/dropzone/dist/min/dropzone.min.css',
                                    'assets/vendors/Holdon/HoldOn.min.css',
                                    'assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.css',
                                );

	$page_data['js_links']=array(	
      								'assets/js/contact_custom.js',
      							);

    $page_data['tag']=array( 
                       // '<script src="'.base_url().'assets/js/admin_custom.min.js" async ></script>',
                        '<script src="'.base_url().'assets/vendors/validator/validator.js" ></script>',
                        '<script src="'.base_url().'assets/vendors/jquery.form.min.js" ></script>',
                        '<script src="'.base_url().'assets/vendors/dropzone/dist/min/dropzone.min.js" ></script>',
                        '<script src="'.base_url().'assets/js/file-upload_request.js" ></script>',
                      );
                      

	$this->load->view("template/container2/header",$page_data);
	?>

</head>

<body>

<div class="super_container">
	
	<!-- Home -->
	<div class="home" style="height: 290px; position: relative;">
		<!-- Image by: https://unsplash.com/@breather -->
		<div class="home_background" style="background-image:url(<?php echo IMAGE_SRC_URL;?>media/default/images/contact.jpg)"></div>
		
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="home_content">
						<div class="home_title">
							<h2>Send us your request</h2>
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

	<div class="contact" style="padding-top: 30px">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-6 contact_col">
					<div class="estate_contact_form">
						<div class="contact_title">Provide info.</div>
						<div class="estate_contact_form_container">

                    <form class=" main_form form-horizontal form-label-left" action="<?php echo base_url(); ?>manage_products/upload/form" data-parsley-validate>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Your Name<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" data-parsley-length="[2, 40]" name="name" placeholder="John Jona" required="required"  data-parsley-required-message="please provide your name"
                            data-parsley-length-message="It should be between 2 to 40 characters"
                          >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">Phone Number<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="tel" class="form-control" data-parsley-length="[8, 20]" name="phone" placeholder="0888 484 922" required="required" data-parsley-required-message="please provide a phone number"
                          >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="email" class="form-control" name="email" placeholder="jonh@mymail.com"
                          >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">What is your Budget?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input name="price" type="text" class="form-control" placeholder="e.g 30000" type="number" data-parsley-type="integer" data-parsley-length="[5, 15]" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">Would like to rent or buy?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="type" class="form-control">
                            <?php foreach ($page_data['type']['data']['records'] as $key => $value): ?>
                              <option  value="<?php echo $value['type_id'] ?>" ><?php echo $value['type_name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">What kind of property are you interested in?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="category" class="form-control">
                            <?php foreach ($page_data['category']['data']['records'] as $key => $value): ?>
                              <option  value="<?php echo $value['category_id'] ?>" ><?php echo $value['category_name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">Which location would you like?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="location" class="select2_group form-control">
                            <optgroup label="Northern Region">
                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
                              <?php if ($value['region']=='Northern Region'): ?>
                                  <option  value="<?php echo $value['location_id'] ?>" ><?php echo $value['location_name'] ?></option>
                              <?php endif ?>
                            <?php endforeach ?> 
                            </optgroup>
                            <optgroup label="Central Region">
                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
                              <?php if ($value['region']=='Central Region'): ?>
                                  <option  value="<?php echo $value['location_id'] ?>" ><?php echo $value['location_name'] ?></option>
                              <?php endif ?>
                            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Southern Region"> 
                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
                              <?php if ($value['region']=='Southern Region'): ?>
                                  <option  value="<?php echo $value['location_id'] ?>" ><?php echo $value['location_name'] ?></option>
                              <?php endif ?>
                            <?php endforeach ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">Provide further Description <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="description" class="form-control" rows="5" required="required" data-parsley-required-message="please provide a description"></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                    </form>
                   <div class="x_content">
                    <p>Add Images if available . 3mb max, 12 images max. </p>
                    <form id="gallery_form" style="border: 1px solid #ced4da; border-radius: .25rem;" action="<?php echo base_url(); ?>upload/pic/attch" class="dropzone gallery_form"></form>
                    <br />
                  </div>

                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left form_send" action="<?php echo base_url(); ?>send_request/form">
                      <div class="form-group">
                        <label class="control-label">Your Request <small> will be handle within reasonable time </small></label>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="">
                          <button type="submit" class="btn btn-primary send_r">SEND REQUEST</button>
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


	<!-- Credits -->
	<?php
	 $this->load->view("template/container2/credits",$page_data);
	?>

</div>

    <data id="info" style="display: none;">
      <span id="base_url"><?php echo base_url(); ?></span>
    </data>
    
  <script src="http://localhost/mpima/assets/vendors/parsleyjs/dist/parsley.min.js" ></script>
  <script src="http://localhost/mpima/assets/vendors/Holdon/HoldOn.min.js" ></script>
  <script src="http://localhost/mpima/assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.js" ></script>


    <!-- Parsley -->
    <script>
      $(document).ready(function() {
      
        $.listen('parsley:field:validate', function() {
          validateFront();
        });

        // submit form submit
       $('.form_send').on('submit',function(event) {
          //event.preventDefault();
          //return false;
        });
 
 
        $('.form_send .send_r').on('click',function(event) {
        event.preventDefault();
        $('.main_form').parsley().validate();
        if (true === $('.main_form').parsley().isValid()) {

            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
             
            $.ajax({
                url  : $(".form_send").attr('action'),
                type : 'POST',
                data : $('.main_form,.gallery_form,.form_send').serialize(),
                success : afterSuccess,
                beforeSubmit: beforeSubmit(),
                });

          } else {

            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          
          }
        });

          var validateFront = function() {
            if (true === $('.main_form').parsley().isValid()) {
              $('.bs-callout-info').removeClass('hidden');
              $('.bs-callout-warning').addClass('hidden');
            } else {
              $('.bs-callout-info').addClass('hidden');
              $('.bs-callout-warning').removeClass('hidden');
            }
          };


          //beforeSubmit  
          function beforeSubmit(jqXHR,element)
          {
            var valid=true;
           
            // check pics
           /* if($('.featured_form').find('input[name="input1"]').length <1)
            {
              valid = false;
                $.sweetModal({
                  content: 'Mmh, Upload a Featured Image',
                  title: 'Slight Error',
                  icon: $.sweetModal.ICON_ERROR,
                  theme:$.sweetModal.THEME_MIXED,
                  buttons: [
                    {
                      label: 'Back',
                      classes: 'redB'
                    }
                  ]
                });
            } */

            if(valid)
            {
               HoldOn.open({
                       theme:"sk-cube-grid",
                       message:'Uploading, please wait ...',
                       backgroundColor:"#456789",
                       textColor:"#c5d1e0"
                  });
            }

            return valid;   
          }

              
          //function after succesful file upload (when server response)
          function afterSuccess(info)
          { 
            HoldOn.close();
            if(info.status)
            {
              $.sweetModal({
                content: 'Request Sent.',
                icon: $.sweetModal.ICON_SUCCESS,
                theme:$.sweetModal.THEME_MIXED,                
                buttons: {
                  someAction: {
                    label: 'Close',
                    classes: '',
                    action: function() {
                      location.reload();
                    }
                    },
                  },
                  onClose:function() {
                    location.reload();
                  }
              });
            }
            else
            { 
              if(info.data.addition_info.indexOf('validation') !== -1)
              {
                jQuery.each(info.data.result_array,function(index,mssg){
                         if( $('input[name=' + index + ']').length ) // use this if you are using id to check
                              {
                                  $('input[name=' + index + ']').parsley().reset();
                                  window.ParsleyUI.addError($('input[name=' + index + ']').parsley(), index + '-custom', mssg);  
                              }

                });
              }
              else
              {
                $.sweetModal({
                  content: info.data.result_info,
                  title: 'Error',
                  icon: $.sweetModal.ICON_ERROR,
                  theme:$.sweetModal.THEME_MIXED,
                  buttons: [
                    {
                      label: 'Back',
                      classes: 'redB'
                    }
                  ]
                });
              }
            }

          } 

          //function report error array
          function reportErrorArray(e,editableFormElement)
          {
              jQuery.each(e,function(index,mssg){
                      editableFormElement.find('input[name="'+index+'"]').parents('td').children('label.error').show('slow');
                  editableFormElement.find('input[name="'+index+'"]').parents('td').children('label.error').html(mssg);     
                });
          }

      });
    </script>
    <!-- /Parsley -->

</body>

</html>
