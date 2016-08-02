<?php 
use Firebelly\Utils; 
use Firebelly\Media;

$bio_page = get_page_by_path( 'about/bio' );

// Page template as normal
include(locate_template('index.php')); 
?>

<button class="arrow -right -black -small open-popup bio-button" data-content="#bio">Brian's Bio</button>
<div class="sr-only linkable-popup" id="bio">

  <?= Media\get_image_html( Media\get_treated_url(get_post_thumbnail_id( $bio_page->ID ), ['type'=>'color']),'inline-image biopick','portrait' ); ?>
  <?= apply_filters('the_content',$bio_page->post_content) ?>
</div>