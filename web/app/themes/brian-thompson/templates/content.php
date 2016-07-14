<article <?php post_class('archive-listing'); ?> role="article">
  <header>
    <h2 class="title"><a href="<?= get_the_permalink($post->ID); ?>"><?= get_the_title($post->ID) ?></a></h2>
    <?php include(locate_template('templates/entry-meta.php')); ?>
    <div class="summary">
      <?php the_excerpt(); ?>
      <a href="<?= get_permalink(); ?>" class="read-more"><button class="white-arrow">Read More</button></a>
    </div>
  </header>
</article>
