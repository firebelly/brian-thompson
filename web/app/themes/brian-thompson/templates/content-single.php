<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?> role="article">
    <?php the_post_thumbnail('large', ['class' => 'floater-image']); ?>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta-single'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <div class="nav-posts">
        <div class="main-area-wrap">
          <?php previous_post_link( '%link','<button class="prev-post"><div class="wrap">Prev Post</div></button>' ); ?>
          <?php next_post_link( '%link','<button class="next-post"><div class="wrap">Next Post</div></button>' ); ?>
        </div>
      </div>
      <?php include(locate_template('templates/share.php')); ?>
    </footer>
  </article>
<?php endwhile; ?>
