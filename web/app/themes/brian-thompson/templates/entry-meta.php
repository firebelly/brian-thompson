<?php 
use Firebelly\Utils; 
$category = Utils\get_category( $post );
?>

<time datetime="<?= date('c', strtotime($post->post_date)); ?>"><?= date('m.d.y', strtotime($post->post_date)); ?></time>
<p><?php if ($category) : ?>
    <a class="category" href="<?= get_term_link($category); ?>"><?= $category->name; ?></a>
  <?php endif; ?></p>
<!-- <p class="byline author vcard"><?= __('By', 'sage'); ?> <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?= get_the_author(); ?></a></p> -->
