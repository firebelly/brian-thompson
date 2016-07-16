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

<div class="start-line" aria-hidden="true"></div>
<a href="<?= get_permalink( get_page_by_path( 'process' ) )?>" class="no-underline"><button class="white-arrow start-button"><div class="wrap">Let's Start</div></button></a>

<?php include(locate_template('templates/recent-posts.php')); ?>

