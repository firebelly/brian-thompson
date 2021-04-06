<?php
$post_type = $post_type ? $post_type : 'post';

$args = [
  'post_type'   => $post_type,
  'numberposts' => 2,
  'orderby'     => 'date',
];
$recent_posts = get_posts($args);
?>

<?php if (!empty($recent_posts)) { ?>
<div class="recent-posts">
  <?php
    if ($post_type == 'podcast') {
      echo '<h4>More Episodes</h4>';
    } else  {
      echo '<h4>Latest News</h4>';
    }
  ?>
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