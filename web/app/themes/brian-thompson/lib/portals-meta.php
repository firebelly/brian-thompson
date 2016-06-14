<?php
/**
 * Portals Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Portals;

// Custom CMB2 fields for post type
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $portals_meta = new_cmb2_box( array(
    'id'            => 'portals_metabox',
    'title'         => __( 'Client Portals', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'portals'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $portal_group = $portals_meta->add_field(
    array(
      'name'  => __( 'Client Portals', 'cmb2' ),
      'desc'  => __( 'All client portals that will appear on client portals page.', 'cmb2' ),
      'id'    => $prefix . 'portals',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Portal {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another Portal', 'cmb2' ),
        'remove_button' => __( 'Remove This Portal', 'cmb2' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Title',
    'desc' => 'I.e, "Client Dashboard," "Investment Portfolio"',
    'id'   => 'title',
    'type' => 'text',
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Image',
    'desc'  => __( 'Image to accompany portal.', 'cmb2' ),
    'id'   => 'thumbnail',
    'type' => 'file',
    'options'          => array(
      'url'            => false, // Hide the text input for the url
    ),
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Brief Description',
    'desc'  => __( 'Brief description (1 sentence or less) or each site.', 'cmb2' ),
    'id'   => 'description',
    'type' => 'textarea_small',
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Login URL',
    'desc'  => __( 'URL where clients can login.', 'cmb2' ),
    'id'   => 'url',
    'type' => 'text_url',
  ) );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );