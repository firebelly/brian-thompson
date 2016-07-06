<?php
/**
 * About Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\About;

/**
 * Custom CMB2 fields for page
 */
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list
  
  $about_meta = new_cmb2_box( array(
    'id'            => 'about_metabox',
    'title'         => __( 'Additional Content', 'sage' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'about'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $about_meta->add_field( 
    array(
      'name' => __( 'Bio', 'sage' ),
      'id'   => $prefix.'bio',
      'desc'  => __( 'This is the content that will be visible when the user triggers the bio popup.', 'sage' ),
      'type' => 'wysiwyg',
    )
  );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );
