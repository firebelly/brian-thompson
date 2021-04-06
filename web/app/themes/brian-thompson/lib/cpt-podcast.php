<?php
/**
 * Podcast post type
 */

namespace Firebelly\PostTypes\Podcast;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$names = [
  'name'     => 'podcast',
  'singular' => 'Podcast',
  'plural'   => 'Podcast',
  'slug'     => 'podcast',
];

$episodes = new PostType($names, [
  'taxonomies' => ['podcast_taxonomy'],
  'supports'   => ['title', 'editor', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);

// Custom taxonomies
$podcast_taxonomy = new Taxonomy('podcast_taxonomy');
$podcast_taxonomy->register();

$episodes->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $podcast_info = new_cmb2_box([
    'id'            => $prefix . 'podcast_info',
    'title'         => __( 'Podcast Info', 'cmb2' ),
    'object_types'  => ['podcast'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $podcast_info->add_field([
    'name'      => 'Type',
    'id'        => $prefix . 'podcast_type',
    'type'      => 'text_medium',
    'column'    => array( // adds this field to admin columns
      'position' => 2,
      'name'     => 'Type',
    ),
    // 'desc'      => 'Help text',
  ]);

}
// add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Get episodes
 */
function get_episodes($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = -1;
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'podcast',
  ];

  // Display all matching posts using article-{$post_type}.php
  $episodes_posts = get_posts($args);
  if (!$episodes_posts) return false;
  $output = '';
  foreach ($episodes_posts as $podcast_post):
    $podcast_post->column_width = $options['column-width'];
    ob_start();
    include(locate_template('templates/article-podcast.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
