<?php
/**
 * Appointments Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Appointments;

/**
 * Custom CMB2 fields for page
 */
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $appoinments_meta = new_cmb2_box( array(
    'id'            => 'appointments_metabox',
    'title'         => __( 'Calendly', 'sage' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'appointments'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $appoinments_meta->add_field(
    array(
      'name'             => __( 'Username', 'sage' ),
      'desc'             => __( 'Your Calendly username so we can embed the Calendly app.'),
      'id'               => $prefix . 'calendly',
      'type'             => 'text',
    )
  );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );
