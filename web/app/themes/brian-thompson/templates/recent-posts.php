<?php
$post_type = $post_type ? $post_type : 'post';
if ($post_type == 'podcast') {
  $postCount = 5;
  $recentTitle = 'More Episodes';
} else {
  $postCount = 2;
  $recentTitle = 'Latest News';
}

$args = [
  'post_type'   => $post_type,
  'numberposts' => $postCount,
  'orderby'     => 'date',
  'post__not_in' => array( $post->ID )
];
$recent_posts = get_posts($args);
?>

<?php if (!empty($recent_posts)) { ?>
<div class="recent-posts">
  <h4><?= $recentTitle ?></h4>

    <ul class="posts">
      <?php
      foreach ($recent_posts as $recent_post) :
        ?>
        <li class="post">

          <?php
            $post = $recent_post;

            if ($post_type == 'podcast') {
              include(locate_template('templates/content-podcast-recent.php'));
            } else {
              include(locate_template('templates/content-recent.php'));
            }
          ?>
        </li>
      <?php endforeach; ?>
    </ul>
</div>
<?php } ?>