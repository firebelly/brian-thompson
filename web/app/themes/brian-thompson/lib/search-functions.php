<?php

namespace Firebelly\Search;

/**
 * Custom li'l search excerpt function
 */

// Clean up content
function clean($content) {
    $content = strip_tags( $content );
    $content = strip_shortcodes( $content );
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
  $search_term = trim(str_replace(['"',"'"], '', $search_term));
  $keywords = [];
  $keywords[] = $search_term;
  $keywords = array_merge( $keywords, explode(" ", $search_term ) );
  return $keywords;
}

// Gets an search excerpt surrounding the first found WP Search term with all search terms highlighted.  
// (Also checks custom fields.)
function get_search_excerpt($post, $search_term) {
    // Whare can the seach term be hiding?
    $hiding_places = [];
    $hiding_places[] = clean($post->post_content);

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
