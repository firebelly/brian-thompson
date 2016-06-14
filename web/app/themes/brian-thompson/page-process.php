<?php 
use Firebelly\PostTypes\Pages\Process;

// Page template as normal to start
include(locate_template('index.php')); 

// Get the process steps
echo Process\get_steps();
?>
