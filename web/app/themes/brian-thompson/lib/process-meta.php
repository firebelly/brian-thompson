<?php
/**
 * Process Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Process;

// Custom CMB2 fields for post type
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $process_meta = new_cmb2_box( array(
    'id'            => 'process_metabox',
    'title'         => __( 'Process Steps', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'process'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $step_group = $process_meta->add_field(
    array(
      'name'  => __( 'List of Steps', 'cmb2' ),
      'id'    => $prefix . 'steps',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Step {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another Step', 'cmb2' ),
        'remove_button' => __( 'Remove Step', 'cmb2' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $process_meta->add_group_field( $step_group, array(
    'name' => __( 'Title', 'cmb2' ),
    'id'   => 'title',
    'type' => 'text',
  ) );
  $process_meta->add_group_field( $step_group, array(
    'name' => __( 'Description', 'cmb2' ),
    'id'   => 'description',
    'desc'  => __( 'This is the content that will be visible when the user triggers the popup for this step.', 'cmb2' ),
    'type' => 'wysiwyg',
    'options' => array(
      'textarea_rows' => get_option('default_post_edit_rows', 8), 
    ),
  ) );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

function get_steps() {
  $steps = get_post_meta( get_the_ID(), '_cmb2_steps', true );
  $output = '';



  $output .= '<ul class="steps">';
  $i=1;
  foreach ( (array) $steps as $key => $step ) {

    $title = $excerpt = $full = '';

    $step_num = '<h4>Step '.$i.'</h4>';
    if ( isset( $step['title'] ) )
      $title = '<h4>'.esc_html( $step['title'] ).'</h4>';
    if ( isset( $step['description'] ) )
      $description = '<div class="description">'.apply_filters('the_content', $step['description'] ).'</div>';

    $output .= '<li class="step">'.$step_num.$title.$description.'</li>';

    $i++;
  }
  $output .= '</ul>';

  return $output;
}