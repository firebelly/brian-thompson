<?php
/**
 * Various media functions
 */

namespace Firebelly\Media;
use Firebelly\SiteOptions;

// Compress jpegs
add_filter( 'jpeg_quality', create_function( '', 'return 90;' ) );


// Get Inline Images for Front Page;
function get_front_page_images() {

  global $post;

  $output = '';
  $featured = get_post_thumbnail_id($post->ID);
  $additional = get_post_meta( $post->ID, '_cmb2_additional_images', true );

  // Output featured image
  if($featured) {
    $output .= get_image_html( get_treated_url($featured, ['type'=>'gray']),'inline-image -one','portrait' );
    $output .= get_image_html( get_treated_url($featured, ['type'=>'gray']), 'inline-image mobile-image -top' );
  }

  // Output additional images
  if($additional) {
    $i = 0;
    foreach ( (array) $additional as $attachment_id => $attachment_url ) {
      switch ($i) {
        case 0:
          $output .= get_image_html( get_treated_url($attachment_id, ['type'=>'color']),'inline-image -two','landscape' );
          $output .= get_image_html( get_treated_url($attachment_id, ['type'=>'color']), 'inline-image mobile-image -bottom' );
          break;
        case 1:
          $output .= get_image_html( get_treated_url($attachment_id, ['type'=>'color']),'inline-image -three','portrait' );

          break;
        case 2:
          $output .= get_image_html( get_treated_url($attachment_id, ['type'=>'color']),'inline-image -four','landscape' );
          break;
        default:
          break;
      }
      $i++;
    }
  }

  return $output;
}


// Add bw checkbox to media library items:
// Adapted (stolen) from: https://gielberkers.com/add-checkbox-media-library-wordpress/
function add_checkboxes($form_fields, $post){
  $bw_checked = get_post_meta( $post->ID, 'bw_only', false ) ? 'checked="checked"' : '';
  $form_fields['bw_only'] = array(
      'label' => 'B/W Only',
      'input' => 'html',
      'html'  => "<input type=\"checkbox\"
          name=\"attachments[{$post->ID}][bw_only]\"
          id=\"attachments[{$post->ID}][bw_only]\"
          value=\"1\" {$bw_checked}/><br />");

  // $color_checked = get_post_meta( $post->ID, 'color_only', false ) ? 'checked="checked"' : '';
  // $form_fields['color_only'] = array(
  //     'label' => 'Color Only',
  //     'input' => 'html',
  //     'html'  => "<input type=\"checkbox\"
  //         name=\"attachments[{$post->ID}][color_only]\"
  //         id=\"attachments[{$post->ID}][color_only]\"
  //         value=\"2\" {$color_checked}/><br />");

  return $form_fields;
}
add_filter('attachment_fields_to_edit', __NAMESPACE__ . '\\add_checkboxes', 10, 2);

function save_checkboxes($post, $attachment){
    if(isset($attachment['bw_only'])) {
        update_post_meta($post['ID'], 'bw_only', 1);
    } else {
        update_post_meta($post['ID'], 'bw_only', 0);
    }
    // if(isset($attachment['color_only'])) {
    //     update_post_meta($post['ID'], 'color_only', 1);
    // } else {
    //     update_post_meta($post['ID'], 'color_only', 0);
    // }
}
add_filter('attachment_fields_to_save', __NAMESPACE__ . '\\save_checkboxes', 10, 2);

function is_bw_only($thumb_id) {
  return get_post_meta( $thumb_id, 'bw_only', false );
}



/**
 * Get thumbnail image for post
 * @param  integer $post_id
 * @return string image URL
 */
function get_post_thumbnail($post_id, $size='medium') {
  $return = false;
  if (has_post_thumbnail($post_id)) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    $return = $thumb[0];
  }
  return $return;
}

// Returns HTML for a floater image
function get_image_html ($url, $class = 'floater-image', $portrait_or_landscape = false) {
  $portrait_or_landscape = $portrait_or_landscape ? $portrait_or_landscape : (rand(0,1) ? 'portrait' : 'landscape');
  return '<div class="'.$class.' -'.$portrait_or_landscape.'" style="background-image: url('.$url.');"><img src="'.$url.'" class="sr-only"></div>';
}

// Get featured image and all additional images for post, add floater-image class
function get_floater_images($post_id = false) {
  // Default
  $post_id = $post_id ? $post_id : \get_the_ID();
  if (!$post_id) { return ''; } // Safety first

  $output = '';

  // Retrieve featured & additional images.  If neither set, take stock images
  $featured_id = get_post_thumbnail_id($post_id);
  $additional = \get_post_meta( $post_id, '_cmb2_additional_images', true );
  $additional_ids = $additional ? array_keys( $additional ) : false;

    // Choose the backup stock images if there will be any need
    $n_stock = count(SiteOptions\get_option('stock_images'));
    if( ( !$featured_id || !$additional ) && $n_stock ) {
      $stock_image_ids = array_keys( SiteOptions\get_option('stock_images') ); 
      shuffle( $stock_image_ids );
      // If user has selected featured image or additional images, remove them from the current stock pool!
      if ( $featured_id ) { 
        $stock_image_ids = array_diff($stock_image_ids, [$featured_id] ); 
      }
      if ( $additional_ids ) {
        $stock_image_ids = array_diff($stock_image_ids, $additional_ids ); 
      }
      // Replace empty featured and/or additional images with stock images
      if( !$featured_id ) {
        $featured_id = array_slice( $stock_image_ids, 0, 1)[0]; // Take first of shuffled for featured image
      }
      if( !$additional_ids ) {
        $additional_ids = array_slice( $stock_image_ids, 1, 3); // Take next 3 of shuffled for featured image
      }
    }

    // Output featured image
    if($featured_id) { // If there were not sufficient stock images our array_slice will return empty array
      $output .= get_image_html( get_treated_url($featured_id, ['type'=>(rand(0,1) ? 'color' : 'gray')]) );
      $output .= get_image_html( get_treated_url($featured_id, ['type'=>(rand(0,1) ? 'color' : 'gray')]), 'inline-image mobile-image -top' );
    }

    // Output additional images
    $i = 0;
    if($additional_ids) {
      foreach ( (array) $additional_ids as $additional_id ) {
        $output .= get_image_html( get_treated_url($additional_id, ['type'=>(rand(0,1) ? 'color' : 'gray')]) );
        if($i===0) { 
          $output .= get_image_html( get_treated_url($additional_id, ['type'=>(rand(0,1) ? 'color' : 'gray')]), 'inline-image mobile-image -bottom' );
        }
        $i++;
      }
    }

  return $output;
}

/**
 * Get the file path (not URL) to a thumbnail of a particular size.  
 * (get_attached_file() only returns paths to full-sized thumbnails.)  
 * @param  int            $thumb_id - attachment id of thumbnail
 * @param  string|array   $size - thumbnail size string (e.g. 'full') or array [w,h]
 * @return path           file path to properly sized thumbnail
 */
function get_thumbnail_size_path($thumb_id,$size) {
  // Find the path to the root image. We can get this from get_attached_file.
  $old_path = get_attached_file($thumb_id, true);

  // Find the url of the image with the proper size
  $attr = wp_get_attachment_image_src( $thumb_id , $size);
  $url = $attr[0];

  // Grab the filename of the sized image from the url
  $exploded_url =  explode ( '/' , $url );
  $filename = $exploded_url[ count($exploded_url)-1 ];

  // Replace the filename in our path with the filename of the properly sized image
  $exploded_path = explode ( '/' , $old_path );
  $exploded_path[count($exploded_path)-1] = $filename; 
  $new_path = implode ( '/' , $exploded_path );

  return $new_path;
}

/**
 * Get url for duo image, make duo image if non-existent
 * @param  int|object   $post_or_id (WP post object or image attachment id)
 * @return URL            background image code
 * e.g. get_treated_url($id, ['type'=>'color'])
 */
function get_treated_url($post_or_id, $options=[]) {

  // Handle options
  $defaults = ['type'=>'color', 'size'=>'large' ];
  $options = wp_parse_args($options,$defaults);
  $type = $options['type'];
  $size = $options['size'];


  // If WP post object, get the featured image
  if (is_object($post_or_id)) {
    if (has_post_thumbnail($post_or_id->ID)) {
      $thumb_id = get_post_thumbnail_id($post_or_id->ID);
    } else { 
      return false;  //thumbnail not found
    }
  } else {
    // Otherwise, id was sent directly
    $thumb_id = $post_or_id;
  }
  $full_image = get_attached_file($thumb_id, true); //this only returns images of size 'full'

  // Do not proceed if full image not found
  if (!file_exists($full_image)) { 
    return false; 
  } 

  // Override 'color' treatment type if image is bw
  if(is_bw_only($thumb_id) && $type === 'color') {
    $type = 'gray';
  }

  // // Override 'color' treatment type if image is color only
  // if(is_color_only($thumb_id) && $type === 'gray') {
  //   $type = 'color';
  // }


  // Get the image of proper size
  $image_to_convert = get_thumbnail_size_path($thumb_id,$size);

  // Do not proceed if sized image not found
  if (!file_exists($image_to_convert)) { 
    return false; 
  }

  $upload_dir = wp_upload_dir();
  $treated_dir = '/treated_'.$type.'/';
  $base_dir = $upload_dir['basedir'] . $treated_dir;

  // Build treated filename with thumb_id in case there are filename conflicts
  $treated_filename = preg_replace("/.+\/(.+)\.(\w{2,5})$/", $thumb_id."-$1-".$type.".$2", $image_to_convert);
  $treated_image = $base_dir . $treated_filename;

  // If treated file doesn't exist, create it
  if (!file_exists($treated_image)) {
    // If the duo directory doesn't exist, create it first
    if(!file_exists($base_dir)) {
      mkdir($base_dir);
    }

    // Build the ImageMagick convert command and execute
    $convert_command = (WP_ENV==='development') ? '/usr/local/bin/convert' : '/usr/bin/convert';
    $full_command = '';
    if ($type==='gray') {
      //convert keys.jpg -modulate 100,0 -size 256x1! gradient:#555555-#dddddd -clut keys-treated.jpg
      $full_command = $convert_command.' '.$image_to_convert.' -modulate 100,0 -size 256x1! gradient:#555555-#dddddd -clut -quality 75 '.$treated_image;
    } else {
      //convert peeps.jpg -brightness-contrast 3%x5% +level 10%,87%,.90 -channel B +level 5% peeps-treated.jpg
      $full_command = $convert_command.' '.$image_to_convert.' -brightness-contrast 3%x5% +level 10%,87%,.90 -channel B +level 5%,100% -quality 75 '.$treated_image;
    }

    if($full_command) { exec($full_command); }

  // echo '<script>console.log(\'MESSAGE FROM PHP:'.$full_command.'\');</script>';
  }

  // Finally, get the URL
  $duo_url = $upload_dir['baseurl'] . $treated_dir . $treated_filename;
  return $duo_url;
}

// Allow SVG via media uploader
function allow_svg($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', __NAMESPACE__ . '\\allow_svg');