<?php
/**
 * About Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\About;

// Custom CMB2 fields for post type
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list
  
  $about_meta = new_cmb2_box( array(
    'id'            => 'about_metabox',
    'title'         => __( 'Additional Images', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'about'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $about_meta->add_field(
    array(
      'name'             => __( 'Brian Alone', 'cmb2' ),
      'id'               => $prefix . 'brian_alone',
      'type'             => 'file',
      'options'          => array(
        'url'            => false, // Hide the text input for the url
      ),
    )
  );
  $about_meta->add_field(
    array(
      'name'             => __( 'Brian w/ Family', 'cmb2' ),
      'id'               => $prefix . 'brian_family',
      'type'             => 'file',
      'options'          => array(
        'url'            => false, // Hide the text input for the url
      ),
    )
  );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );
