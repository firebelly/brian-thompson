<?php
/**
 * Home Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Home;

/**
 * Add additional content sections
 */
function register_metaboxes() {
  $prefix = '_cmb2_';
  $home_meta = new_cmb2_box( array(
    'id'            => 'home_metabox',
    'title'         => __( 'Additional Copy', 'sage' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'home'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $home_meta->add_field(
    array(
      'name'             => __( 'Section 2', 'sage' ),
      'desc'             => __( 'Second section of content on the home page' ),
      'id'               => $prefix . 'section2',
      'type'             => 'wysiwyg',
      'options'          => array(
        'textarea_rows'            => 6, // Hide the text input for the url
      ),
    )
  );
  $home_meta->add_field(
    array(
      'name'             => __( 'Section 3', 'sage' ),
      'desc'             => __( 'Third section of content on the home page' ),
      'id'               => $prefix . 'section3',
      'type'             => 'wysiwyg',
      'options'          => array(
        'textarea_rows'            => 6, // Hide the text input for the url
      ),
    )
  );
  $home_meta->add_field(
    array(
      'name'             => __( 'Section 4', 'sage' ),
      'desc'             => __( 'Third section of content on the home page' ),
      'id'               => $prefix . 'section4',
      'type'             => 'wysiwyg',
      'options'          => array(
        'textarea_rows'            => 6, // Hide the text input for the url
      ),
    )
  );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

