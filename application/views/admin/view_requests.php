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

    $page_data['page_title']= SITE_NAME.' Admin';
    $page_data['css_links']=array( 'assets/css/admin.min.css',
                                );

    $page_data['tag']=array( 
                        '<script src="'.base_url().'assets/js/admin_custom.min.js" async ></script>'
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
                <h3>Properities <small> controling panel</small></h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

              <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="x_panel " style="padding: 10px 5px;">

                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th>
                              <input type="checkbox" id="check-all" class="flat">
                            </th>
                            <th class="column-title">Request ID </th>
                            <th class="column-title">Name </th>
                            <th class="column-title">Type </th>
                            <th class="column-title">Location </th>
                            <th class="column-title">Date </th>
                            <th class="column-title">Price </th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="7">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                              $n=0;
                              foreach ($page_data['item']['data']['records'] as $key => $value) {                           
                            ?>
                          <tr class="even pointer">
                            <td class="a-center ">
                              <input type="checkbox" class="flat" name="table_records">
                            </td>
                            <td class=" "><?php echo $value['item_id']; ?></td>
                            <td class=" "><?php echo $value['item_name']; ?></td>
                            <td class=" "><?php echo $value['type_name']; ?></td>
                            <td class=" "><?php echo $value['location_name']; ?></td>
                            <td class=" "><?php echo $value['date']; ?></td>
                            <td class="a-right a-right "><?php echo $value['price']; ?></td>
                            <td class=" last">
                              <a class="action_link" href="<?php echo base_url().'single_request/'.$value['item_id']; ?>">View</a>
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
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



  </body>

</html>