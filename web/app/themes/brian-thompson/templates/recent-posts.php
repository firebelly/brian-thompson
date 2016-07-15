<div class="recent-posts">
  <h4><?php _e('Latest News', 'sage'); ?></h3>
    <ul class="posts">
      <?php
      $args = [
        'post_type'   => 'post',
        'numberposts' => 2,
        'orderby'     => 'date',
      ];
      $recent_posts = get_posts($args);
      foreach ($recent_posts as $recent_post) : 
        ?>
        <li class="post">
          <?php $post = $recent_post; include(locate_template('templates/content-recent.php')); ?>
        </li>
      <?php endforeach; ?>
    </ul>
</div>