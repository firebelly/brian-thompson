<header class="site-header" role="banner">
  <div class="brand-wrap">
    <div class="main-area-wrap">
      <h1 class="brand"><a href="<?= esc_url(home_url('/')); ?>">
        <svg class="btf-logo" role="img" width="101" height="53"><use xlink:href="#btf-logo"></use></svg>
      </a></h1>
    </div>
  </div>
  <div class="site-nav-wrap">
    <div class="main-area-wrap">
      <nav class="site-nav" role="navigation">
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
    </div>
  </div>
</header>