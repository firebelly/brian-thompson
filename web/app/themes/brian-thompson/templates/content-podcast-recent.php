<?php
  use Firebelly\SiteOptions;
  use Firebelly\Media;

  if (has_post_thumbnail($post)) {
    $thumbnail = Media\get_post_thumbnail($post->ID);
  } else {
    $thumbnail = SiteOptions\get_option('default_podcast_image');
  }
?>

<article <?php post_class('archive-listing'); ?> role="article">
  <div class="header-image">
    <img src="<?= $thumbnail ?>" alt="<?= get_the_title ?> eiposde artwork">
  </div>
  <div class="header-text">
    <?php include(locate_template('templates/entry-meta-recent.php')); ?>
    <h5 class="title"><a href="<?= get_the_permalink($post->ID); ?>" class="no-underline"><?= get_the_title($post->ID) ?></a></h3>
  </div>
</article>
