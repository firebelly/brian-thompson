<?php
  use Firebelly\Media;
?>

<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?> role="article">
    <div class="main-column">
      <header>
        <?php
          $thumbnail = Media\get_treated_url($post, ['size' => 'large', 'type' => 'gray']);
          echo '<div class="podcast-page-thumbnail">';
          echo Media\get_image_html($thumbnail, "inline-image mobile-image -top landscape");
          echo '</div>';
        ?>

        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry-meta-single'); ?>
      </header>

      <div class="entry-content">
        <?php the_content(); ?>
      </div>

      <footer>
        <?php include(locate_template('templates/share.php')); ?>
      </footer>
    </div>

    <aside>
      <?php
        $thumbnail = Media\get_post_thumbnail($post->ID);
        echo '<div class="page-thumbnail desktop-only">' . Media\get_image_html($thumbnail, "inline-image -one", "portrait") . '</div>';
      ?>

      <?php include(locate_template('templates/recent-posts.php')); ?>

      <?php include(locate_template('templates/newsletter-card.php')); ?>

    </aside>

  </article>
<?php endwhile; ?>
