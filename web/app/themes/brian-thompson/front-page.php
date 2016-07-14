<?php 

// Page template as normal
include(locate_template('index.php'));  

echo Firebelly\Media\get_front_page_images();
?>

<h1>Let’s work together.</h1>

<div class="section -one"><h2>Why Partner With Me?</h2>
<p>Because life happens. And smart, sensitive financial guidance makes all the difference when it comes to staying on track.</p></div>

<div class="section -two"><div class="wrap"><h2>Personalized 
Financial Planning</h2>
<p>Tailored services, simple pricing—get the targeted advice you need right now to manage your finances and make good choices.</p></div></div>

<div class="section -three"><div class="wrap"><h2>An Advocate for Life</h2>
<p>As your financial advisor, I’m also your ally. Your goals are my goals. I’m here to help, wherever life takes you. </p></div></div>

<div class="recent-posts">
  <h3><?php _e('Read the Latest', 'sage'); ?></h3>
    <ul class="posts">
      <?php
      $args = [
        'post_type'   => 'post',
        'numberposts' => 3,
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