<?php 
use Firebelly\Utils; 

// Page template as normal
include(locate_template('index.php')); 

// Add extra images for about page
$brian_alone_url = wp_get_attachment_image_src( get_post_meta( get_the_ID(), '_cmb2_brian_alone_id', true ) ,'large')[0]; 
$brian_family_url = wp_get_attachment_image_src( get_post_meta( get_the_ID(), '_cmb2_brian_family_id', true ),'large')[0]; 
?>

<button class="black-arrow reveal-content bio-button" data-content="#bio">Brian's Bio</button>
<div class="sr-only" id="bio"><?= apply_filters('the_content',get_post_meta( get_the_ID(), '_cmb2_bio', true )); ?></div>