<?php

namespace Firebelly\Utils;

/**
 * Custom li'l excerpt function
 */
function get_excerpt( $post, $length=15, $force_content=false ) {
  $excerpt = trim($post->post_excerpt);
  if (!$excerpt || $force_content) {
    $excerpt = $post->post_content;
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = apply_filters( 'the_content', $excerpt );
    $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
    $excerpt_length = apply_filters( 'excerpt_length', $length );
    $excerpt = wp_trim_words( $excerpt, $excerpt_length );
  }
  return $excerpt;
}

/**
 * Custom li'l search excerpt function
 */
function clean($content) {
    $content = strip_shortcodes( $content );
    $content = apply_filters( 'the_content', $content );
    $content = strip_tags( $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    return $content;
}
function highlight_search_term( $content, $search_term ) {
  $content = preg_replace("/(".$search_term.")/i", "<span class=\"search-term-match highlight\">$1</span>", $content); 
  return $content;
}
function get_search_excerpt($post, $search_term) {
    // Whare can the seach term be hiding?
    $hiding_places = [];
    $hiding_places[] = clean($post->post_content);

    // Get all strings in custom fields
    $customs = get_post_custom($post->ID);
    $custom_strings = [];
    foreach($customs as $custom) {

      if (is_string($custom)) {
        $custom_strings[] = clean($custom);
      } 

      elseif ( is_array($custom) ) {
        // Some of these are nested arrays
        foreach ($custom as $custom_value) {
          if (is_string($custom_value)) {
            $custom_strings[] = clean($custom_value);
          }
        }
      }

    }
    //Add those strings to our list of hiding places
    $hiding_places = array_merge($hiding_places, $custom_strings);

    $word_padding = 20;
    // Return first match
    foreach ($hiding_places as $hiding_place) {
      $location = stripos($hiding_place,$search_term);
      if ($location) {
        $before = substr($hiding_place, 0, $location);
        $after = substr($hiding_place, $location );

        $before_trim = strrev(wp_trim_words( strrev($before), 20, '' )). //Double reverse to get LAST words of string.
          (preg_match('/\s/', substr($before, -1, 1))? ' ' : ''); //Append a space if it originally ended in a space (wp_trim will remove whitespace)
        $after_trim = wp_trim_words( $after , 20, '' );

        $excerpt = '&hellip;'.$before_trim.$after_trim.'&hellip;';
        $excerpt = highlight_search_term( $excerpt, $search_term );

        return $excerpt;
      }
    }

  return get_excerpt( $post ); //Didn't find it for some reason?  Screw it.  The excerpt it is then.
}

/**
 * Get top ancestor for post
 */
function get_top_ancestor($post){
  if (!$post) return;
  $ancestors = $post->ancestors;
  if ($ancestors) {
    return end($ancestors);
  } else {
    return $post->ID;
  }
}

/**
 * Get first term for post
 */
function get_first_term($post, $taxonomy='category') {
  $return = false;
  if ($terms = get_the_terms($post->ID, $taxonomy))
    $return = array_pop($terms);
  return $return;
}

/**
 * Get page content from slug
 */
function get_page_content($slug) {
  $return = false;
  if ($page = get_page_by_path($slug))
    $return = apply_filters('the_content', $page->post_content);
  return $return;
}

/**
 * Get category for post
 */
function get_category($post) {
  if ($category = get_the_category($post)) {
    return $category[0];
  } else return false;
}

/**
 * Get num_pages for category given slug + per_page
 */
function get_total_pages($category, $per_page) {
  $cat_info = get_category_by_slug($category);
  $num_pages = ceil($cat_info->count / $per_page);
  return $num_pages;
}

/**
 * Get Page Blocks
 */
function get_page_blocks($post) {
  $output = '';
  $page_blocks = get_post_meta($post->ID, '_cmb2_page_blocks', true);
  if ($page_blocks) {
    foreach ($page_blocks as $page_block) {
      if (empty($page_block['hide_block'])) {
        $block_title = $block_body = '';
        if (!empty($page_block['title']))
          $block_title = $page_block['title'];
        if (!empty($page_block['body'])) {
          $block_body = apply_filters('the_content', $page_block['body']);
          $output .= '<div class="page-block">';
          if ($block_title) {
            $output .= '<h2 class="flag">' . $block_title . '</h2>';
          }
          $output .= '<div class="user-content">' . $block_body . '</div>';
          $output .= '</div>';
        }
      }
    }
  }
  return $output;
}

