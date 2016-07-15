<article <?php post_class('archive-listing'); ?> role="article">
  <header>
    <h5 class="title"><a href="<?= get_the_permalink($post->ID); ?>" class="no-underline"><?= get_the_title($post->ID) ?></a></h3>
    <?php include(locate_template('templates/entry-meta-recent.php')); ?>
  </header>
</article>
