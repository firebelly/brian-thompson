<?php get_template_part('templates/page', 'header'); ?>

<?php if(is_home()) { echo apply_filters('the_content',get_post(get_option('page_for_posts'))->post_content); } // We need to do this to get the content from the posts page.  I.e. the page "Blog."
?>

<?php include(locate_template('templates/category-nav.php')); ?>

<?php if (!have_posts()) : ?>
  <h2> No posts found.</h2>
    <p> How about some <a href="<?=get_permalink( get_option('page_for_posts' ) )?>">light reading</a> instead? </p>

<?php else : ?>

  <ul class="posts columns-wrap -first-wide load-more-container">
    <?php while (have_posts()) : the_post(); ?>
      <li class="post columns-item">

        <?php
        $blog_post = $post;
        include(locate_template('templates/content.php'));
        ?>

      </li>
    <?php endwhile; ?>
  </ul>

  <?= \Firebelly\Ajax\load_more_button(); ?>

<?php endif ?>
