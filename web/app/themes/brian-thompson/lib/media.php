<?php
/**
 * Various media functions
 */

namespace Firebelly\Media;

// image size for popout thumbs
add_image_size( 'popout-thumb', 250, 300, ['center', 'top'] );

/**
 * Get thumbnail image for post
 * @param  integer $post_id
 * @return string image URL
 */
function get_post_thumbnail($post_id, $size='medium') {
  $return = false;
  if (has_post_thumbnail($post_id)) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    $return = $thumb[0];
  }
  return $return;
}

// Get featured image and all additional images for post, add floater-image class
function get_floater_images($post_id = false) {
  // Default
  $post_id = $post_id ? $post_id : \get_the_ID();
  if (!$post_id) { return ''; } // Safety first

  $output = '';
  $output .= \get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'floater-image' ) );

  $files = \get_post_meta( $post_id, '_cmb2_additional_images', true );
  // Loop through them and output an image
  foreach ( (array) $files as $attachment_id => $attachment_url ) {
    $output .=  \wp_get_attachment_image( $attachment_id, 'large', false, array( 'class' => 'floater-image' ) );
  }

  return $output;
}

