<?php get_template_part('templates/page', 'header'); ?>

<?= apply_filters('the_content',get_post(get_option('page_for_posts'))->post_content); // We need to do this to get the content from the posts page.  I.e. the page "Blog."
?>

<?php include(locate_template('templates/category-nav.php')); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no posts were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
<?php endwhile; ?>
