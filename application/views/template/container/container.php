<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>

		<!-- Meta, title, CSS, favicons, etc. -->
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <title>
			<?php if(isset($header['page_title'])) echo($header['page_title']); else echo "Mpima"; ?> 
	    </title>
   
		<!-- CSS Global -->
		
		    <!-- Custom Theme Style -->
    		<link href="<?php echo base_url(); ?>assets/build/css/custom.min.css" rel="stylesheet">
		    
		    <!-- Bootstrap -->
	        <link href="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		    <!-- Font Awesome -->
		    <link href="<?php echo base_url(); ?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		    <!-- NProgress -->
		    <link href="<?php echo base_url(); ?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
		    <!-- iCheck -->
		    <link href="<?php echo base_url(); ?>assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">


         <!-- CSS templating -->
		<?php if(isset($header['css'])){ foreach($header['css'] as $key=> $value){ ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url().$value; ?>">
		<?php }} ?>

		
		<!-- Scripts Global -->

            <!-- custom -->
            <script src="<?php echo base_url(); ?>assets/build/js/custom.min.js"></script>


		    <!-- jQuery -->
		    <script src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
		    <!-- Bootstrap -->
		    <script src="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		    <!-- FastClick -->
		    <script src="<?php echo base_url(); ?>assets/vendors/fastclick/lib/fastclick.js"></script>
		    <!-- NProgress -->
		    <script src="<?php echo base_url(); ?>assets/vendors/nprogress/nprogress.js"></script>
		    <!-- iCheck -->
		    <script src="<?php echo base_url(); ?>assets/vendors/iCheck/icheck.min.js"></script>

		<!-- Scripts templating -->
		<?php if(isset($header['js'])){ foreach($header['js'] as $key=> $value){ ?>
		<script type="text/javascript" src="<?php echo base_url().$value; ?>"></script>
		<?php }} ?>

  	</head>

	<body class="nav-md">
	    <div class="container body">
	      <div class="main_container">	 

	<!-- Left bar -->
		<?php
		if(isset($main_content['left_bar']))
				{	
					$this->load->view($main_content['left_bar'],$main_content);
				}
		?>
	<!-- /Left bar -->

	<!-- /nav bar -->
		<?php
		if(isset($main_content['nav_bar']))
				{	
					$this->load->view($main_content['nav_bar'],$main_content);
				}
		?>
	<!-- /nav bar -->

	<!-- page content -->
		<?php
		if(isset($main_content['page_path']))
			{
				

				if(!is_array($main_content['page_path']))
				 $this->load->view($main_content['page_path'],$main_content);
				else
					foreach ($main_content['page_path'] as $key => $value) {
						$this->load->view($value,$main_content);
					}

				// <---pagination>
					if(isset($main_content['pagination']))
					{
				 		//set some defaults
				 			if(!isset($main_content['pagination']['page_path']) || empty($main_content['pagination']['page_path']) )
				 				$main_content['pagination']['page_path']="default";
				 			if(!isset($main_content['pagination']['current_page']))$main_content['pagination']['current_page']=1;
				 			if(!isset($main_content['pagination']['total_pages']))$main_content['pagination']['total_pages']=1;
				 			if(!isset($main_content['pagination']['href']))$main_content['pagination']['href']=base_url();
				 			if(!isset($main_content['pagination']['page_variable']))$main_content['pagination']['page_variable']='pg';


				 		//pagination	 	
				  		if(	$main_content['pagination']['page_path']!="default" && $main_content['pagination']['page_path']!=false )
						{
							$this->load->view($main_content['pagination']['page_path'],$main_content['pagination']);
						}else if(isset($main_content['pagination']))
						{
							$this->load->view("pagination/pagination_default_view",$main_content['pagination']);				
						}

					}
				// <---/pagination>	
			
				
			}
		?>
	<!-- /page content -->

	<!-- Right bar -->
		<?php
		if(isset($main_content['right_bar']))
			{
				$this->load->view($main_content['right_bar'],$main_content);
			}
		?>
	<!-- /Right bar -->

		</div>

	<!-- footer -->	
		<?php
		//footer
		if(isset($main_content['footer']))
			{
				$this->load->view($main_content['footer'],$main_content);
			}
		?>
	<!-- /footer -->



	      </div> 
	    </div>	 
	</body>
</html>