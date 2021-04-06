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
  return '&hellip;';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

// Get rid of annoying nbsp characters that wfpc7 outputs and mess up formatting
function remove_spaces_from_wpfc7( $content ) {

  $find = '&nbsp;';
  $replace = '';
  $content = str_replace( $find, $replace, $content);

  return $content;
}
add_filter( 'wpcf7_form_elements', __NAMESPACE__ . '\\remove_spaces_from_wpfc7' );