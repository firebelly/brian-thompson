<?php
/**
 * Footer Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Footer;

// Custom CMB2 fields for post type
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list
  
  $footer_meta = new_cmb2_box( array(
    'id'            => 'footer_metabox',
    'title'         => __( 'More Information', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'footer'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $footer_meta->add_field(
    array(
      'name'             => __( 'Copyright Statement', 'cmb2' ),
      'id'               => $prefix . 'copyright',
      'type'             => 'text',
    )
  );
  $footer_meta->add_field( array(
    'name' => 'Contact, Social Media, & ADV Part 2A',
    'desc' => 'Contact, Social Media, & ADV Part 2A are all editable from the main menu.',
    'type' => 'title',
    'id'   => $prefix . 'messagetoadmin'
) );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );
