<h2>Make an appointment</h2>
  <iframe class="calendly" src="<?= esc_url('https://calendly.com/'.get_post_meta(get_the_ID(), '_cmb2_calendly', true)) ?>/" frameBorder="0" scrolling="yes"></iframe>
<h3>Contact</h3>
<?= apply_filters('the_content', get_post_meta(get_the_ID(),'_cmb2_contact_callout',true)); ?>
<hr>
<button class="contact-button arrow -right -black -small switch-content" data-content="#contact">Contact Brian</button>