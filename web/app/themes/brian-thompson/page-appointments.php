<?php include(locate_template('index.php')); ?>

<div class="form-wrap">
  <h3>Calendly</h3>
    <iframe class="calendly" src="<?= esc_url('https://calendly.com/'.get_post_meta(get_the_ID(), '_cmb2_calendly', true)) ?>/" frameBorder="0" scrolling="yes"></iframe>
  <h3>Contact</h3>
  <?= apply_filters('the_content', get_post_meta(get_the_ID(),'_cmb2_contact_callout',true)); ?>
  <hr>
  <a href="<?= get_permalink(get_page_by_path('contact')) ?>" class="no-underline"><button class="contact-button black-arrow">Contact Brian</button></a>
</div>