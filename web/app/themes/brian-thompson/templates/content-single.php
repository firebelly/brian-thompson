<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?> role="article">
    <?php the_post_thumbnail('large', ['class' => 'floater-image']); ?>
    <?php the_post_thumbnail('large', ['class' => 'floater-image']); ?>
    <?php the_post_thumbnail('large', ['class' => 'floater-image']); ?>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <div class="nav-posts">
        <div class="main-area-wrap">
          <?php previous_post_link( '%link','<div class="prev-post"><div class="arrow-wrap">Prev Post</div></div>' ); ?>
          <?php next_post_link( '%link','<div class="next-post"><div class="arrow-wrap">Next Post</div></div>' ); ?>
        </div>
      </div>
    </footer>
  </article>
<?php endwhile; ?>
