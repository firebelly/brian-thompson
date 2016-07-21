

<article class="<?= implode(' ',get_post_class('archive-listing', $blog_post->ID)); ?>" role="article">
  <header>
    <h2 class="title"><a href="<?= get_the_permalink($blog_post->ID); ?>"><?= get_the_title($post->ID) ?></a></h2>
    <?php include(locate_template('templates/entry-meta.php')); ?>
    <div class="summary">
      <?= Firebelly\Utils\get_excerpt($blog_post,30); ?>
    </div>
  </header>
  <a href="<?= get_the_permalink($blog_post->ID); ?>" class="read-more no-underline"><button class="arrow -white -right -small">Read More</button></a>
</article>

