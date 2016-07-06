<?php
/**
 * General Page Meta/Admin Functions, Filters, Hooks
 */

namespace Firebelly\PostTypes\Pages;

/**
 * Add additional image dialogues to all pages
 */
function register_metaboxes() {
  $prefix = '_cmb2_';
  $add_images_meta = new_cmb2_box( array(
    'id'            => 'additional_images_metabox',
    'title'         => __( 'Additional Images', 'sage' ),
    'object_types'  => array( 'page', 'post'), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $add_images_meta->add_field(
    array(
      'name'             => __( 'Additional Images', 'sage' ),
      'desc'             => __( 'Any images you would like to be displayed about the page/post in ADDITION to the featured image.' ),
      'id'               => $prefix . 'additional_images',
      'type'             => 'file_list',
      'options'          => array(
        'url'            => false, // Hide the text input for the url
      ),
    )
  );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_metaboxes' );

/** 
 * ADAPTED FROM:
 * Metabox for Page Slug
 * @author Tom Morton
 * @link https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-show_on-filters
 *
 * @param bool $display
 * @param array $meta_box
 * @return bool display metabox
 */
function metabox_show_on_slug( $display, $meta_box ) {
    if ( ! isset( $meta_box['show_on']['key'], $meta_box['show_on']['value'] ) ) {
        return $display;
    }

    if ( 'slug' !== $meta_box['show_on']['key'] ) {
        return $display;
    }

    $post_id = 0;

    // If we're showing it based on ID, get the current ID
    if ( isset( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
    } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = $_POST['post_ID'];
    }

    if ( ! $post_id ) {
      return false; // In Morton's code, this WAS 'return $dispay;'-- but this caused all the metaboxes to appear for pages as they were being created, i.e. before they had proper slugs.
    }

    $slug = get_post( $post_id )->post_name;

    // See if there's a match
    return in_array( $slug, (array) $meta_box['show_on']['value']);
}
add_filter( 'cmb2_show_on', __NAMESPACE__ . '\metabox_show_on_slug', 10, 2 );

/**
 * Needed to change some CSS in the wordpress admin.  This is a quick hack to do so.
 */
function hack_in_admin_styling() {
  echo <<<HTML
  <style>
    .cmb-group-name {
      padding-left: 0 !important;
      font-weight: bold !important;
    }
  </style>
HTML;
}
add_action('admin_head', __NAMESPACE__ . '\hack_in_admin_styling');

/**
 * Hide editor on pages where we dont need it.
 * adapted from: https://gist.github.com/ramseyp/4060095
 */
function hide_editor() {
    // Leave if we are not editing a page...
    if( get_current_screen()->id != 'page') return;

    // Get the Post ID.
    if (isset($_GET['post']))
      $post_id = $_GET['post'];
    elseif (isset($_POST['post_ID']))
      $post_id = $_POST['post_ID'];
    else $post_id = false;

    // No post id? (e.g. editing a new, unsaved page) Get outta here!
    if(!$post_id) return;

    // Hide the editor on a page with a specific page template
    // Get the name of the Page Template file.
    $template_file = get_post_meta($post_id, '_wp_page_template', true);
    $post = get_post($post_id);
    $slug = $post->post_name;
    $exclude_on = array( // Any template filenames or slugs here will be have the editor excluded
      'portals',
      );
    if(in_array($template_file,$exclude_on) || in_array($slug,$exclude_on)){
      remove_post_type_support('page', 'editor');
    }
}
add_action( 'do_meta_boxes', __NAMESPACE__ . '\hide_editor' );