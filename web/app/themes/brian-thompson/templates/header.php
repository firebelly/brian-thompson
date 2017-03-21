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
    <?php
    if (has_nav_menu('primary_navigation')) :
      wp_nav_menu([
        'theme_location' => 'primary_navigation', 
        'menu_class' => 'nav',
        'depth' => 2,
      ]);
    endif;
    ?>
  </nav>
  <button class="open-search" aria-hidden="true">
    <svg class="icon-search"><use xlink:href="#icon-search"></use></svg>
  </button>
</header>

<div class="search-popup">
  <div class="body-wrap">
    <?php get_search_form() ?>
    <button class="close" aria-hidden="true">x</button>
  </div>
</div>
