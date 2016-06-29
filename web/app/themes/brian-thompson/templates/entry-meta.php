<?php 
use Firebelly\Utils; 
$category = Utils\get_category( $post );
?>

<div class="entry-meta">
  <p><?php if ($category) : ?>
      <a href="<?= get_term_link($category); ?>"><?= $category->name; ?></a>
     | <?php endif; ?><time datetime="<?= date('c', strtotime($post->post_date)); ?>"><?= date('m.d', strtotime($post->post_date)); ?></time></p>
</div>