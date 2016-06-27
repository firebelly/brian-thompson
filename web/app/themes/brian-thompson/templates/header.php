<header class="site-header" role="banner">
    <h1 class="brand"><a href="<?= esc_url(home_url('/')); ?>">
      <svg class="btf-logo" role="img"><use xlink:href="#btf-logo"></use></svg>
    </a></h1>
    <div class="wrap">
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
</header>