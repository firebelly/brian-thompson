<?php 

use Roots\Sage\Titles; 
use Firebelly\Media;

if( ( is_home() || is_archive() ) && $id = get_option('page_for_posts') ) :
  echo Media\get_floater_images($id); // This is how we need to get the featured image if we are on the posts page or archive page.  the_post_thumbnail() won't do here.
else: 
  echo Media\get_floater_images();
endif;
?>

<div class="page-header">
  <h1><?= Titles\title(); ?></h1>
</div>
