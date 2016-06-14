<?php 
use Firebelly\Utils; 

// Page template as normal
include(locate_template('index.php')); 

// Add extra images for about page
$brian_alone_url = wp_get_attachment_image_src( get_post_meta( get_the_ID(), '_cmb2_brian_alone_id', true ) ,'large')[0]; 
$brian_family_url = wp_get_attachment_image_src( get_post_meta( get_the_ID(), '_cmb2_brian_family_id', true ),'large')[0]; 
?>

<img src="<?= $brian_alone_url ?>" class="brian-alone">
<img src="<?= $brian_family_url ?>" class="brian-family">