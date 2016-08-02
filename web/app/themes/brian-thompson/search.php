<?php get_template_part('templates/page', 'header'); 

$page_results = $post_results = [];

$search_query = isset($wp_query->query_vars['s']) ? $wp_query->query_vars['s'] : '';

?>

<?php if (!have_posts()) : ?>
  <h2> No posts found.</h2>
  <p> How about some <a href="<?=get_permalink( get_option('page_for_posts' ) )?>">light reading</a> instead? </p>
<?php else: ?>

  <?php
  while (have_posts()) { 
    the_post();
    if ($post->post_type == 'post') {
      $post_results[] = $post;
    }
    if ($post->post_type == 'page' or $post->post_type == 'service') {
      $page_results[] = $post;
    }
  } 
  ?>

  <?php if($page_results) : ?>
    <h2 class="results-type">Pages</h2>
    <ul class="posts columns-wrap">
      <?php foreach ($page_results as  $page_result) : ?>
        <li class="post columns-item">

          <?php 
          $blog_post = $page_result;
          include(locate_template('templates/content-search.php'));
          ?>

        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif ?>

  <?php if($post_results) : ?>
    <h2 class="results-type">Blog</h2>
    <ul class="posts columns-wrap">
      <?php foreach ($post_results as  $post_result) : ?>
        <li class="post columns-item">

          <?php 
          $blog_post = $post_result;
          include(locate_template('templates/content-search.php'));
          ?>

        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif ?>

<?php endif ?>