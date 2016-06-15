<?php include(locate_template('index.php')); ?>

<iframe src="https://calendly.com/<?= get_post_meta(get_the_ID(), '_cmb2_calendly', true) ?>/" style="width:100%; height:650px;" frameBorder="0" scrolling="yes"></iframe>