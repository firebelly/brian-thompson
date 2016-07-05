<?php
/**
 * Process Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages\Process;

/**
 * Custom CMB2 fields for page
 */
function register_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $process_meta = new_cmb2_box( array(
    'id'            => 'process_metabox',
    'title'         => __( 'Process Steps', 'sage' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'process'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $step_group = $process_meta->add_field(
    array(
      'name'  => __( 'List of Steps', 'sage' ),
      'id'    => $prefix . 'steps',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Step {#}', 'sage' ),
        'add_button'    => __( 'Add Another Step', 'sage' ),
        'remove_button' => __( 'Remove Step', 'sage' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $process_meta->add_group_field( $step_group, array(
    'name' => __( 'Title', 'sage' ),
    'id'   => 'title',
    'type' => 'text',
  ) );
  $process_meta->add_group_field( $step_group, array(
    'name' => __( 'Description', 'sage' ),
    'id'   => 'description',
    'desc'  => __( 'This is the content that will be visible when the user triggers the popup for this step.', 'sage' ),
    'type' => 'wysiwyg',
    'options' => array(
      'textarea_rows' => get_option('default_post_edit_rows', 8), 
    ),
  ) );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

/**
 * Get template markup for steps
 */
function get_steps() {
  $steps = get_post_meta( get_the_ID(), '_cmb2_steps', true );
  $output = '';

  $output .= '<ul class="steps columns-wrap">';
  $i=1;
  foreach ( (array) $steps as $key => $step ) {

    $title = $excerpt = $full = '';

    $step_num = '<h2 class="big-title reveal-content">'.$i.'</h2>';
      $title = '<h3><a href="" class="reveal-content fake-link">'.esc_html( $step['title'] ).'</a></h3>';
      $content_to_reveal = '<div class="content-to-reveal"><h2 class="big-title">'.$i.'</h2><div class="description"><h3>'.esc_html( $step['title'] ).'</h3>'.apply_filters('the_content', $step['description'] ).'</div></div>';

    $output .= '<li class="step columns-item">'.$step_num.$title.$content_to_reveal.'</li>';

    $i++;
  }
  $output .= '</ul>';

  return $output;
}