<?php

use Firebelly\Media;

// Page template as normal
include(locate_template('index.php'));

echo Firebelly\Media\get_front_page_images();
?>

<button class="arrow -right -black -small open-popup" data-content="#appointments">Make an appointment</button>

<div class="section -two"><div class="wrap">
  <?= apply_filters('the_content', get_post_meta(get_the_ID(), '_cmb2_section2', true)); ?>
</div></div>

<div class="section -three"><div class="wrap">
  <?= apply_filters('the_content', get_post_meta(get_the_ID(), '_cmb2_section3', true)); ?>
</div></div>

<div class="section -four"><div class="wrap">
  <?= apply_filters('the_content', get_post_meta(get_the_ID(), '_cmb2_section4', true)); ?>
  <button class="arrow -right -black -small open-popup" data-content="#appointments">Make an appointment</button>
</div></div>

<div class="start-line" aria-hidden="true"></div>
<span class="open-popup no-underline start-button" data-content="#appointments"><button class="arrow -white -huge -right">Let's Start</button></span>

<div class="modal-pages">
  <?php

  $args = array(
      'post_type'      => 'page',
      'posts_per_page' => -1,
      'post_parent'    => $post->ID,
      'order'          => 'ASC',
      'orderby'        => 'menu_order'
   );


  $parent = new WP_Query( $args );

  if ( $parent->have_posts() ) : ?>

      <?php while ( $parent->have_posts() ) : $parent->the_post(); ?>

          <div id="<?= $post->post_name; ?>" class="sr-only linkable-popup">
              
              <?php get_template_part('page-'.$post->post_name); ?>

          </div>

      <?php endwhile; ?>

  <?php endif; wp_reset_query(); ?>
</div>