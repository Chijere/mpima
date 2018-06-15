      <title>
      <?php if(isset($page_title)) echo($page_title); else echo "Mpima"; ?> 
      </title>

    
    <!-- Meta, title, CSS, favicons, etc. -->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="Mpima Investiments">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSS Global -->

      <!-- Bootstrap -->
      <link href="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome -->
      <link href="<?php echo base_url(); ?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <!-- NProgress -->
      <link href="<?php echo base_url(); ?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
      <!-- iCheck -->
      <link href="<?php echo base_url(); ?>assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">


         <!-- CSS templating -->
    <?php if(isset($css_links)){ foreach($css_links as $key=> $value){ ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url().$value; ?>">
    <?php }} ?>

    
    <!-- Scripts Global -->

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
    <?php if(isset($js_links)){ foreach($js_links as $key=> $value){ ?>
    <script type="text/javascript" src="<?php echo base_url().$value; ?>" ></script>
    <?php }} ?>

        <!-- pass a tag -->
    <?php if(isset($tag)){ foreach($tag as $key=> $value){
      echo $value;
     }} ?>

     
