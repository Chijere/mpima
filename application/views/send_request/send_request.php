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
                        <label class="control-label col-md-8 col-sm-3 col-xs-12">Where are you from?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="type" class="form-control">
                            
                                      <option value="AF">Afghanistan</option>
                                      <option value="AX">Åland Islands</option>
                                      <option value="AL">Albania</option>
                                      <option value="DZ">Algeria</option>
                                      <option value="AS">American Samoa</option>
                                      <option value="AD">Andorra</option>
                                      <option value="AO">Angola</option>
                                      <option value="AI">Anguilla</option>
                                      <option value="AQ">Antarctica</option>
                                      <option value="AG">Antigua and Barbuda</option>
                                      <option value="AR">Argentina</option>
                                      <option value="AM">Armenia</option>
                                      <option value="AW">Aruba</option>
                                      <option value="AU">Australia</option>
                                      <option value="AT">Austria</option>
                                      <option value="AZ">Azerbaijan</option>
                                      <option value="BS">Bahamas</option>
                                      <option value="BH">Bahrain</option>
                                      <option value="BD">Bangladesh</option>
                                      <option value="BB">Barbados</option>
                                      <option value="BY">Belarus</option>
                                      <option value="BE">Belgium</option>
                                      <option value="BZ">Belize</option>
                                      <option value="BJ">Benin</option>
                                      <option value="BM">Bermuda</option>
                                      <option value="BT">Bhutan</option>
                                      <option value="BO">Bolivia, Plurinational State of</option>
                                      <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                      <option value="BA">Bosnia and Herzegovina</option>
                                      <option value="BW">Botswana</option>
                                      <option value="BV">Bouvet Island</option>
                                      <option value="BR">Brazil</option>
                                      <option value="IO">British Indian Ocean Territory</option>
                                      <option value="BN">Brunei Darussalam</option>
                                      <option value="BG">Bulgaria</option>
                                      <option value="BF">Burkina Faso</option>
                                      <option value="BI">Burundi</option>
                                      <option value="KH">Cambodia</option>
                                      <option value="CM">Cameroon</option>
                                      <option value="CA">Canada</option>
                                      <option value="CV">Cape Verde</option>
                                      <option value="KY">Cayman Islands</option>
                                      <option value="CF">Central African Republic</option>
                                      <option value="TD">Chad</option>
                                      <option value="CL">Chile</option>
                                      <option value="CN">China</option>
                                      <option value="CX">Christmas Island</option>
                                      <option value="CC">Cocos (Keeling) Islands</option>
                                      <option value="CO">Colombia</option>
                                      <option value="KM">Comoros</option>
                                      <option value="CG">Congo</option>
                                      <option value="CD">Congo, the Democratic Republic of the</option>
                                      <option value="CK">Cook Islands</option>
                                      <option value="CR">Costa Rica</option>
                                      <option value="CI">Côte d'Ivoire</option>
                                      <option value="HR">Croatia</option>
                                      <option value="CU">Cuba</option>
                                      <option value="CW">Curaçao</option>
                                      <option value="CY">Cyprus</option>
                                      <option value="CZ">Czech Republic</option>
                                      <option value="DK">Denmark</option>
                                      <option value="DJ">Djibouti</option>
                                      <option value="DM">Dominica</option>
                                      <option value="DO">Dominican Republic</option>
                                      <option value="EC">Ecuador</option>
                                      <option value="EG">Egypt</option>
                                      <option value="SV">El Salvador</option>
                                      <option value="GQ">Equatorial Guinea</option>
                                      <option value="ER">Eritrea</option>
                                      <option value="EE">Estonia</option>
                                      <option value="ET">Ethiopia</option>
                                      <option value="FK">Falkland Islands (Malvinas)</option>
                                      <option value="FO">Faroe Islands</option>
                                      <option value="FJ">Fiji</option>
                                      <option value="FI">Finland</option>
                                      <option value="FR">France</option>
                                      <option value="GF">French Guiana</option>
                                      <option value="PF">French Polynesia</option>
                                      <option value="TF">French Southern Territories</option>
                                      <option value="GA">Gabon</option>
                                      <option value="GM">Gambia</option>
                                      <option value="GE">Georgia</option>
                                      <option value="DE">Germany</option>
                                      <option value="GH">Ghana</option>
                                      <option value="GI">Gibraltar</option>
                                      <option value="GR">Greece</option>
                                      <option value="GL">Greenland</option>
                                      <option value="GD">Grenada</option>
                                      <option value="GP">Guadeloupe</option>
                                      <option value="GU">Guam</option>
                                      <option value="GT">Guatemala</option>
                                      <option value="GG">Guernsey</option>
                                      <option value="GN">Guinea</option>
                                      <option value="GW">Guinea-Bissau</option>
                                      <option value="GY">Guyana</option>
                                      <option value="HT">Haiti</option>
                                      <option value="HM">Heard Island and McDonald Islands</option>
                                      <option value="VA">Holy See (Vatican City State)</option>
                                      <option value="HN">Honduras</option>
                                      <option value="HK">Hong Kong</option>
                                      <option value="HU">Hungary</option>
                                      <option value="IS">Iceland</option>
                                      <option value="IN">India</option>
                                      <option value="ID">Indonesia</option>
                                      <option value="IR">Iran, Islamic Republic of</option>
                                      <option value="IQ">Iraq</option>
                                      <option value="IE">Ireland</option>
                                      <option value="IM">Isle of Man</option>
                                      <option value="IL">Israel</option>
                                      <option value="IT">Italy</option>
                                      <option value="JM">Jamaica</option>
                                      <option value="JP">Japan</option>
                                      <option value="JE">Jersey</option>
                                      <option value="JO">Jordan</option>
                                      <option value="KZ">Kazakhstan</option>
                                      <option value="KE">Kenya</option>
                                      <option value="KI">Kiribati</option>
                                      <option value="KP">Korea, Democratic People's Republic of</option>
                                      <option value="KR">Korea, Republic of</option>
                                      <option value="KW">Kuwait</option>
                                      <option value="KG">Kyrgyzstan</option>
                                      <option value="LA">Lao People's Democratic Republic</option>
                                      <option value="LV">Latvia</option>
                                      <option value="LB">Lebanon</option>
                                      <option value="LS">Lesotho</option>
                                      <option value="LR">Liberia</option>
                                      <option value="LY">Libya</option>
                                      <option value="LI">Liechtenstein</option>
                                      <option value="LT">Lithuania</option>
                                      <option value="LU">Luxembourg</option>
                                      <option value="MO">Macao</option>
                                      <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                      <option value="MG">Madagascar</option>
                                      <option value="MW" selected="selected">Malawi</option>
                                      <option value="MY">Malaysia</option>
                                      <option value="MV">Maldives</option>
                                      <option value="ML">Mali</option>
                                      <option value="MT">Malta</option>
                                      <option value="MH">Marshall Islands</option>
                                      <option value="MQ">Martinique</option>
                                      <option value="MR">Mauritania</option>
                                      <option value="MU">Mauritius</option>
                                      <option value="YT">Mayotte</option>
                                      <option value="MX">Mexico</option>
                                      <option value="FM">Micronesia, Federated States of</option>
                                      <option value="MD">Moldova, Republic of</option>
                                      <option value="MC">Monaco</option>
                                      <option value="MN">Mongolia</option>
                                      <option value="ME">Montenegro</option>
                                      <option value="MS">Montserrat</option>
                                      <option value="MA">Morocco</option>
                                      <option value="MZ">Mozambique</option>
                                      <option value="MM">Myanmar</option>
                                      <option value="NA">Namibia</option>
                                      <option value="NR">Nauru</option>
                                      <option value="NP">Nepal</option>
                                      <option value="NL">Netherlands</option>
                                      <option value="NC">New Caledonia</option>
                                      <option value="NZ">New Zealand</option>
                                      <option value="NI">Nicaragua</option>
                                      <option value="NE">Niger</option>
                                      <option value="NG">Nigeria</option>
                                      <option value="NU">Niue</option>
                                      <option value="NF">Norfolk Island</option>
                                      <option value="MP">Northern Mariana Islands</option>
                                      <option value="NO">Norway</option>
                                      <option value="OM">Oman</option>
                                      <option value="PK">Pakistan</option>
                                      <option value="PW">Palau</option>
                                      <option value="PS">Palestinian Territory, Occupied</option>
                                      <option value="PA">Panama</option>
                                      <option value="PG">Papua New Guinea</option>
                                      <option value="PY">Paraguay</option>
                                      <option value="PE">Peru</option>
                                      <option value="PH">Philippines</option>
                                      <option value="PN">Pitcairn</option>
                                      <option value="PL">Poland</option>
                                      <option value="PT">Portugal</option>
                                      <option value="PR">Puerto Rico</option>
                                      <option value="QA">Qatar</option>
                                      <option value="RE">Réunion</option>
                                      <option value="RO">Romania</option>
                                      <option value="RU">Russian Federation</option>
                                      <option value="RW">Rwanda</option>
                                      <option value="BL">Saint Barthélemy</option>
                                      <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                      <option value="KN">Saint Kitts and Nevis</option>
                                      <option value="LC">Saint Lucia</option>
                                      <option value="MF">Saint Martin (French part)</option>
                                      <option value="PM">Saint Pierre and Miquelon</option>
                                      <option value="VC">Saint Vincent and the Grenadines</option>
                                      <option value="WS">Samoa</option>
                                      <option value="SM">San Marino</option>
                                      <option value="ST">Sao Tome and Principe</option>
                                      <option value="SA">Saudi Arabia</option>
                                      <option value="SN">Senegal</option>
                                      <option value="RS">Serbia</option>
                                      <option value="SC">Seychelles</option>
                                      <option value="SL">Sierra Leone</option>
                                      <option value="SG">Singapore</option>
                                      <option value="SX">Sint Maarten (Dutch part)</option>
                                      <option value="SK">Slovakia</option>
                                      <option value="SI">Slovenia</option>
                                      <option value="SB">Solomon Islands</option>
                                      <option value="SO">Somalia</option>
                                      <option value="ZA">South Africa</option>
                                      <option value="GS">South Georgia and the South Sandwich Islands</option>
                                      <option value="SS">South Sudan</option>
                                      <option value="ES">Spain</option>
                                      <option value="LK">Sri Lanka</option>
                                      <option value="SD">Sudan</option>
                                      <option value="SR">Suriname</option>
                                      <option value="SJ">Svalbard and Jan Mayen</option>
                                      <option value="SZ">Swaziland</option>
                                      <option value="SE">Sweden</option>
                                      <option value="CH">Switzerland</option>
                                      <option value="SY">Syrian Arab Republic</option>
                                      <option value="TW">Taiwan, Province of China</option>
                                      <option value="TJ">Tajikistan</option>
                                      <option value="TZ">Tanzania, United Republic of</option>
                                      <option value="TH">Thailand</option>
                                      <option value="TL">Timor-Leste</option>
                                      <option value="TG">Togo</option>
                                      <option value="TK">Tokelau</option>
                                      <option value="TO">Tonga</option>
                                      <option value="TT">Trinidad and Tobago</option>
                                      <option value="TN">Tunisia</option>
                                      <option value="TR">Turkey</option>
                                      <option value="TM">Turkmenistan</option>
                                      <option value="TC">Turks and Caicos Islands</option>
                                      <option value="TV">Tuvalu</option>
                                      <option value="UG">Uganda</option>
                                      <option value="UA">Ukraine</option>
                                      <option value="AE">United Arab Emirates</option>
                                      <option value="GB">United Kingdom</option>
                                      <option value="US">United States</option>
                                      <option value="UM">United States Minor Outlying Islands</option>
                                      <option value="UY">Uruguay</option>
                                      <option value="UZ">Uzbekistan</option>
                                      <option value="VU">Vanuatu</option>
                                      <option value="VE">Venezuela, Bolivarian Republic of</option>
                                      <option value="VN">Viet Nam</option>
                                      <option value="VG">Virgin Islands, British</option>
                                      <option value="VI">Virgin Islands, U.S.</option>
                                      <option value="WF">Wallis and Futuna</option>
                                      <option value="EH">Western Sahara</option>
                                      <option value="YE">Yemen</option>
                                      <option value="ZM">Zambia</option>
                                      <option value="ZW">Zimbabwe</option>

                          </select>
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
