

<article class="<?= implode(' ',get_post_class('archive-listing', $blog_post->ID)); ?>" role="article">
  <header>
    <h3 class="title"><a href="<?= get_the_permalink($blog_post->ID); ?>" class="no-underline"><?= Firebelly\Search\highlight_search_term(get_the_title($blog_post->ID),$search_query) ?></a></h3>
    <?php include(locate_template('templates/entry-meta.php')); ?>
    <div class="summary">
      <?= Firebelly\Search\get_search_excerpt($blog_post,$search_query); ?>
    </div>
  </header>
  <a href="<?= get_the_permalink($blog_post->ID); ?>" class="read-more no-underline"><button class="arrow -white -right -small">Read More</button></a>
</article>

