<?php 
use Firebelly\Utils; 
use Firebelly\Media;

// echo '<pre><h1>'.get_post_meta( get_the_ID(), '_cmb2_bio_pick_id', true ).'</h1></pre>';

// Page template as normal
include(locate_template('index.php')); 
?>

<button class="arrow -right -black -small open-popup bio-button" data-content="#bio">Brian's Bio</button>
<div class="sr-only" id="bio">
  <?= Media\get_image_html( Media\get_treated_url(get_post_meta( get_the_ID(), '_cmb2_bio_pick_id', true ), ['type'=>'color']),'inline-image biopick','portrait' ); ?>
  <?= apply_filters('the_content',get_post_meta( get_the_ID(), '_cmb2_bio', true )); ?>
</div>