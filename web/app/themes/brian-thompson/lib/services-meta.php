<?php
/**
 * Services Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Services;
use Firebelly\Utils; 

 /**
  * Register Custom Post Type For Services
  */
function post_type() {

  $labels = array(
    'name'                => 'Services',
    'singular_name'       => 'Service',
    'menu_name'           => 'Services',
    'parent_item_colon'   => '',
    'all_items'           => 'All Services',
    'view_item'           => 'View Service',
    'add_new_item'        => 'Add New Service',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Service',
    'update_item'         => 'Update Service',
    'search_items'        => 'Search Services',
    'not_found'           => 'Not found',
    'not_found_in_trash'  => 'Not found in Trash',
  );
  $rewrite = array(
    'slug'                => 'service-pages',
    'with_front'          => false,
    'pages'               => true,
    'feeds'               => true,
  );
  $args = array(
    'label'               => 'service',
    'description'         => 'Service',
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 20,
    'menu_icon'           => 'dashicons-admin-post',
    'can_export'          => false,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'page',
  );
  register_post_type( 'service', $args );
}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );


/**
 * Custom admin cols for post type
 */
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox">',
    'title' => 'Title',
    'content' => 'Description'
  );
  return $columns;
}
add_filter('manage_service_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'service' ) {
    if ( $column == 'featured_image' )
      echo the_post_thumbnail('thumbnail');
    elseif ( $column == 'content') {
      echo Utils\get_excerpt($post);
    } else {
      $custom = get_post_custom();
      if ( array_key_exists($column, $custom) )
        echo $custom[$column][0];
    }
  }
}
add_action('manage_posts_custom_column', __NAMESPACE__ . '\custom_columns');

/**
 * Custom CMB2 fields for services
 */
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $services_meta = new_cmb2_box( array(
    'id'            => 'service_metabox',
    'title'         => __( 'Services', 'sage' ),
    'object_types'  => array( 'service', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $services_meta->add_field( array(
    'name' => __( 'Short Description', 'sage' ),
    'desc' => __( 'The brief description visible from the page with no popup open.', 'sage' ),
    'id'   => $prefix.'excerpt',
    'type' => 'wysiwyg',
    'options' => array(
      'media_buttons' => false, // show insert/upload button(s)
      'textarea_rows' => get_option('default_post_edit_rows', 8), // rows="..."
    ),
  ) );
  $services_meta->add_field( array(
    'name'       => __( 'Pricing', 'sage' ),
    'desc'       => __( 'Item, Price, Note separated by new lines.', 'sage' ),
    'id'         => $prefix.'price',
    'type'       => 'textarea_code',
    'repeatable' => true,
    'options' => array(
      'textarea_rows' => 1
    ),
  ) );
  $services_meta->add_field( array(
    'name' => __( 'Pricing Note', 'sage' ),
    'id'   => $prefix.'pricing_note',
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


  $args= [
    'post_type' => 'service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
  ];
  $services = get_posts($args);

  $output = '';

  $output .= '<ul class="services">';
  foreach ( (array) $services as $i => $service ) {
    $title = $excerpt = $full = $pricing = $slug = '';

    $slug = $service->post_name;
    $title = esc_html( $service->post_title );
    $excerpt = '<div class="excerpt">'.apply_filters('the_content', get_post_meta( $service->ID, '_cmb2_excerpt', true ) ).'</div>';
    $full = apply_filters('the_content', $service->post_content );

    $pricetags = get_post_meta( $service->ID, '_cmb2_price', true );
    $pricing_note = get_post_meta( $service->ID, '_cmb2_pricing_note', true );
  
    if ( $pricetags || $pricing_note ) {
      $pricing .= '<ul class="pricetags -number-'.$i.'">';
      if ( $pricetags ) {
        foreach ($pricetags as $pricetag) {
          $pricetag_exploded = preg_split('/[\r|\n]+/',$pricetag);
          $item = isset($pricetag_exploded[0]) ? '<p class="item">'.trim($pricetag_exploded[0]).'</p>' : '';
          $price = isset($pricetag_exploded[1]) ? trim($pricetag_exploded[1]) : '';
          $price_exploded = explode(' ',$price,2);
          $cost = isset($price_exploded[0]) ? '<p class="cost">'.trim($price_exploded[0]).'</p>' : '';
          $unit = isset($price_exploded[1]) ? '<p class="unit">'.trim($price_exploded[1]).'</p>' : '';
          $note = isset($pricetag_exploded[2]) ? '<p class="note">'.trim($pricetag_exploded[2]).'</p>' : '';

          $pricing .='<li class="pricetag">'.$item.$cost.$unit.$note.'</li>';
        }
      }
      if ( $pricing_note ) {
        $pricing .='<li class="pricetag pricing-note"><div class="wrap">'.apply_filters('the_content', $pricing_note ).'</div></li>';
      }

      $pricing .= '</ul>';
    }

    $next = (($i+1) % count($services));
    $arrow='<button class="arrow -white switch-content next-content -right -big" data-content=".service-'.$next.'" aria-hidden="true">Next Service</button>';
    $content_to_reveal = '<div id="'.$slug.'" class="sr-only linkable-popup service-'.$i.'"><h2>'.$title.'</h2>'.$full.$pricing.$arrow.'</div>';

    $output .= '<li class="service"><h2><a href="#" class="fake-link open-popup" data-content=".service-'.$i.'">'.$title.'</a></h2>'.$excerpt.$content_to_reveal.'</li>';
  }
  $output .= '</ul>';

  return $output;
}