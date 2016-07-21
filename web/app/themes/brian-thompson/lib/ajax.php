<?php
namespace Firebelly\Ajax;

/**
 * Add wp_ajax_url variable to global js scope
 */
function wp_ajax_url() {
  wp_localize_script('sage/js', 'wp_ajax_url', admin_url( 'admin-ajax.php'));
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\wp_ajax_url', 100);

/**
 * Silly ajax helper, returns true if xmlhttprequest
 */
function is_ajax() {
  return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * AJAX load more posts (news or events)
 */
function load_more_posts() {
  // news or projects?
  // get page offsets
  $page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  $per_page = !empty($_REQUEST['per_page']) ? $_REQUEST['per_page'] : get_option('posts_per_page');
  $category = !empty($_REQUEST['category']) ? $_REQUEST['category'] : '';
  $offset = ($page-1) * $per_page;
  $args = [
    'offset' => $offset,
    'posts_per_page' => $per_page,
  ];
  // Filter by Category?
  if (!empty($category)) {
    $args['cat'] = $category;
  }

  $posts = get_posts($args);

  if ($posts): 
    foreach ($posts as $post) {
      $blog_post = $post;
      echo '<li class="post columns-item">';
      include(locate_template('templates/content.php'));
      echo '</li>';
    }
  endif;

  // we use this call outside AJAX calls; WP likes die() after an AJAX call
  if (is_ajax()) die();
}
add_action( 'wp_ajax_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );


function load_more_button($orig_query=false) {

  //if a query obj is not provided, grab the global wp_query
  if (!$orig_query) {
    global $wp_query;
    $orig_query = $wp_query;
  }

  //stop if no posts
  if(!isset($orig_query->posts) && empty($orig_query->posts)){
    return '';
  }

  //extract query vars
  $category = isset($orig_query->queried_object->term_id) ? $orig_query->queried_object->term_id : '';
  // $search_query = isset($orig_query->query_vars['s']) ? $orig_query->query_vars['s'] : '';
  $per_page = isset($orig_query->query['posts_per_page']) ? $orig_query->query['posts_per_page'] : get_option( 'posts_per_page', 10 );
  // $orderby = isset($orig_query->query['orderby']) ? $orig_query->query['orderby'] : '';

  //get total post count for all posts in all pages of query
  $total_posts = wp_count_posts('post')->publish;
  $total_pages = ceil( $total_posts / $per_page);

  //return the markup
  $output = '<button class="load-more arrow -right -black -small" data-page-at="1" data-per-page="'.$per_page.'" data-total-pages="'.$total_pages.'" data-category="'.$category.'">See More Posts</div>';
  return $output;

}
