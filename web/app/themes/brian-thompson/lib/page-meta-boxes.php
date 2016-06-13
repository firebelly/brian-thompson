<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

// Custom CMB2 fields for post type
function register_page_metaboxes() {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list
  
  /**
   * About Page
   */
  $about_meta = new_cmb2_box( array(
    'id'            => 'about_metabox',
    'title'         => __( 'Additional Images', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'about'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $about_meta->add_field(
    array(
      'name'             => __( 'Brian Alone', 'cmb2' ),
      'id'               => $prefix . 'brian_alone',
      'type'             => 'file',
      'options'          => array(
        'url'            => false, // Hide the text input for the url
      ),
    )
  );
  $about_meta->add_field(
    array(
      'name'             => __( 'Brian w/ Family', 'cmb2' ),
      'id'               => $prefix . 'brian_family',
      'type'             => 'file',
      'options'          => array(
        'url'            => false, // Hide the text input for the url
      ),
    )
  );

  /**
   * Services Page
   */
  $services_meta = new_cmb2_box( array(
    'id'            => 'service_metabox',
    'title'         => __( 'Services', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'services'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $service_group = $services_meta->add_field(
    array(
      'name'  => __( 'List of Services', 'cmb2' ),
      'id'    => $prefix . 'services',
      'desc'  => __( 'The unfoldable "accordians" containing the services offered.', 'cmb2' ),
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Service {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Service', 'cmb2' ),
        'remove_button' => __( 'Remove Service', 'cmb2' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Title', 'cmb2' ),
    'id'   => 'title',
    'type' => 'text_medium',
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Short Description', 'cmb2' ),
    'desc' => __( 'The brief description visible before the "accordian" is opened.', 'cmb2' ),
    'id'   => 'excerpt',
    'type' => 'wysiwyg',
    'options' => array(
      'media_buttons' => false, // show insert/upload button(s)
      'textarea_rows' => get_option('default_post_edit_rows', 8), // rows="..."
    ),
  ) );
  $services_meta->add_group_field( $service_group, array(
    'name' => __( 'Full Description', 'cmb2' ),
    'desc' => __( 'The complete description revealed to the user when they open the "accordian."', 'cmb2' ),
    'id'   => 'full',
    'type' => 'wysiwyg',
  ) );

  /**
   * Process Page
   */
  $process_meta = new_cmb2_box( array(
    'id'            => 'process_metabox',
    'title'         => __( 'Process Steps', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'process'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $step_group = $process_meta->add_field(
    array(
      'name'  => __( 'List of Steps', 'cmb2' ),
      'id'    => $prefix . 'steps',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Step {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another Step', 'cmb2' ),
        'remove_button' => __( 'Remove Step', 'cmb2' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $process_meta->add_group_field( $step_group, array(
    'name' => __( 'Title', 'cmb2' ),
    'id'   => 'title',
    'type' => 'text_medium',
  ) );
  $process_meta->add_group_field( $step_group, array(
    'name' => __( 'Description', 'cmb2' ),
    'id'   => 'description',
    'desc'  => __( 'This is the content that will be visible when the user triggers the popup for this step.', 'cmb2' ),
    'type' => 'wysiwyg',
    'options' => array(
      'textarea_rows' => get_option('default_post_edit_rows', 8), 
    ),
  ) );

  /**
   * Contact Page
   */
  $contact_meta = new_cmb2_box( array(
    'id'            => 'content_metabox',
    'title'         => __( 'Contact Form Checklists', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'contact'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $interests_group = $contact_meta->add_field(
    array(
      'name'  => __( 'What specific services are you interested in?', 'cmb2' ),
      'desc'  => __( 'The list of checkbox items in the specific serves list for the contact form.', 'cmb2' ),
      'id'    => $prefix . 'interests',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Interest {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another Interest', 'cmb2' ),
        'remove_button' => __( 'Remove Interest', 'cmb2' ),
        'sortable'      => true, // beta
        'closed'     => true, // true to have the groups closed by default
      ),
    )
  );
  $contact_meta->add_group_field( $interests_group, array(
    'name' => 'Interest',
    'id'   => 'interest',
    'type' => 'text_medium',
  ) );
  $checkany_group = $contact_meta->add_field(
    array(
      'name'  => __( 'Please check any that apply:', 'cmb2' ),
      'desc'  => __( 'The list of checkbox items in the check-any-that-apply list for the contact form.', 'cmb2' ),
      'id'    => $prefix . 'checkany',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Check Any That Apply {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another C.A.T.A.', 'cmb2' ),
        'remove_button' => __( 'Remove This C.A.T.A.', 'cmb2' ),
        'sortable'      => true, // beta
        'closed'     => true, // true to have the groups closed by default
      ),
    )
  );
  $contact_meta->add_group_field( $checkany_group, array(
    'name' => 'Name',
    'id'   => 'name',
    'type' => 'text_medium',
  ) );

  /**
   * Appointment Page
   */
  $appoinments_meta = new_cmb2_box( array(
    'id'            => 'appointments_metabox',
    'title'         => __( 'Calendly', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'appointments'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $appoinments_meta->add_field(
    array(
      'name'             => __( 'Username', 'cmb2' ),
      'desc'             => __( 'Your Calendly username so we can embed the Calendly app.'),
      'id'               => $prefix . 'calendly',
      'type'             => 'text_medium',
    )
  );

    /**
   * Portals Page
   */
  $portals_meta = new_cmb2_box( array(
    'id'            => 'portals_metabox',
    'title'         => __( 'Client Portals', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'show_on'       => array( 'key' => 'slug', 'value' => 'portals'),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    )
  );
  $portal_group = $portals_meta->add_field(
    array(
      'name'  => __( 'Client Portals', 'cmb2' ),
      'desc'  => __( 'All client portals that will appear on client portals page.', 'cmb2' ),
      'id'    => $prefix . 'portals',
      'type'  => 'group',
      'options'     => array(
        'group_title'   => __( 'Portal {#}', 'cmb2' ),
        'add_button'    => __( 'Add Another Portal', 'cmb2' ),
        'remove_button' => __( 'Remove This Portal', 'cmb2' ),
        'sortable'      => true, // beta
      ),
    )
  );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Title',
    'desc' => 'I.e, "Client Dashboard," "Investment Portfolio"',
    'id'   => 'title',
    'type' => 'text_medium',
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Image',
    'desc'  => __( 'Image to accompany portal.', 'cmb2' ),
    'id'   => 'thumbnail',
    'type' => 'file',
    'options'          => array(
      'url'            => false, // Hide the text input for the url
    ),
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Brief Description',
    'desc'  => __( 'Brief description (1 sentence or less) or each site.', 'cmb2' ),
    'id'   => 'description',
    'type' => 'textarea_small',
  ) );
  $portals_meta->add_group_field( $portal_group, array(
    'name' => 'Login URL',
    'desc'  => __( 'URL where clients can login.', 'cmb2' ),
    'id'   => 'url',
    'type' => 'text_medium',
  ) );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\register_page_metaboxes' );

/** 
 * ADAPTED FROM:
 * Metabox for Page Slug
 * @author Tom Morton
 * @link https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-show_on-filters
 *
 * @param bool $display
 * @param array $meta_box
 * @return bool display metabox
 */
function metabox_show_on_slug( $display, $meta_box ) {
    if ( ! isset( $meta_box['show_on']['key'], $meta_box['show_on']['value'] ) ) {
        return $display;
    }

    if ( 'slug' !== $meta_box['show_on']['key'] ) {
        return $display;
    }

    $post_id = 0;

    // If we're showing it based on ID, get the current ID
    if ( isset( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
    } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = $_POST['post_ID'];
    }

    if ( ! $post_id ) {
      return false; // In Morton's code, this WAS 'return $dispay;'-- but this caused all the metaboxes to appear for pages as they were being created, i.e. before they had proper slugs.
    }

    $slug = get_post( $post_id )->post_name;

    // See if there's a match
    return in_array( $slug, (array) $meta_box['show_on']['value']);
}
add_filter( 'cmb2_show_on', __NAMESPACE__ . '\metabox_show_on_slug', 10, 2 );