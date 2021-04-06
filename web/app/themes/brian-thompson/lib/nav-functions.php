<?php

namespace Firebelly\Navs;

// Set current item class in nav for CPTs
function add_current_nav_class($classes, $item) {

	// Getting the current post details
	global $post;
  $current_post = get_permalink($post);

	// Getting the post type of the current post
	$current_post_type = get_post_type_object(get_post_type($post->ID));
	$current_post_type_slug = $current_post_type->rewrite['slug'];

	// Getting the URL of the menu item
	$menu_slug = strtolower(trim($item->url));

  // Get the page for posts slug
  $page_for_posts_id = get_option('page_for_posts');
  $page_for_posts_slug = get_permalink($page_for_posts_id);

  // For Blog Posts
  if ( ( is_search() ) && $item->url==='#search' ) {
    $classes[] = "current-menu-item";
  }

	// If the menu item URL contains the current post types slug add the current-menu-item class
	if (strpos($menu_slug,$current_post_type_slug) !== false || ( strpos($menu_slug, $page_for_posts_slug) !== false && strpos($current_post, 'posts') !== false ) ) {
    $classes[] = 'current-menu-item';
	} else {
    $classes = array_diff( $classes, ['current_page_parent'] );
	}

	// Return the corrected set of classes to be added to the menu item
	return $classes;
}
add_action('nav_menu_css_class', __NAMESPACE__ . '\\add_current_nav_class', 10, 2 );