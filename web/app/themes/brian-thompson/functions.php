<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

$firebelly_includes = [
  'lib/disable-comments.php',          // Disables WP comments in admin and frontend
  'lib/fb_init.php',                   // FB theme setups
  'lib/fb_assets.php',                 // FB assets
  'lib/media.php',                     // FB media
  'lib/ajax.php',                      // AJAX functions
  'lib/custom-functions.php',          // Rando utility functions and miscellany
  'lib/cmb2-custom-fields.php',        // Custom CMB2
  'lib/page-meta.php',                 // General Page Meta/Admin Functions, Filters, Hooks
  'lib/home-meta.php',                // Home Page Meta/Admin Functions, Filters, Hooks
  'lib/services-meta.php',             // Services Page Meta/Admin Functions, Filters, Hooks
  'lib/process-meta.php',              // Process Page Meta/Admin Functions, Filters, Hooks
  'lib/appointments-meta.php',         // Appointments Page Meta/Admin Functions, Filters, Hooks
  'lib/portals-meta.php',              // Portals Page Meta/Admin Functions, Filters, Hooks
  'lib/footer-meta.php',               // Footer Page Meta/Admin Functions, Filters, Hooks
  'lib/post-meta-boxes.php',           // Post Meta/Admin Functions, Filters, Hooks
  'lib/site_options.php',              // Site Options
  'lib/nav-functions.php',             // Functions pertaining to navs
  'lib/fb_metatags.php',               // Generate metatags for social media sharing
  'lib/search-functions.php',          // Functions pertaining to search
  'lib/cpt-podcast.php',               // Podcast Custom Post Type
];

$sage_includes = array_merge($sage_includes, $firebelly_includes);

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);