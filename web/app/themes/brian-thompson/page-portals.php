<?php 
use Firebelly\PostTypes\Pages\Portals;

include(locate_template('index.php')); 

?>

<div class="form-wrap">
  <?= Portals\get_portals(); ?>
</div>
