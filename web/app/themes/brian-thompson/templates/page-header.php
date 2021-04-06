<?php

use Roots\Sage\Titles;
use Firebelly\Media;

if ( !is_front_page() ) {
  if( ( is_home() || is_archive() ) && $id = get_option('page_for_posts') ) :
    echo Media\get_floater_images($id); // This is how we need to get the featured image if we are on the posts page or archive page.  the_post_thumbnail() won't do here.
  elseif ($post->post_name !== 'podcast'):
    echo Media\get_floater_images();
  elseif ($post->post_name == 'podcast'):
    $grayThumb = Media\get_treated_url($post, ['size' => 'large', 'type' => 'gray']);
    $colorThumb = Media\get_treated_url($post, ['size' => 'large', 'type' => 'color']);
    echo '<div class="podcast-page-thumbnail">';
    echo Media\get_image_html($grayThumb, "inline-image mobile-image -top landscape");
    echo '<div class="page-thumbnail">' . Media\get_image_html($colorThumb, "inline-image -one", "portrait") . '</div>';
    echo '<div class="desktop-only">';
    include(locate_template('/templates/newsletter-card.php'));
    echo '</div>';
    echo '</div>';
  endif;
}

if ( is_search() ) {
  echo '<div class="page-header">';
  echo '  <h1>Results for</h1>';
  get_search_form();
  echo '</div>';
} else {
  echo '<div class="page-header">';
  echo '  <h1>'.Titles\title().'</h1>';
  echo '</div>';
}




