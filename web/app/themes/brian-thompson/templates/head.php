<?php
use Roots\Sage\Assets;
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= Roots\Sage\Assets\asset_path('images/favicon.png'); ?>">
  <?php wp_head(); ?>
    <!-- Google Analytics -->
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-48869649-1', 'auto');
    ga('send', 'pageview');
  </script>
  <!-- End Google Analytics -->
  <meta name="google-site-verification" content="q4jCIQyCLHUUgYeELkqYwMKQbVnBGP9hYetNmbthGHY" />
  <div id="breakpoint-indicator"></div>
</head>
