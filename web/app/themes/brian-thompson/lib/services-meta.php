<?php
/**
 * Services Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Services;

/**
 * Custom CMB2 fields for page
 */
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $services_meta = new_cmb2_box( array(
    'id'            => 'service_metabox',
    'title'         => __( 'Services', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'services'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $service_group = $services_meta->add_field(
    array(
      'name'  => __( 'List of Services', 'cmb2' ),
      'id'    => $prefix . 'services',
      'desc'  => __( 'The unfoldable "accordians" containing the services offered.', 'cmb2' ),
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Service {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Service', 'cmb2' ),
        'remove_button' => __( 'Remove Service', 'cmb2' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Title', 'cmb2' ),
    'id'   => 'title',
    'type' => 'text',
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Short Description', 'cmb2' ),
    'desc' => __( 'The brief description visible before the "accordian" is opened.', 'cmb2' ),
    'id'   => 'excerpt',
    'type' => 'wysiwyg',
    'options' => array(
      'media_buttons' => false, // show insert/upload button(s)
      'textarea_rows' => get_option('default_post_edit_rows', 8), // rows="..."
    ),
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Full Description', 'cmb2' ),
    'desc' => __( 'The complete description revealed to the user when they open the "accordian."', 'cmb2' ),
    'id'   => 'full',
    'type' => 'wysiwyg',
  ) );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

/**
 * Shortcode [services] gets template markup for services
 */
add_shortcode('services', __NAMESPACE__ . '\shortcode');
function shortcode() {

  $services = get_post_meta( get_the_ID(), '_cmb2_services', true );
  $output = '';


  $output .= '<ul class="services">';
  foreach ( (array) $services as $key => $service ) {
      $title = $excerpt = $full = '';
      if ( isset( $service['title'] ) )
        $title = '<h4>'.esc_html( $service['title'] ).'</h4>';
      if ( isset( $service['excerpt'] ) )
        $excerpt = '<div class="excerpt">'.apply_filters('the_content', $service['excerpt'] ).'</div>';
      if ( isset( $service['full'] ) )
        $full = '<div class="full">'.apply_filters('the_content', $service['full'] ).'</div>';
    $output .= '<li class="service">'.$title.$excerpt.$full.'</li>';
  }
  $output .= '</ul>';

  return $output;
}