
  <header class="header trans_300" style="height: 110px;">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="header_container d-flex flex-row align-items-center trans_300"  style="height: 110px;">

            <!-- Logo -->

            <div class="logo_container">
              <a href="<?php echo base_url(); ?>home">
                <div class="logo">
                  <img src="<?php echo IMAGE_SRC_URL;?>media/default/images/mpima_logo.jpg" alt="">
                  <span>Mpima Investments</span>
                </div>
              </a>
            </div>
            
            <!-- Main Navigation -->

            <nav class="main_nav">
              <ul class="main_nav_list">
                <li class="main_nav_item">
                  <a href="<?php echo base_url(); ?>listings/property" data-toggle="dropdown">listings</a>
                  <div class="dropdown-menu nav_dropdown" ">
                    <a class="dropdown-item" href="<?php echo base_url(); ?>listings/property">Property for Rent/sell</a>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>listings/land">Land for Sell</a>
                  </div>
                </li>
                <li class="main_nav_item"><a href="<?php echo base_url(); ?>contact">contact</a></li>
                <li class="main_nav_item"><a href="<?php echo base_url(); ?>about">about us</a></li>
              </ul>
            </nav>
            
            <!-- Phone Home -->

            <div class="phone_home text-center">
              <a href="<?php echo base_url(); ?>send_request"><span>Request a Property</span></a>
            </div>
            
            <!-- Hamburger -->

            <div class="hamburger_container menu_mm">
              <div class="hamburger menu_mm">
                <i class="fas fa-bars trans_200 menu_mm"></i>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Menu -->

    <div class="menu menu_mm">
      <ul class="menu_list">
        <li class="menu_item">
          <div class="container">
            <div class="row">
              <div class="col">
                <a href="#">home</a>
              </div>
            </div>
          </div>
        </li>
        <li class="menu_item">
          <div class="container">
            <div class="row">
              <div class="col">
                <a href="<?php echo base_url(); ?>listings/property">Property for Sell/Rent</a>
              </div>
            </div>
          </div>
        </li>
        <li class="menu_item">
          <div class="container">
            <div class="row">
              <div class="col">
                <a href="<?php echo base_url(); ?>listings/land">Land for sell</a>
              </div>
            </div>
          </div>
        </li>
        <li class="menu_item">
          <div class="container">
            <div class="row">
              <div class="col">
                <a href="<?php echo base_url(); ?>contact">contact</a>
              </div>
            </div>
          </div>
        </li>

        <li class="menu_item">
          <div class="container">
            <div class="row">
              <div class="col">
                <a href="<?php echo base_url(); ?>about">about us</a>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>

  </header>