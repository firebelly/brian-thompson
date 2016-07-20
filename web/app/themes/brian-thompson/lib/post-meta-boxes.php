<?php
/**
 * Extra fields for Posts
 */

namespace Firebelly\PostTypes\Posts;

// Get rid of post formats box (we are only using one type).
function remove_formats() {
   remove_theme_support('post-formats');
   remove_theme_support('post-formats');
}
add_action('after_setup_theme', __NAMESPACE__ . '\remove_formats', 100);


// Get rid of tags
function remove_tags() {
  remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
  remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // Tags Metabox
}
add_action('admin_menu', __NAMESPACE__ . '\remove_tags');

function remove_tag_col( $columns ) {
  unset($columns['tags']);
  return $columns;
}
add_filter( 'manage_posts_columns' , __NAMESPACE__ . '\remove_tag_col' );

//Get rid of author
function remove_author_col( $columns ) {
  unset($columns['author']);
  return $columns;
}
add_filter( 'manage_posts_columns' , __NAMESPACE__ . '\remove_author_col' );