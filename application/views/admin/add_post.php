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

    $page_data['page_title']='Mpima Add Property';
    $page_data['css_links']=array( 'assets/css/admin.min.css',
                                    'assets/vendors/dropzone/dist/min/dropzone.min.css',
                                    'assets/vendors/Holdon/HoldOn.min.css',
                                    'assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.css',
                                );

    $page_data['tag']=array( 
                        '<script src="'.base_url().'assets/js/admin_custom.min.js" async ></script>',
                        '<script src="'.base_url().'assets/js/file-upload.js" ></script>',
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
                <h3>Add Post</h3>
              </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Text areas<small>Sessions</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div id="alerts"></div>
                  
                  <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor">
                    

                    <div class="btn-group">
                      <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li>
                          <a data-edit="fontSize 5">
                            <p style="font-size:17px">Huge</p>
                          </a>
                        </li>
                        <li>
                          <a data-edit="fontSize 3">
                            <p style="font-size:14px">Normal</p>
                          </a>
                        </li>
                        <li>
                          <a data-edit="fontSize 1">
                            <p style="font-size:11px">Small</p>
                          </a>
                        </li>
                      </ul>
                    </div>

                    <div class="btn-group">
                      <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                      <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                      <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                    </div>

                    <div class="btn-group">
                      <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                      <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                    </div>

                    <div class="btn-group">
                      <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                      <div class="dropdown-menu input-append">
                        <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                        <button class="btn" type="button">Add</button>
                      </div>
                    </div>

                    <div class="btn-group">
                      <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                      <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                    </div>

                    <div class="btn-group">
                      <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                      <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                    </div>
                  </div>

                  
                  <div id="editor" class="editor-wrapper"></div>

                  <textarea name="descr" id="descr" style="display:none;"></textarea>
                  
                  <br />

                  <div class="ln_solid"></div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Resizable Text area</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <textarea class="resizable_textarea form-control" placeholder="This text area automatically resizes its height as you fill in more text courtesy of autosize-master it out..."></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
                          <input type="text" class="form-control" data-parsley-length="[4, 20]" name="name" placeholder="e.g House in Nyambadwe" required="required" 
                            data-parsley-length-message="It should be between 4 to 20 characters"
                          >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Price</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input name="price" type="text" class="form-control" placeholder="e.g 30000" type="number" data-parsley-type="integer" data-parsley-length="[5, 10]" required="required" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Type</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="type" class="form-control">
                            <?php foreach ($page_data['type']['data']['records'] as $key => $value): ?>
                              <option  value="<?php echo $value['type_id'] ?>" ><?php echo $value['type_name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="category" class="form-control">
                            <?php foreach ($page_data['category']['data']['records'] as $key => $value): ?>
                              <option  value="<?php echo $value['category_id'] ?>" ><?php echo $value['category_name'] ?></option>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Summary <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="summary" class="form-control" rows="3"
                              data-parsley-length="[10, 60]" name="name" placeholder="e.g. modern House in Nyambadwe with 4 bedrooms" required="required" 
                            data-parsley-length-message="It should be between 10 to 60 characters"
                          ></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Description <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="description" class="form-control" rows="5" required="required"></textarea>
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
                    <form class="form-horizontal form-label-left form_publish" action="<?php echo base_url(); ?>admin/add_property/form">
                      <div class="form-group">
                        <label class="control-label">Published <small> work will immediately show on the front end </small></label>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="">
                          <button type="submit" class="btn btn-primary publish">Publish</button>
                          <button type="submit" class="btn btn-success save_drft">Save Draft</button>
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
                    <form id="featured_form" action="<?php echo base_url(); ?>upload/pic/attch" class="dropzone featured_form" style="min-height: 80px; border: 1px solid #e5e5e5;"></form>
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
            if($('.featured_form').find('input[name="input1"]').length <1)
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
            }

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
                content: 'Propety Added.',
                icon: $.sweetModal.ICON_SUCCESS,
                theme:$.sweetModal.THEME_MIXED,                
                buttons: {
                  someOtherAction: {
                    label: 'Add another Propety',
                    classes: 'lightGreyB bordered flat',
                    action: function() {
                      window.location.href = $('#info #base_url').html()+'admin/add_property';
                    }
                  },
                  someAction: {
                    label: 'View',
                    classes: '',
                    action: function() {
                      window.location.href = $('#info #base_url').html()+'admin';
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