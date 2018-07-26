<!DOCTYPE html>
<html lang="en">
<head>  
  <?php
    
    /* Already Loaded CSS & JS Links
    
    ##CSS 

      assets/vendors/bootstrap/dist/css/bootstrap.min.css
      assets/vendors/font-awesome/css/font-awesome.min.css
      assets/vendors/nprogress/nprogress.css
      assets/vendors/iCheck/skins/flat/green.css
        
      ##JS
        assets/vendors/jquery/dist/jquery.min.js
        assets/vendors/bootstrap/dist/js/bootstrap.min.js
        assets/vendors/fastclick/lib/fastclick.js
        assets/vendors/nprogress/nprogress.js
        assets/vendors/iCheck/icheck.min.js
     */

    $page_data['page_title']='Mpima Edit Property';
    $page_data['css_links']=array( 'assets/css/admin.min.css',
                                    'assets/vendors/dropzone/dist/min/dropzone.min.css',
                                    'assets/vendors/Holdon/HoldOn.min.css',
                                    'assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.css',
                                );

    $page_data['tag']=array( 
                        '<script src="'.base_url().'assets/js/admin_custom.min.js" async ></script>',
                        '<script src="'.base_url().'assets/js/file-upload_edit.js" ></script>',
                        '<script src="'.base_url().'assets/vendors/validator/validator.js" ></script>',
                        '<script src="'.base_url().'assets/vendors/jquery.form.min.js" ></script>',
                        '<script src="'.base_url().'assets/vendors/dropzone/dist/min/dropzone.min.js" ></script>'
                      );
                      
    $this->load->view("template/container/header_admin",$page_data);
  ?>

</head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">

        <!-- Left bar -->
        <?php
         $this->load->view("template/container/left_bar_admin",$page_data);
        ?>

        <!-- nav -->
        <?php
         $this->load->view("template/container/nav_bar_admin",$page_data);
        ?>
        
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Edit Property</h3>
              </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">

              <div class="col-md-8 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Description</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class=" main_form form-horizontal form-label-left" action="<?php echo base_url(); ?>manage_products/upload/form" data-parsley-validate>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Propety Title<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" data-parsley-length="[4, 20]" name="name" placeholder="<?php echo $page_data['item']['data']['records'][0]['item_name']; ?>" 
                            data-parsley-length-message="It should be between 4 to 20 characters"
                          >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Price</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input name="price" type="text" class="form-control" placeholder="<?php echo $page_data['item']['data']['records'][0]['price']; ?>" type="number" data-parsley-type="integer" data-parsley-length="[6, 10]"  >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Type</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="type" class="form-control">
                            <?php foreach ($page_data['type']['data']['records'] as $key => $value): ?>
                              <option <?php if($page_data['item']['data']['records'][0]['type_id'] == $value['type_id']) echo 'selected="selected"'; ?> value="<?php echo $value['type_id'] ?>" ><?php echo $value['type_name'] ?></option>
                            <?php endforeach ?> 
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="category" class="form-control">
                            <?php foreach ($page_data['category']['data']['records'] as $key => $value): ?>
                              <option <?php if($page_data['item']['data']['records'][0]['category_id'] == $value['category_id']) echo 'selected="selected"'; ?>  value="<?php echo $value['category_id'] ?>" ><?php echo $value['category_name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Location</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select name="location" class="select2_group form-control">
                            <optgroup label="Northern Region">
                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
                              <?php if ($value['region']=='Northern Region'): ?>
                                  <option <?php if($page_data['item']['data']['records'][0]['location_id'] == $value['location_id']) echo 'selected="selected"'; ?> value="<?php echo $value['location_id'] ?>" ><?php echo $value['location_name'] ?></option>
                              <?php endif ?>
                            <?php endforeach ?> 
                            </optgroup>
                            <optgroup label="Central Region">
                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
                              <?php if ($value['region']=='Central Region'): ?>
                                  <option <?php if($page_data['item']['data']['records'][0]['location_id'] == $value['location_id']) echo 'selected="selected"'; ?> value="<?php echo $value['location_id'] ?>" ><?php echo $value['location_name'] ?></option>
                              <?php endif ?>
                            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Southern Region"> 
                            <?php foreach ($page_data['location']['data']['records'] as $key => $value): ?>
                              <?php if ($value['region']=='Southern Region'): ?>
                                  <option <?php if($page_data['item']['data']['records'][0]['location_id'] == $value['location_id']) echo 'selected="selected"'; ?> value="<?php echo $value['location_id'] ?>" ><?php echo $value['location_name'] ?></option>
                              <?php endif ?>
                            <?php endforeach ?>
                            </optgroup> 
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Summary <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="summary" class="form-control" rows="3"
                              data-parsley-length="[10, 60]" name="name" placeholder="<?php echo $page_data['item']['data']['records'][0]['summary']; ?>" 
                            data-parsley-length-message="It should be between 10 to 60 characters"
                          ></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Description <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="description" placeholder="<?php echo $page_data['item']['data']['records'][0]['item_description']; ?>" class="form-control" rows="5" ></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Publish</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left form_publish" action="<?php echo base_url(); ?>admin/edit_property/form">
                      <input type="hidden" name="i_ref" value="<?php echo $page_data['item']['data']['records'][0]['item_id'] ?>" />
                      <div class="form-group">
                        <label class="control-label">Published <small> work will immediately show on the front end </small></label>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="">
                          <button  style="width:70px" type="submit" class="btn btn-primary publish">Publish</button>
                          <button  type="submit" class="btn btn-success save_drft"> <?php if($page_data['item']['data']['records'][0]['on_display'] != 0) echo "Move"; else echo "Save"; ?> to Draft</button>
                          <button style="width:70px" type="submit" class="btn btn-danger delete">Delete </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>              
              <div class="col-md-3 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Featured Image</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p>Drag Images to the box below to upload or click to select Images. 3mb max</p>
                    <form id="featured_form" action="<?php echo base_url(); ?>upload/pic/attch" class="dropzone featured_form" style="min-height: 80px; border: 1px solid #e5e5e5;">
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Gallery Images</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p>Drag Images to the box below to upload or click to select Images. 3mb max, 12 images max. </p>
                    <form id="gallery_form" action="<?php echo base_url(); ?>upload/pic/attch" class="dropzone gallery_form"></form>
                    <br />
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Footer -->
        <?php
         $this->load->view("template/container/footer_admin",$page_data);
        ?>

      </div>
    </div>

    <data id="info" style="display: none;">
      <span id="base_url"><?php echo base_url(); ?></span>
      <span id="IMAGE_SRC_URL"><?php echo IMAGE_SRC_URL; ?></span>
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
       $('.form_publish').on('submit',function(event) {
          //event.preventDefault();
          //return false;
        });
 
        $('.form_publish .publish').on('click',function(event) {
        event.preventDefault();
        $('.main_form').parsley().validate();
        if (true === $('.main_form').parsley().isValid()) {

          $('.form_publish').append('<input type="hidden" name="on_display" value="1" />');

            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
             
            $.ajax({
                url  : $(".form_publish").attr('action'),
                type : 'POST',
                data : $('.main_form,.gallery_form,.featured_form,.form_publish').serialize(),
                success : afterSuccess,
                beforeSubmit: beforeSubmit(),
                });

          } else {

            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          
          }
        });

        $('.form_publish .save_drft').on('click',function(event) {
          event.preventDefault();
        $('.main_form').parsley().validate();
        if (true === $('.main_form').parsley().isValid()) {
          
          $('.form_publish').append('<input type="hidden" name="on_display" value="0" />');

            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
             
            $.ajax({
                url  : $(".form_publish").attr('action'),
                type : 'POST',
                data : $('.main_form,.gallery_form,.featured_form,.form_publish').serialize(),
                success : afterSuccess,
                beforeSubmit: beforeSubmit(),
                });

          } else {

            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          
          }
        });

        $('.form_publish .delete').on('click',function(event) {
          event.preventDefault();
          
          $.sweetModal({
          content: 'Confirm to delete this Property?',
          theme:$.sweetModal.THEME_MIXED,
          showCloseButton:false,                
          buttons: {
            someAction: {
              label: 'cancel',
              classes: '',
              action: function() {
              }
              },
            someAction2: {
              label: 'Yes',
              classes: 'redB',
              action: function() {
                       $('.main_form').parsley().validate();
                        if (true === $('.main_form').parsley().isValid()) {
                        
                        $('.form_publish').append('<input type="hidden" name="on_display" value="0" />');

                          $('.bs-callout-info').removeClass('hidden');
                          $('.bs-callout-warning').addClass('hidden');
                           
                          $.ajax({
                              url  : $(".form_publish").attr('action')+'/delete',
                              type : 'POST',
                              data : $('.main_form,.gallery_form,.featured_form,.form_publish').serialize(),
                              success : afterSuccess_delete,
                              beforeSubmit: beforeSubmit(),
                              });

                        } else {

                          $('.bs-callout-info').addClass('hidden');
                          $('.bs-callout-warning').removeClass('hidden');
                        
                        }
              }
              },
            },
            onClose:function() {
            }
          });

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
          function afterSuccess_delete(info)
          { 
              HoldOn.close();
            if(info.status)
            {
              $.sweetModal({
                content: 'Propety deleted.',
                icon: $.sweetModal.ICON_SUCCESS,
                theme:$.sweetModal.THEME_MIXED,                
                buttons: {
                  someAction: {
                    label: 'close',
                    classes: '',
                    action: function() {
                      window.location.href = $('#info #base_url').html()+'admin';
                    }
                    },
                  },
                  onClose:function() {
                    window.location.href = $('#info #base_url').html()+'admin';
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
              
          //function after succesful file upload (when server response)
          function afterSuccess(info)
          { 
              HoldOn.close();
            if(info.status)
            {
              $.sweetModal({
                content: 'Propety Edited.',
                icon: $.sweetModal.ICON_SUCCESS,
                theme:$.sweetModal.THEME_MIXED,                
                buttons: {
                  someAction: {
                    label: 'close',
                    classes: '',
                    action: function() {
                      window.location.href = $('#info #base_url').html()+'admin';
                    }
                    },
                  },
                  onClose:function() {
                   window.location.href = $('#info #base_url').html()+'admin';
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