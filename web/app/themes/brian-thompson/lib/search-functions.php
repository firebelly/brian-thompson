<?php

namespace Firebelly\Search;

/**
 * Custom li'l search excerpt function
 */

// Clean up some content
function clean($content) {
    $content = strip_tags( $content );
    $content = strip_shortcodes( $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    return $content;
}

// Wrap WP search terms in a span.
function highlight_search_term( $content, $search_query ) {
  // What are all the possible keywords that WP will look for given the searh_query?
  $keywords = get_keywords($search_query);

  // Loop through them all and add OR ('|') delimiters so we can put it into preg_replace
  $keyword_regex = '';
  foreach ($keywords as $keyword) {
    $keyword_regex = preg_quote($keyword).'|';
  }
  $keyword_regex = rtrim($keyword_regex, '|'); //Get rid of the final extra  '|', if there.

  // Wrap all matches in our highlight span
  $content = preg_replace("/(".$keyword_regex.")/i", "<span class=\"search-term-match highlight\">$1</span>", $content); 

  return $content;
}

// Given a search term, what terms will WP search try to match?
function get_keywords($search_query) {
  // Clean up the search_query
  $search_query = esc_html($search_query);
  $search_query = str_replace(['"',"'"], '', $search_query);
  $search_query = trim($search_query);
  $search_query = preg_replace('/\s+/', ' ', $search_query);

  // From what I've read, WP searches the string itself, and each individual word in the string, so put all those options in an $keywords array.
  $keywords = [];
  $keywords[] = $search_query;
  $keywords = array_merge( $keywords, explode(" ", $search_query ) );

  return $keywords;
}

// Gets a search excerpt surrounding the first found WP Search term.  All containted search terms will be highlighted.
function get_search_excerpt($post, $search_query) {
    // Whare can the seach term be hiding?
    $hiding_places = [];
    $hiding_places[] = clean($post->post_content);

    $word_padding = 20;  // Number of words on each side of matched string
    // Return first match
    $keywords = get_keywords($search_query); // What was WP matching in the search?  We'll match for it, too!
    foreach ($keywords as $keyword) { // Check each keyword
      foreach ($hiding_places as $hiding_place) { //Check each hiding place
        $location = stripos($hiding_place,$keyword);
        if ($location) { // If found...
          $before = substr($hiding_place, 0, $location);
          $after = substr($hiding_place, $location );

          //Hackily use wp_trim_words to trim by WORDS instead of characters
          $before_trim = strrev(wp_trim_words( strrev($before), $word_padding, '' )). //Double reverse to get LAST words of string. --HACKY
            (preg_match('/\s/', substr($before, -1, 1))? ' ' : ''); //Append a space if it originally ended in a space (wp_trim will remove whitespace)
          $after_trim = wp_trim_words( $after , $word_padding, '' );

          // Make our excerpt
          $excerpt = '&hellip;'.$before_trim.$after_trim.'&hellip;';
          $excerpt = highlight_search_term( $excerpt, $search_query );

          // We found a match, we are done.
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
