<?php use Roots\Sage\Titles; ?>

<?php 
if( ( is_home() || is_archive() ) && $id = get_option('page_for_posts') ) :
  echo get_the_post_thumbnail( $id, 'large' ); // This is how we need to get the featured image if we are on the posts page or archive page.  the_post_thumbnail() won't do here.
else: 
  the_post_thumbnail('large');
endif;
?>

<div class="page-header">
  <h1><?= Titles\title(); ?></h1>
</div>
