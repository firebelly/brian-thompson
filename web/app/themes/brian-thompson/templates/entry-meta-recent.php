<?php 
use Firebelly\Utils; 
$category = Utils\get_category( $post );
?>

<div class="meta">
  <p class="category"><?php if ($category) : ?><a href="<?= get_term_link($category); ?>" class="no-underline"><?= $category->name; ?></a><?php endif; ?></p>
  <p class="date"><time datetime="<?= date('c', strtotime($post->post_date)); ?>"><?= date('m.d', strtotime($post->post_date)); ?></time></p>
</div>