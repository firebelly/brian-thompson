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
 * Use the magic of cookies to get the correct color class 
 * so we alternate colors every page.
 */
function get_color_class() {
  $prev_color = !empty($_COOKIE['color']) ? $_COOKIE['color'] : 'blue';
  $curr_color = ($prev_color === 'white' ? 'blue' : 'white');
  setcookie('color', $curr_color, 0, "/");
  $_COOKIE['color'] = $curr_color; //setcookie won't repopulate $_COOKIE[]
  return $curr_color.'-color-scheme';
}

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '" class="read-more"><button class="white-arrow">' . __('Read More', 'sage') . '</button></a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');
