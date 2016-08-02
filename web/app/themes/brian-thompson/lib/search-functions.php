<?php

namespace Firebelly\Search;

/**
 * Custom li'l search excerpt function
 */

// Clean up content
function clean($content) {
    $content = strip_shortcodes( $content );
    $content = apply_filters( 'the_content', $content );
    $content = strip_tags( $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    return $content;
}

// Wrap WP search terms in a span.
function highlight_search_term( $content, $search_term ) {
  $keywords = get_keywords($search_term);

  foreach ($keywords as $keyword) {
    $content = preg_replace("/(".$keyword.")/i", "<span class=\"search-term-match highlight\">$1</span>", $content); 
  }

  $content = preg_replace("/(".$search_term.")/i", "<span class=\"search-term-match highlight\">$1</span>", $content); 
  return $content;
}

// Given a search term, what terms will WP search try to match?
function get_keywords($search_term) {
  $search_term = str_replace(['"',"'"], '', $search_term);
  $keywords = [];
  $keywords[] = $search_term;
  $keywords = array_merge( $keywords, explode(" ", $search_term ) );
  return $keywords;
}

// Recusrsive function that (after maybe_unserializing) will
// A: return the string, if it is passed a string
// B: loop through elements of an array and call itself on them, if it is passed an array
// The result is that a nested array with potential serialization at different levels is flattened into an array of strings
function return_string_or_unpack($thing) {
  // If it's a serialized string, unpack it.
  if (is_string($thing)) { 
    $thing = maybe_unserialize($thing); 
  }

  $return = [];
  // So now we either have an array or a string (or something else, in which case ignore it.)
  if (is_array($thing)) {
    foreach ($thing as $value) {
      $return = array_merge($return,return_string_or_unpack($value));
    }
  }
  if (is_string($thing)) {
    $return[] = $thing;
  }
  return $return;
}

// Gets an search excerpt surrounding the first found WP Search term with all search terms highlighted.  
// (Also checks custom fields.)
function get_search_excerpt($post, $search_term) {
    // Whare can the seach term be hiding?
    $hiding_places = [];
    $hiding_places[] = clean($post->post_content);

    // Get all strings in custom fields
    $custom = get_post_custom($post->ID);
    $custom_strings = return_string_or_unpack($custom);

    //Add those strings to our list of hiding places
    $hiding_places = array_merge($hiding_places, $custom_strings);

    $word_padding = 20;
    // Return first match
    $keywords = get_keywords($search_term);
    foreach ($keywords as $keyword) {
      foreach ($hiding_places as $hiding_place) {
        $location = stripos($hiding_place,$keyword);
        if ($location) {
          $before = substr($hiding_place, 0, $location);
          $after = substr($hiding_place, $location );

          $before_trim = strrev(wp_trim_words( strrev($before), $word_padding, '' )). //Double reverse to get LAST words of string.
            (preg_match('/\s/', substr($before, -1, 1))? ' ' : ''); //Append a space if it originally ended in a space (wp_trim will remove whitespace)
          $after_trim = wp_trim_words( $after , $word_padding, '' );

          $excerpt = '&hellip;'.$before_trim.$after_trim.'&hellip;';
          $excerpt = highlight_search_term( $excerpt, $search_term );

          return $excerpt;
        }
      }
    }
  return \Firebelly\Utils\get_excerpt( $post ); //Didn't find it for some reason?  Screw it.  The excerpt it is then.
}


/**
 * Bump up # search results TO INIFINITY
 */
function search_queries( $query ) {
  if ( !is_admin() && is_search() ) {
    $query->set( 'posts_per_page', -1 );
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\\search_queries' );

// /**
//  * Extend WordPress search to include custom fields
//  * http://adambalee.com
//  */

// /**
//  * Join posts and postmeta tables
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
//  */
// function search_join( $join ) {
//     global $wpdb;

//     if ( is_search() ) {    
//         $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
//     }
    
//     return $join;
// }
// add_filter('posts_join', __NAMESPACE__ . '\\search_join' );

// /**
//  * Modify the search query with posts_where
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
//  */
// function search_where( $where ) {
//     global $pagenow, $wpdb;
   
//     if ( is_search() ) {
//         $where = preg_replace(
//             "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
//             "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
//     }

//     return $where;
// }
// add_filter( 'posts_where', __NAMESPACE__ . '\\search_where' );

// /**
//  * Prevent duplicates
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
//  */
// function search_distinct( $where ) {
//     global $wpdb;

//     if ( is_search() ) {
//         return "DISTINCT";
//     }

//     return $where;
// }
// add_filter( 'posts_distinct', __NAMESPACE__ . '\\search_distinct' );