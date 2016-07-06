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
    'title'         => __( 'Services', 'sage' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'services'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $service_group = $services_meta->add_field(
    array(
      'name'  => __( 'List of Services', 'sage' ),
      'id'    => $prefix . 'services',
      'desc'  => __( 'The unfoldable "accordians" containing the services offered.', 'sage' ),
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Service {#}', 'sage' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Service', 'sage' ),
        'remove_button' => __( 'Remove Service', 'sage' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Title', 'sage' ),
    'id'   => 'title',
    'type' => 'text',
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Short Description', 'sage' ),
    'desc' => __( 'The brief description visible from the page with no popup open.', 'sage' ),
    'id'   => 'excerpt',
    'type' => 'wysiwyg',
    'options' => array(
      'media_buttons' => false, // show insert/upload button(s)
      'textarea_rows' => get_option('default_post_edit_rows', 8), // rows="..."
    ),
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Full Description', 'sage' ),
    'desc' => __( 'The complete description revealed to the user when they open the popup.', 'sage' ),
    'id'   => 'full',
    'type' => 'wysiwyg',
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name'       => __( 'Pricing', 'sage' ),
    'desc'       => __( 'Price in this format, separating with double dashes: Item -- Price -- Note', 'sage' ),
    'id'         => 'price',
    'type'       => 'textarea_code',
    'repeatable' => true,
    'options' => array(
      'textarea_rows' => 1
    ),
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Pricing Note', 'sage' ),
    'id'   => 'pricing_note',
    'desc'  => __( 'Any (very short) notes you have on pricing', 'sage' ),
    'type' => 'wysiwyg',
  ) );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

/**
 * Shortcode [services] gets template markup for services
 */
add_shortcode('services', __NAMESPACE__ . '\services_shortcode');
function services_shortcode() {

  $services = get_post_meta( get_the_ID(), '_cmb2_services', true );
  $output = '';


  $output .= '<ul class="services">';
  foreach ( (array) $services as $i => $service ) {
    $title = $excerpt = $full = $pricing = '';
    if ( isset( $service['title'] ) )
      $title = esc_html( $service['title'] );
    if ( isset( $service['excerpt'] ) )
      $excerpt = '<div class="excerpt">'.apply_filters('the_content', $service['excerpt'] ).'</div>';
    if ( isset( $service['full'] ) )
      $full = apply_filters('the_content', $service['full'] );
    if ( isset( $service['price'] ) || isset( $service['pricing_note'] ) ) {
      $pricing .= '<ul class="pricetags">';
      if ( isset( $service['price'] ) ) {
        foreach ($service['price'] as $pricetag) {
          $pricetag_exploded = explode('--',$pricetag);
          $item = isset($pricetag_exploded[0]) ? '<p class="item">'.trim($pricetag_exploded[0]).'</p>' : '';
          $price = isset($pricetag_exploded[1]) ? trim($pricetag_exploded[1]) : '';
          $price_exploded = explode(' ',$price,2);
          $cost = isset($price_exploded[0]) ? '<p class="cost">'.trim($price_exploded[0]).'</p>' : '';
          $unit = isset($price_exploded[1]) ? '<p class="unit">'.trim($price_exploded[1]).'</p>' : '';
          $note = isset($pricetag_exploded[2]) ? '<p class="note">'.trim($pricetag_exploded[2]).'</p>' : '';

          $pricing .='<li class="pricetag">'.$item.$cost.$unit.$note.'</li>';
        }
      }
      if ( isset( $service['pricing_note'] ) ) {
        $pricing .='<li class="pricetag pricing-note"><div class="wrap">'.apply_filters('the_content', $service['pricing_note'] ).'</div></li>';
      }

      $pricing .= '</ul>';
    }

    $next = (($i+1) % count($services));
    $arrow='<button class="white-arrow switch-content next-content" data-content="#service-'.$next.'" aria-hidden="true">Next Service</button>';
    $content_to_reveal = '<div id="service-'.$i.'" class="sr-only"><h2>'.$title.'</h2>'.$full.$pricing.$arrow.'</div>';

    $output .= '<li class="service"><h2><a href="#" class="fake-link reveal-content" data-content="#service-'.$i.'">'.$title.'</a></h2>'.$excerpt.$content_to_reveal.'</li>';
  }
  $output .= '</ul>';

  return $output;
}