<?php

  get_template_part('templates/page', 'header');

  echo apply_filters('the_content',$post->post_content);

  $podcast_links = get_post_meta( $post->ID, '_cmb2_podcast_links', true );

  $podcast_episodes = query_posts(['post_type'=>'podcast']);
?>

<?php if (!empty($podcast_links)): ?>
<div class="button-container">
  <h4>Follow the podcast:</h4>
  <?php
    foreach ($podcast_links as $link) {
      echo '<a href="'.$link['url'].'" rel="noopener" target="_blank"><button class="arrow -right -black -small">'.$link['name'].'</button></a>';
    }
  ?>
</div>
<?php endif ?>

<?php if (!have_posts()) : ?>
  <h4> No episodes found.</h4>
    <p> How about some <a href="<?=get_permalink( get_option('page_for_posts' ) )?>">light reading</a> instead? </p>

<?php else : ?>
  <h4>Episodes</h4>

  <ul class="posts load-more-container">
    <?php while (have_posts()) : the_post(); ?>
      <li class="post">

        <?php
        $blog_post = $post;
        include(locate_template('templates/content-podcast.php'));
        ?>

      </li>
    <?php endwhile; ?>
  </ul>

  <?= \Firebelly\Ajax\load_more_button(); ?>

  <div class="newsletter-card-container mobile-only">
    <?php include(locate_template('templates/newsletter-card.php')); ?>
  </div>

<?php endif ?>
