<?php
use Firebelly\Utils;
$category = Utils\get_category( $post );
?>

<div class="entry-meta">
  <div class="date"><p><time datetime="<?= date('c', strtotime($post->post_date)); ?>"><?= date('m.d.y', strtotime($post->post_date)); ?></time></p></div>
  <?php if ($category) : ?>
    <div class="category">
      <p><a href="<?= get_term_link($category); ?>"><?= $category->name; ?></a></p>
    </div>
  <?php endif; ?>
</div>