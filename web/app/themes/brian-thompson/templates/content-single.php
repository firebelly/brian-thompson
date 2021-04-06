<?php
  use Firebelly\Media;
?>


<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?> role="article">
    <?= get_post_type($post->ID) == 'podcast' ? '' : Firebelly\Media\get_floater_images(); ?>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta-single'); ?>

      <?php
        if ( get_post_type($post) == 'podcast' ) {
          $thumbnail = Media\get_post_thumbnail($post->ID);
          echo '<div class="page-thumbnail">' . Media\get_image_html($thumbnail, "inline-image -one", "portrait") . '</div>';
        }
      ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php include(locate_template('templates/share.php')); ?>
      <div class="nav-posts">
          <?php previous_post_link( '%link','<button class="arrow -white  -huge -left"><div class="wrap">Prev Post</div></button>' ); ?>
          <?php next_post_link( '%link','<button class="arrow -white -huge -right"><div class="wrap">Next Post</div></button>' ); ?>
      </div>
    </footer>
  </article>
  <?php include(locate_template('templates/recent-posts.php')); ?>
<?php endwhile; ?>
