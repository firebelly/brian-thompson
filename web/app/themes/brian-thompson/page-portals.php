<?php 
use Firebelly\PostTypes\Pages\Portals;

include(locate_template('index.php')); 

// Get the process steps
echo Portals\get_portals();

?>