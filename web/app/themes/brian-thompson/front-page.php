<?php 
use Firebelly\Utils; 

// Page template as normal
include(locate_template('index.php')); 

// Add a recent posts module ?>
<h3><?php _e('Read the Latest', 'sage'); ?></h3>
  <ul class="recent-posts">
    <?php
    $args = [
      'post_type'   => 'post',
      'numberposts' => 3,
      'orderby'     => 'date',
    ];
    $recent_posts = get_posts($args);
    foreach ($recent_posts as $recent_post) : 
      $category = Utils\get_category( $recent_post );
      ?>
      <li class="recent-post">
        <article>
          <a href="<?= get_the_permalink($recent_post->ID); ?>"><h4><?= get_the_title($recent_post->ID) ?></h4></a>
          <?php if ($category) : ?>
            <a class="category" href="<?= get_term_link($category); ?>"><?= $category->name; ?></a>
          <?php endif; ?></p>
          <time datetime="<?= date('c', strtotime($recent_post->post_date)); ?>"><?= date('m.d.y', strtotime($recent_post->post_date)); ?></time>
        </article>
      </li>
    <?php endforeach;
