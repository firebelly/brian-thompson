<article <?php post_class('archive-listing'); ?> role="article">
  <header>
    <h3 class="title"><a href="<?= get_the_permalink($post->ID); ?>"><?= get_the_title($post->ID) ?></a></h3>
    <?php include(locate_template('templates/entry-meta.php')); ?>
  </header>
</article>
