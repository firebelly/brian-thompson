<?php
/**
 * Portals Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Portals;

/**
 * Custom CMB2 fields for page
 */
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $portals_meta = new_cmb2_box( array(
    'id'            => 'portals_metabox',
    'title'         => __( 'Client Portals', 'sage' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'portals'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $portal_group = $portals_meta->add_field(
    array(
      'name'  => __( 'Client Portals', 'sage' ),
      'desc'  => __( 'All client portals that will appear on client portals page.', 'sage' ),
      'id'    => $prefix . 'portals',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Portal {#}', 'sage' ),
        'add_button'    => __( 'Add Another Portal', 'sage' ),
        'remove_button' => __( 'Remove This Portal', 'sage' ),
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
    'desc'  => __( 'Image to accompany portal.', 'sage' ),
    'id'   => 'thumbnail',
    'type' => 'file',
    'options'          => array(
      'url'            => false, // Hide the text input for the url
    ),
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Brief Description',
    'desc'  => __( 'Brief description (1 sentence or less) or each site.', 'sage' ),
    'id'   => 'description',
    'type' => 'textarea_small',
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Login URL',
    'desc'  => __( 'URL where clients can login.', 'sage' ),
    'id'   => 'url',
    'type' => 'text_url',
  ) );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

function get_portals() {
  $portals = get_post_meta(get_the_ID(),'_cmb2_portals',true);
  $output = '';

  $output.= '<ul class="portals-list columns-wrap">';
  foreach ($portals as $portal) {

    $thumbnail_url = \wp_get_attachment_image_src($portal['thumbnail_id'],'medium')[0];
    $description = apply_filters('the_content',$portal['description']);
    $url = esc_url($portal['url']);

    $output .= <<<HTML
      <li class="portal columns-item">
        <h3 class='sr-only'>{$portal['title']}</h3>
        <img src="{$thumbnail_url}" class="thumb">
        <p class="description">{$description}</p>
        <div class="login-wrap">
          <hr>
          <a href="{$url}" class="login no-underline" target="_blank"><button class="arrow -right -black -small">Login</button></a>
        </div>
      </li>
HTML;
  }
  $output .= '</ul>';

  return $output;

}