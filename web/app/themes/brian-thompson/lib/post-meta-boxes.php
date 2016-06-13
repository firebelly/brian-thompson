<?php
/**
 * Extra fields for Posts
 */

namespace Firebelly\PostTypes\Posts;

// Get rid of post formats box (we are only using one type).
function remove_formats() {
   remove_theme_support('post-formats');
}
add_action('after_setup_theme', __NAMESPACE__ . '\remove_formats', 100);