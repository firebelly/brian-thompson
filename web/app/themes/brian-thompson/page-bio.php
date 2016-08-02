<?php 
use Firebelly\Utils; 
use Firebelly\Media;

$about_page = get_page_by_path( 'about' );
$about_url = get_the_permalink( $about_page );

wp_redirect( $about_url.'#bio' );