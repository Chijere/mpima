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
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/bootstrap4/bootstrap.min.css">
        <!-- Font Awesome -->
      <link href="<?php echo base_url(); ?>assets/vendors/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/OwlCarousel2-2.2.1/owl.carousel.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/OwlCarousel2-2.2.1/owl.theme.default.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/OwlCarousel2-2.2.1/animate.css">

      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/nav_bar.css">
      

         <!-- CSS templating -->
    <?php if(isset($css_links)){ foreach($css_links as $key=> $value){ ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url().$value; ?>">
    <?php }} ?>

    
    <!-- Scripts Global -->

        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>assets/vendors/jquery-3.2.1.min.js" ></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>assets/vendors/bootstrap4/popper.min.js" ></script>
        <script src="<?php echo base_url(); ?>assets/vendors/bootstrap4/bootstrap.min.js" ></script>
        
        

      <script src="<?php echo base_url(); ?>assets/vendors/greensock/TweenMax.min.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/greensock/TimelineMax.min.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/scrollmagic/ScrollMagic.min.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/greensock/animation.gsap.min.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/greensock/ScrollToPlugin.min.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/OwlCarousel2-2.2.1/owl.carousel.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/scrollTo/jquery.scrollTo.min.js" ></script>
      <script src="<?php echo base_url(); ?>assets/vendors/easing/easing.js" ></script>
    <!-- Scripts templating -->
    <?php if(isset($js_links)){ foreach($js_links as $key=> $value){ ?>
    <script type="text/javascript" src="<?php echo base_url().$value; ?>" ></script>
    <?php }} ?>    

    <!-- pass a tag -->
    <?php if(isset($tag)){ foreach($tag as $key=> $value){
      echo $value;  
     }} ?>
