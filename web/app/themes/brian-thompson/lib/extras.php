<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Add <body> class for color scheme
 */
function color_scheme($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  if(isset($_GET['color'])) { 
    $classes[] = ($_GET['color'] === 'blue' ? 'blue' : 'white' ).'-color-scheme';
  } else {
    $classes[] = 'white-color-scheme';
  }


  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\color_scheme');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '" class="read-more"><button class="white-arrow">' . __('Read More', 'sage') . '</button></a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');
