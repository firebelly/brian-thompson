<?php 

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
      ?>
      <li class="recent-post">
        <?php $post = $recent_post; include(locate_template('templates/content.php')); ?>
      </li>
    <?php endforeach; ?>
  </ul>
