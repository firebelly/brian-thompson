<?php 
use Firebelly\Utils; 
use Firebelly\Media;

$services_page = get_page_by_path( 'services' );
$services_url = get_the_permalink( $services_page );

global $post;
$slug=$post->post_name;

wp_redirect( $services_url.'#'.$slug );

?>