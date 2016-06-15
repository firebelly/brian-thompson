<?php

namespace Firebelly\Navs;

/**
 * Add current-menu-item class to blog link if we are on an archive page
 */
function highlight_blog_link( $classes, $item ) {
  if(is_category() || is_search() && $item->object_id===get_option('page_for_posts')) 
    $classes[] = "current-menu-item";
  return $classes;
}
add_filter( 'nav_menu_css_class' , __NAMESPACE__ . '\highlight_blog_link', 10, 2 );
