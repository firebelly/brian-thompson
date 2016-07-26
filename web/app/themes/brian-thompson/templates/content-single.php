<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?> role="article">
    <?= Firebelly\Media\get_floater_images(); ?>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta-single'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php include(locate_template('templates/share.php')); ?>
      <div class="nav-posts">
          <?php previous_post_link( '%link','<button class="prev-post arrow -white  -huge -left"><div class="wrap">Prev Post</div></button>' ); ?>
          <?php next_post_link( '%link','<button class="next-post arrow -white -huge -right"><div class="wrap">Next Post</div></button>' ); ?>
      </div>
    </footer>
  </article>
  <?php include(locate_template('templates/recent-posts.php')); ?>
<?php endwhile; ?>
