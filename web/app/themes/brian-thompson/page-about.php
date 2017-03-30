<?php 
use Firebelly\Utils; 
use Firebelly\Media;
?>

<?= Media\get_image_html( Media\get_treated_url(get_post_thumbnail_id( $post->ID ), ['type'=>'color']),'biopick','portrait' ); ?>
<?= apply_filters('the_content',$post->post_content) ?>