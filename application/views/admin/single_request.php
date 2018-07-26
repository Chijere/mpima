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
    $page_data['css_links']=array(  'assets/css/admin.min.css',
                                    'assets/vendors/PhotoSwipe-master/dist/photoswipe.css',
                                    'assets/vendors/PhotoSwipe-master/dist/default-skin/default-skin.css',
                                    'assets/vendors/Holdon/HoldOn.min.css',
                                    'assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.css',
                                );

    $page_data['js_links']=array( 'assets/vendors/PhotoSwipe-master/dist/photoswipe.min.js',
                                  'assets/vendors/PhotoSwipe-master/dist/photoswipe-ui-default.min.js',
                                  'assets/js/admin_single_request_view_custom.js',
                                );

    $page_data['tag']=array( 
                        '<script src="'.base_url().'assets/js/admin_custom.min.js" async ></script>',
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
                <h3>Customer Request</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-8">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Request Info</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                          <h3>
                            <?php echo $page_data['item']['data']['records'][0]['item_name']; ?>
                          </h3>
                          <h5> <strong>Date: </strong> <?php echo $page_data['item']['data']['records'][0]['date']; ?>
                          </h5>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- Table row -->
                      <div style="margin-top: 50px" class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <tbody>
                              <tr>
                                <th style="width: 100px; font-size: 15px;">Price:</th>
                                <td><?php echo $page_data['item']['data']['records'][0]['price']; ?></td>
                              </tr>
                              <tr>
                                <th style="width: 100px; font-size: 15px;">Type:</th>
                                <td><?php echo $page_data['item']['data']['records'][0]['type_name']; ?></td>
                              </tr>
                              <tr>
                                <th style="width: 100px; font-size: 15px;">Category:</th>
                                <td><?php echo $page_data['item']['data']['records'][0]['category_name']; ?></td>
                              </tr>
                              <tr>
                                <th style="width: 100px; font-size: 15px;">Location:</th>
                                <td><?php echo $page_data['item']['data']['records'][0]['location_name']; ?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                      <div class="row">
                        <div class="col-md-8">
                          <div class="tab-pane active" id="home-r">
                          <p class="lead" style="font-size: 16px; font-weight: 600">Description</p>
                          <p><?php echo $page_data['item']['data']['records'][0]['item_description']; ?></p>
                        </div>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Actions</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left form_action" action="<?php echo base_url(); ?>admin/edit_request/form">
                      <input type="hidden" name="i_ref" value="<?php echo $page_data['item']['data']['records'][0]['item_id'] ?>" />
                      <div class="form-group">
                        <label class="control-label"><small> please delete only review requests </small></label>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="">
                          <button style="width:70px" type="submit" class="btn btn-danger delete">Delete </button>
                        </div>
                      </div>
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

                    <div class="row" id="image-gallery">

                      <?php
                        foreach ($page_data['item']['data']['records'][0]['item_pic'] as $key => $value) {
                      ?>
                        <div class="col-md-55">
                          <div class="thumbnail"   data-size="<?php echo $value['dimension']; ?>" data-thumb="<?php echo IMAGE_SRC_URL.$value['path'].'_t.jpg'; ?>" data-src="<?php echo IMAGE_SRC_URL.$value['path'].'.jpg'; ?>"  style="padding: 0px">
                            <div class="image view view-first">
                              <img style="" src="<?php echo IMAGE_SRC_URL.$value['path'].'_t.jpg'; ?>" alt="No image" />
                            </div>
                          </div>
                        </div>
                      <?php } ?>

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

<!-------- PhotoSwipe Plugin ------------>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
 
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
 
        <div class="pswp__ui pswp__ui--hidden" style="position: static;">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--share" title="Share"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div> 
</div>

  <script src="http://localhost/mpima/assets/vendors/parsleyjs/dist/parsley.min.js" ></script>
  <script src="http://localhost/mpima/assets/vendors/Holdon/HoldOn.min.js" ></script>
  <script src="http://localhost/mpima/assets/vendors/jquery.sweet-modal-1.3.3/min/jquery.sweet-modal.min.js" ></script>


    <!-- Parsley -->
    <script>
      $(document).ready(function() {
      

        $('.form_action .delete').on('click',function(event) {
          event.preventDefault();
          
          $.sweetModal({
          content: 'Confirm to delete this Request?',
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
                       
                $.ajax({
                    url  : $(".form_action").attr('action')+'/delete',
                    type : 'POST',
                    data : $('.form_action').serialize(),
                    success : afterSuccess_delete,
                    beforeSubmit: beforeSubmit(),
                    });

              }
              },
            },
            onClose:function() {
            }
          });

        });

          //beforeSubmit  
          function beforeSubmit(jqXHR,element)
          {
            var valid=true;
           
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
                content: 'Request deleted.',
                icon: $.sweetModal.ICON_SUCCESS,
                theme:$.sweetModal.THEME_MIXED,                
                buttons: {
                  someAction: {
                    label: 'close',
                    classes: '',
                    action: function() {
                      window.location.href = $('#info #base_url').html()+'admin/requests';
                    }
                    },
                  },
                  onClose:function() {
                    window.location.href = $('#info #base_url').html()+'admin/requests';
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
            
      });
    </script>
    <!-- /Parsley -->

  </body>

</html>