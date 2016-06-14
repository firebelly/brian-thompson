<?php
/**
 * Contact Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Contact;

// Custom CMB2 fields for post type
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $contact_meta = new_cmb2_box( array(
    'id'            => 'content_metabox',
    'title'         => __( 'Contact Form Checklists', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'contact'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $interests_group = $contact_meta->add_field(
    array(
      'name'  => __( 'What specific services are you interested in?', 'cmb2' ),
      'desc'  => __( 'The list of checkbox items in the specific serves list for the contact form.', 'cmb2' ),
      'id'    => $prefix . 'interests',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Interest {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another Interest', 'cmb2' ),
        'remove_button' => __( 'Remove Interest', 'cmb2' ),
        'sortable'      => true, // beta
        'closed'     => true, // true to have the groups closed by default
      ),
    )
  );
  $contact_meta->add_group_field( $interests_group, array(
    'name' => 'Interest',
    'id'   => 'interest',
    'type' => 'text',
  ) );
  $checkany_group = $contact_meta->add_field(
    array(
      'name'  => __( 'Please check any that apply:', 'cmb2' ),
      'desc'  => __( 'The list of checkbox items in the check-any-that-apply list for the contact form.', 'cmb2' ),
      'id'    => $prefix . 'checkany',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Check Any That Apply {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another C.A.T.A.', 'cmb2' ),
        'remove_button' => __( 'Remove This C.A.T.A.', 'cmb2' ),
        'sortable'      => true, // beta
        'closed'     => true, // true to have the groups closed by default
      ),
    )
  );
  $contact_meta->add_group_field( $checkany_group, array(
    'name' => 'Name',
    'id'   => 'name',
    'type' => 'text',
  ) );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );
