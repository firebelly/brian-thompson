<article <?php post_class(); ?>>
  <header>
    <h2 class="title"><a href="<?= get_the_permalink($post->ID); ?>"><?= get_the_title($post->ID) ?></a></h2>
    <?php include(locate_template('templates/entry-meta.php')); ?>
  </header>
</article>
