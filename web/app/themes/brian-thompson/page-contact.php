<?php 
use Firebelly\Utils; 

// Page template as normal
include(locate_template('index.php')); 

?>

<div class="form-wrap">
  <?= apply_filters('the_content','[contact-form-7 id="91" title="Primary Contact Form"]'); ?>
</div>