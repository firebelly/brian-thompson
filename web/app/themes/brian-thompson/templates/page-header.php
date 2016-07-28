<?php 

use Roots\Sage\Titles; 
use Firebelly\Media;

if ( !is_front_page() ) {
  if( ( is_home() || is_archive() ) && $id = get_option('page_for_posts') ) :
    echo Media\get_floater_images($id); // This is how we need to get the featured image if we are on the posts page or archive page.  the_post_thumbnail() won't do here.
  else: 
    echo Media\get_floater_images();
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




