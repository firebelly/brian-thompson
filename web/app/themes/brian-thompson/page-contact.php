<?php 
use Firebelly\Utils; 

?>

<?php get_template_part('templates/content', 'page'); ?>
<div class="form-wrap">
  <?= apply_filters('the_content','[contact-form-7 id="91" title="Primary Contact Form"]'); ?>
</div>