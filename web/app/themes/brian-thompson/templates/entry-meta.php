<?php 
use Firebelly\Utils; 
if (!isset($blog_post)) {
  $blog_post = $post;
}
$category = Utils\get_category( $blog_post );
?>

<div class="meta">
  <p><?php if ($category) : ?>
      <a href="<?= get_term_link($category); ?>"><?= $category->name; ?></a>
     | <?php endif; ?><time datetime="<?= date('c', strtotime($blog_post->post_date)); ?>"><?= date('m.d', strtotime($blog_post->post_date)); ?></time></p>
</div>