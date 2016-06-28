<?php 
use Firebelly\Utils; 
$category = Utils\get_category( $post );
?>

<p class="small-mono"><time datetime="<?= date('c', strtotime($post->post_date)); ?>"><?= date('m.d.y', strtotime($post->post_date)); ?></time></p>
<p class="small-mono"><?php if ($category) : ?>
    <a class="category" href="<?= get_term_link($category); ?>"><?= $category->name; ?></a>
  <?php endif; ?></p>