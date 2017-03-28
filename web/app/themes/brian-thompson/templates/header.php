<?php
use Firebelly\SiteOptions;
?>

<header class="site-header" role="banner">
  <h1 class="brand">
    <a href="<?= esc_url(home_url('/')); ?>">
      <span class="sr-only"><?= SiteOptions\get_option('org') ?></span>
      <svg class="btf-logo" role="img"><use xlink:href="#btt-logo"></use></svg>
    </a>
  </h1> 
  <div class="site-nav-bg"></div>
  <nav class="site-nav -big" role="navigation">
    <div class="menu-main-menu-container">    
      <ul id="menu-main-menu" class='nav popup-nav'>
        <li class="menu-item">
          <button class="open-popup" data-content="#about">About</button>
        </li>
        <li class="menu-item">
          <button class="open-popup" data-content="#services">Services</button>
        </li>
        <li class="menu-item">
          <button class="open-popup" data-content="#contact">Contact</button>
        </li>
        <li class="menu-item">
          <button class="open-popup" data-content="#appointments">Make An Appointment</button>
        </li>
      </ul>
    </div>
  </nav>
</header>

<div class="search-popup">
  <div class="body-wrap">
    <?php get_search_form() ?>
    <button class="close" aria-hidden="true">x</button>
  </div>
</div>
