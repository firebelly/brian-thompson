<?php get_template_part('templates/page', 'header'); ?>

<?php if(is_home()) { echo apply_filters('the_content',get_post(get_option('page_for_posts'))->post_content); } // We need to do this to get the content from the posts page.  I.e. the page "Blog."
?>

<?php include(locate_template('templates/category-nav.php')); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no posts were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php else : ?>

  <ul class="posts columns-wrap -first-wide">
    <?php while (have_posts()) : the_post(); ?>
      <li class="post columns-item">
        <?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
      </li>
    <?php endwhile; ?>
  </ul>
<?php endif ?>