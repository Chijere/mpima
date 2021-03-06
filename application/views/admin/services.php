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

    $page_data['page_title']='Mpima Edit Services';
    $page_data['css_links']=array(  'assets/css/admin.min.css',
                                    'assets/vendors/PhotoSwipe-master/dist/photoswipe.css',
                                    'assets/vendors/PhotoSwipe-master/dist/default-skin/default-skin.css',
                                    'assets/vendors/Holdon/HoldOn.min.css',
                                    'assets/vendors/dropzone/dist/min/dropzone.min.css',
                                    'assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.css',
                                );

    $page_data['js_links']=array( 'assets/vendors/PhotoSwipe-master/dist/photoswipe.min.js',
                                  'assets/vendors/PhotoSwipe-master/dist/photoswipe-ui-default.min.js',
                                );

    $page_data['tag']=array( 
                        '<script src="'.base_url().'assets/vendors/progressbar/bootstrap-progressbar.min" ></script>',
                        '<script src="'.base_url().'assets/js/admin_custom.min.js" async ></script>',
                        '<script src="'.base_url().'assets/vendors/dropzone/dist/min/dropzone.min.js" ></script>'
                      );
                      
    $this->load->view("template/container/header_admin",$page_data);
  ?>

<!-------Overrriden Css--------->
<style type="text/css">
 .sweet-modal-overlay{
  z-index: 1060 !important;
 }   
</style>

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
                <h3>Services</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-8">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Current Services</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row" id="image-gallery">

                      <?php 
                        foreach ($page_data['item']['data']['records'] as $key => $value) {
                      ?>
                        <div class="col-sm-3">
                          <div class="thumbnail" data-toggle="modal" data-target="#<?php echo $value['item_id']; ?>"  style="padding: 0px">
                            <div class="image view view-first">
                              <img style="" src="<?php echo IMAGE_SRC_URL.$value['item_pic']['main']['path'].'_t.jpg'; ?>" alt="No image" />
                              <div class="mask">
                              <div class="tools tools-bottom">
                                <a href="#"><i class="fa fa-pencil"></i></a>
                              </div>
                            </div>
                            </div>
                          <div class="caption">
                            <div>
                              <strong><?php echo $value['item_name']; ?></strong>
                            </div>
                            <span class="role">
                              <?php echo $value['title']; ?>
                            </span>
                          </div>                          
                          </div>
                        </div>
                      <?php } ?>

                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add a Service</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="form-group">
                        <label class="control-label"><small> - Only 2 services can be featured on the front banner </small></label>
                        <label class="control-label"><small> - Add a file to the services </small></label>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="">
                          <button style="width:70px"  class="btn btn-success add-service"> Add </button>
                        </div>
                      </div>
                  </div>
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

<!--------------------service--------------------------->
<!-- Modal - Add service -->
<div id="add-service-Modal" class="modal fade add" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add service</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert" style="display: none;">
          <strong>Error: </strong> <span></span>
        </div>
        <div class="progress" style="display: none; width: 80%; margin-left: 10%;">
            <div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress>
                <span class="progress-text"></span>
            </div>
        </div>
                      <form class=" main_form form-horizontal form-label-left" action="<?php echo base_url(); ?>admin/services/form/add" data-parsley-validate>
                      
                      <div class="form-group">
                        <label class="control-label col-sm-2">Title<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" data-parsley-length="[0, 100]" name="title" placeholder="e.g Bidding" required="required" 
                            data-parsley-length-message="It should be between 2 to 100 characters"
                          >
                        </div>
                      </div>                  
                      <div class="form-group">
                        <label class="control-label col-sm-2">About<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea name="description" class="form-control" rows="15"
                              data-parsley-length="[0, 2000]" name="name" placeholder="e.g. We are experts in this"
                            data-parsley-length-message="Must not be more than 2000 characters" required="required" 
                          ></textarea>
                        </div>
                        </div>   
                          <div class="ln_solid"></div>
                          <div class="checkbox " style="margin-bottom: 10px">
                          <label>
                          <input type="checkbox" name="addTofrntBanner" value="1"> Add on Front banner as Special Service
                          </label>
                          </div>
                          <div class="form-group">
                              <button style="width: 140px" type="button" class="btn btn-default modal-change-photo"><i class="fa fa-pencil"></i> Add Brochure </button>
                              <span class="file_name"></span>
                          </div>

                 </form>
                      <div class="row" id="image-gallery" style="display: none;">
                            <div class="col-sm-12">
                              <div class="thumbnail"  style="padding: 0px">
                                <div class="view view-first">
                                <form id="add_service_dropzone_form" action="<?php echo base_url(); ?>upload/file/attch" class="dropzone add" style="display: none; border:none; padding:0px; "></form>
                                </div>                          
                              </div>
                            </div>
                      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success submit" >Submit</button>
        <button type="button" class="btn btn-default cancel" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<?php foreach ($page_data['item']['data']['records'] as $key => $value) { ?>
<div id="<?php echo $value['item_id']; ?>" class="modal fade  edit" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Service</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert" style="display: none;">
          <strong>Error: </strong> <span></span>
        </div>
        
        <div class="progress" style="display: none; width: 80%; margin-left: 10%;">
            <div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress>
                <span class="progress-text"></span>
            </div>
        </div>
                    <form class=" main_form form-horizontal form-label-left" action="<?php echo base_url(); ?>admin/services/form/edit" data-parsley-validate> 
                      
                      <div class="form-group">
                        <label class="control-label col-sm-2">Title<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" data-parsley-length="[0, 100]" name="title" placeholder="<?php echo $value['title']; ?>"  
                            data-parsley-length-message="It should be between 2 to 100 characters"
                          >
                        </div>
                      </div>                  
                      <div class="form-group">
                        <label class="control-label col-sm-2">About<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea name="description" class="form-control" rows="15"
                              data-parsley-length="[0, 2000]" name="name" placeholder="<?php echo $value['item_description']; ?>"
                            data-parsley-length-message="Must not be more than 2000 characters"
                          ></textarea>
                        </div>
                        </div>   
                          <div class="ln_solid"></div>
                
                          <div class="checkbox " style="margin-bottom: 10px">
                          <label>
                          <input <?php if($value['addTo_front_Banner']) echo "checked"; ?> type="checkbox" name="addTofrntBanner" value="1"> Add on Front banner as Special Service
                          </label>
                          </div>

                          <input type="hidden" name="i_ref" value="<?php echo $value['item_id'] ?>" />
                          <div class="form-group">
                              <button style="width: 170px" type="button" class="btn btn-default modal-change-photo"><i class="fa fa-pencil"></i> Change Brochure </button>
                              <span class="file_name">Added File</span>
                          </div>

                              <button style="width: 140px" type="submit" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete </button>

                 </form>
                 
                      <div class="row" id="image-gallery" style="display: none;">
                            <div class="col-sm-12">
                              <div class="thumbnail"  style="padding: 0px">
                                <div class="view view-first">
                                <form id="add_service_dropzone_form" action="<?php echo base_url(); ?>upload/file/attch" class="dropzone add" style="display: none; border:none; padding:0px; "></form>
                                </div>                          
                              </div>
                            </div>
                      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success submit" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php } ?>




  <script src="<?php echo base_url(); ?>assets/vendors/parsleyjs/dist/parsley.min.js" ></script>
  <script src="<?php echo base_url(); ?>assets/vendors/Holdon/HoldOn.min.js" ></script>
  <script src="<?php echo base_url(); ?>assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.js" ></script>
  <script src="<?php echo base_url(); ?>assets/js/admin_services_custom.js" ></script>


    <!-- Parsley -->
    <script>
    </script>
    <!-- /Parsley -->

  </body>

</html>