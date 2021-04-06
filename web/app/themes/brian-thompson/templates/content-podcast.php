<?php
  use Firebelly\Media;
?>

<article class="<?= implode(' ',get_post_class('archive-listing', $blog_post->ID)); ?> bigclicky" role="article">
  <div class="header-image">
    <?php $thumbnail = Media\get_post_thumbnail($blog_post->ID); ?>
    <img src="<?= $thumbnail ?>" alt="<?= get_the_title ?> eiposde artwork">
  </div>
  <div class="header-text">
    <?php include(locate_template('templates/entry-meta.php')); ?>
    <h2 class="title"><a href="<?= get_the_permalink($blog_post->ID); ?>" class="no-underline"><?= get_the_title($blog_post->ID) ?></a></h2>
    <div class="summary">
      <p><?= Firebelly\Utils\get_excerpt($blog_post,30); ?></p>
    </div>
  </div>
</article>

