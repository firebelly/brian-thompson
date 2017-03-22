<?php

// Page template as normal
include(locate_template('index.php'));

echo Firebelly\Media\get_front_page_images();
?>

<button class="arrow -right -black -small open-popup" data-content="#make-an-appointment">Make an appointment</button>

<div class="section -two"><div class="wrap">
  <?= apply_filters('the_content', get_post_meta(get_the_ID(), '_cmb2_section2', true)); ?>
</div></div>

<div class="section -three"><div class="wrap">
  <?= apply_filters('the_content', get_post_meta(get_the_ID(), '_cmb2_section3', true)); ?>
</div></div>

<div class="section -four"><div class="wrap">
  <?= apply_filters('the_content', get_post_meta(get_the_ID(), '_cmb2_section4', true)); ?>
  <button class="arrow -right -black -small open-popup" data-content="#make-an-appointment">Make an appointment</button>
</div></div>

<div class="start-line" aria-hidden="true"></div>
<a href="<?= get_permalink( get_page_by_path( 'process' ) )?>" class="no-underline start-button"><button class="arrow -white -huge -right">Let's Start</button></a>

<?php include(locate_template('templates/recent-posts.php')); ?>

