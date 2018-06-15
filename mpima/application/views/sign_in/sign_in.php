<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	
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

    $page_data['page_title']='Mpima Login';
    $page_data['css_links']=array( 'assets/css/sign_in.css',
                                );

    $page_data['tag']=array( 
                             '<script src="'.base_url().'assets/js/sign_in.js" ></script>',
                          );
                      
    $this->load->view("template/container/header_admin",$page_data);
  ?>

</head>

        <body>
            <div class="container"> 
                <div class="row vertical-offset-100">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">                                
                                <div class="row-fluid user-row">
                                  <h3><strong>Admin</strong> Login</h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form id="login_form" accept-charset="UTF-8" role="form" class="form-signin" action="<?php echo base_url(); ?>sign_in" method="POST">
                                    <fieldset>
                                        <label class="panel-login">
                                            <div class="login_result"></div>
                                        </label>
                                        <input class="form-control" placeholder="email" id="email" name="email" value="<?php echo set_value('email'); ?>" type="text">
                                        <input class="form-control" placeholder="Password" name="password" id="password" type="password">
                                        <br></br>
                                        <input class="btn btn-lg btn-success btn-block" type="submit" id="login" value="Login »">
                                    </fieldset>
                                </form>
                                <div class="clearfix" style="margin-top: 10px"></div>
                                <?php if(!$page_data['status']){ ?> 
                                    <div class="alert alert-danger" id="error_see" style="display: none;" >
                                      <strong>Error: </strong> <?php echo $page_data['result_info']; ?>.
                                    </div>
                                <?php }else { ?>
                                    <div class="alert alert-danger" id="error_see" style="display: none;" ></div>
                                <?php } ?>    
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="row vertical-offset-100">
                          <!-- Footer -->
                  <?php
                   $this->load->view("template/container/footer_admin",$page_data);
                  ?>
                </div>
            </div>
        </body>
</html>            



