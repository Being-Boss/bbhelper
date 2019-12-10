<?php

/*
* Initializing the Webinar custom post type
*/
 
function webinar_post_type() {
 
// Set UI labels for Webinar post type
    $labels = array(
        'name'                => _x( 'Webinars', 'Post Type General Name' ),
        'singular_name'       => _x( 'Webinar', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Webinars' ),
        'parent_item_colon'   => __( 'Parent Webinar' ),
        'all_items'           => __( 'Webinars' ),
        'view_item'           => __( 'View Webinar' ),
        'add_new_item'        => __( 'Add New Webinar' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Webinar' ),
        'update_item'         => __( 'Update Webinar' ),
        'search_items'        => __( 'Search Webinars' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Webinar post type
     
    $args = array(
        'label'               => __( 'webinar' ),
        'description'         => __( 'Being Boss Webinars' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => 'bbsettings',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'webinar', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'webinar_post_type', 0 );



/**
 * Creates Resources Taxonomy for Webinar Replay
 */
function custom_resources_webinar_init(){

  //set some options for our new custom taxonomy
  $args = array(
    'label' => __( 'Related Resources' ),
    'hierarchical' => true,
    'capabilities' => array(
      // allow anyone editing posts to assign terms
      'assign_terms' => 'edit_posts',
      /* 
      * but you probably don't want anyone except 
      * admins messing with what gets auto-generated! 
      */
      'edit_terms' => 'administrator'
    )
  );

  /* 
  * create the custom taxonomy and attach it to
  * custom post type A 
  */
  register_taxonomy( 'related-resources-webinar', 'webinar', $args);
}

add_action( 'init', 'custom_resources_webinar_init' );




/**
 * Populates Resources Taxonomy with Webinar Custom Post Type
 */
function update_resources_webinar_terms($post_id) {

  // only update terms if it's a post-type-B post
  if ( 'resources' != get_post_type($post_id)) {
    return;
  }

  // don't create or update terms for system generated posts
  if (get_post_status($post_id) == 'auto-draft') {
    return;
  }
    
  /*
  * Grab the post title and slug to use as the new 
  * or updated term name and slug
  */
  $term_title = get_the_title($post_id);
  $term_slug = get_post( $post_id )->post_name;

  /*
  * Check if a corresponding term already exists by comparing 
  * the post ID to all existing term descriptions. 
  */
  $existing_terms = get_terms('related-resources-webinar', array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, 'related-resources-webinar', array(
        'name' => $term_title,
        'slug' => $term_slug
        )
      );
      return;
    }
  }

  /* 
  * If we didn't find a match above, this is a new post, 
  * so create a new term.
  */
  wp_insert_term($term_title, 'related-resources-webinar', array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_resources_webinar_terms');







add_action( 'cmb2_admin_init', 'cmb2_bbwebinar_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbwebinar_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbwebinar_';

	/**
	 * Initiate the metabox
	 */
	$bbwebinar = new_cmb2_box( array(
		'id'            => 'bbwebinar_metabox',
		'title'         => __( 'Being Boss - Webinar Details', 'cmb2' ),
		'object_types'  => array( 'webinar', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbwebinar->add_field( array(
    		'name' => 'Replay Mode',
    		'desc' => 'Check box to enable replay mode',
    		'id'   => $prefix . 'replay_mode',
    		'type' => 'checkbox',
	) );

  $bbwebinar->add_field( array(
        'name'       => __( 'Mode Select', 'cmb2' ),
        'desc'       => __( 'Select a mode for the page layout', 'cmb2' ),
        'id'         => $prefix . 'mode_select',
        'type'       => 'select',
        'show_option_none' => true,
        'default'          => 'register',
        'options'          => array(
          'register' => __( 'Everwebinar Signup', 'cmb2' ),
          'replay'   => __( 'On-Page Replay', 'cmb2' ),
          'signupreplay'     => __( 'Form Submit to Replay', 'cmb2' ),
        ),
  ) );

  $bbwebinar->add_field( array(
        'name'    => 'Replay Video',
        'desc'    => 'Embed code for replay video',
        'default' => '',
        'id'      => $prefix . 'replay_video',
        'type'    => 'textarea_code',
  ) );

  $bbwebinar->add_field( array(
        'name'       => __( 'Replay Optin', 'cmb2' ),
        'desc'       => __( 'Select a fullwidth optin', 'cmb2' ),
        'id'         => $prefix . 'optin_select',
        'type'       => 'select',
        'show_option_none' => true,
        'options_cb' => 'cmb2_get_optins_list',
  ) );

	$bbwebinar->add_field( array(
    		'name'    => 'Header Image',
    		'desc'    => 'Upload an image or enter an URL.',
    		'id'      => $prefix . 'header_image',
    		'type'    => 'file',
    		// Optional:
    		'options' => array(
        		'url' => false, // Hide the text input for the url
    		),
    		'text'    => array(
        		'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
    		),
	) );


	$bbwebinar->add_field( array(
    		'name' => 'Header Text Overlay (Small)',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );

	$bbwebinar->add_field( array(
    		'name'    => 'Webinar Main Title',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'main_title',
    		'type'    => 'text',
	) );


	$bbwebinar->add_field( array(
    		'name'    => 'Webinar Month',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'month',
    		'type'    => 'text',
	) );

	$bbwebinar->add_field( array(
    		'name'    => 'Webinar Day',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'day',
    		'type'    => 'text',
	) );

	$bbwebinar->add_field( array(
    		'name'    => 'Guest 1 - Name',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'guest_one_name',
    		'type'    => 'textarea_small',
	) );

	$bbwebinar->add_field( array(
    		'name' => 'Guest 1 - Image',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'guest_one_image',
    		'type' => 'file',
		// Optional:
    		'options' => array(
        		'url' => false, // Hide the text input for the url
    		),
    		'text'    => array(
        		'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
    		),
    		// query_args are passed to wp.media's library query.
    		'query_args' => array(
        		'type' => 'application/pdf', // Make library only display PDFs.
    		),

	) );

	$bbwebinar->add_field( array(
    		'name'    => 'Guest 2 - Name',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'guest_two_name',
    		'type'    => 'textarea_small',
	) );

	$bbwebinar->add_field( array(
    		'name' => 'Guest 2 - Image',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'guest_two_image',
    		'type' => 'file',
		// Optional:
    		'options' => array(
        		'url' => false, // Hide the text input for the url
    		),
    		'text'    => array(
        		'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
    		),
    		// query_args are passed to wp.media's library query.
    		'query_args' => array(
        		'type' => 'application/pdf', // Make library only display PDFs.
    		),

	) );

	$bbwebinar->add_field( array(
    		'name'    => 'External Link',
    		'desc'    => 'External link (such as for the homepage)',
    		'default' => '',
    		'id'      => $prefix . 'external_link',
    		'type'    => 'text',
	) );

  $bbwebinar->add_field( array(
        'name' => 'Subscribe Box Title',
        'desc' => 'Box Label',
        'default' => '',
        'id' => $prefix . 'subscribe_box_title',
        'type' => 'textarea_small'
  ) );

  $bbwebinar->add_field( array(
        'name' => 'Everwebinar - Registration Button Code',
        'desc' => '',
        'default' => '',
        'id' => $prefix . 'everwebinar_full',
        'type' => 'textarea_code'
  ) );

  $bbwebinar->add_field( array(
        'name' => 'Custom Button Label',
        'desc' => 'Button Label if Everwebinar/WebinarJam is not used',
        'default' => '',
        'id' => $prefix . 'custom_button_label',
        'type' => 'text'
  ) );

  $bbwebinar->add_field( array(
        'name'    => 'Custom ButtonLink',
        'desc'    => 'Custom Button Link if Everwebinar/WebinarJam is not used',
        'default' => '',
        'id'      => $prefix . 'custom_button_link',
        'type'    => 'text',
  ) );

  $bbwebinar->add_field( array(
        'name' => 'Custom Tracker',
        'desc' => 'Facebook Pixel, etc.',
        'default' => '',
        'id' => $prefix . 'custom_tracker',
        'type' => 'textarea_code'
  ) );

  $bbwebinar->add_field( array(
        'name' => 'Signup Form',
        'desc' => 'Code for Drip signup form',
        'default' => '',
        'id' => $prefix . 'signup_form',
        'type' => 'textarea_code'
  ) );


}



// Add new Webinar category taxonomy
add_action( 'init', 'create_webinar_hierarchical_taxonomy', 0 );
 
function create_webinar_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Webinar Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Webinar Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Webinar Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Webinar Category' ), 
    'update_item' => __( 'Update Webinar Category' ),
    'add_new_item' => __( 'Add New Webinar Category' ),
    'new_item_name' => __( 'New Webinar Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('webinarcategories',array('webinar'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'webinarcategories' ),
  ));
 
}









/*
* Initializing the Webinar Replay custom post type
*/
 
function replay_post_type() {
 
// Set UI labels for Webinar post type
    $labels = array(
        'name'                => _x( 'Replays', 'Post Type General Name' ),
        'singular_name'       => _x( 'Replay', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Replays' ),
        'parent_item_colon'   => __( 'Parent Replay' ),
        'all_items'           => __( 'Replays' ),
        'view_item'           => __( 'View Replay' ),
        'add_new_item'        => __( 'Add New Replay' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Replay' ),
        'update_item'         => __( 'Update Replay' ),
        'search_items'        => __( 'Search Replays' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Webinar post type
     
    $args = array(
        'label'               => __( 'replay' ),
        'description'         => __( 'Being Boss Webinar Replays' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'author', 'thumbnail', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => 'bbsettings',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'rewrite'            => array( 'with_front' => false, 'slug' => 'watch' ),
    );
     
    // Registering your Custom Post Type
    register_post_type( 'replay', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'replay_post_type', 0 );


add_action( 'cmb2_admin_init', 'cmb2_bbreplay_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbreplay_metaboxes() {

  // Start with an underscore to hide fields from custom fields list
  $prefix = 'bbreplay_';

  /**
   * Initiate the metabox
   */
  $bbreplay = new_cmb2_box( array(
    'id'            => 'bbreplay_metabox',
    'title'         => __( 'Being Boss - Webinar Details', 'cmb2' ),
    'object_types'  => array( 'replay', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    // 'cmb_styles' => false, // false to disable the CMB stylesheet
    // 'closed'     => true, // Keep the metabox closed by default
  ) );

  $bbreplay->add_field( array(
        'name'       => __( 'Related Webinar', 'cmb2' ),
        'desc'       => __( 'Select a webinar post', 'cmb2' ),
        'id'         => $prefix . 'webinar_select',
        'type'       => 'select',
        'show_option_none' => true,
        'options_cb' => 'cmb2_get_webinar_list',
        'column'  => true,
  ) );

  $bbreplay->add_field( array(
        'name'    => 'Replay Video',
        'desc'    => 'Embed code for replay video',
        'default' => '',
        'id'      => $prefix . 'replay_video',
        'type'    => 'textarea_code',
  ) );

/*  $bbreplay->add_field( array(
        'name'    => 'Header Image',
        'desc'    => 'Upload an image or enter an URL.',
        'id'      => $prefix . 'header_image',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
        ),
  ) );

  $bbreplay->add_field( array(
        'name' => 'Header Text',
        'desc' => 'Text overlay for page header image (HTML-enabled)',
        'default' => '',
        'id' => $prefix . 'header_text',
        'type' => 'textarea_small'
  ) );

  $bbreplay->add_field( array(
        'name' => 'Header Text Label',
        'desc' => 'Text overlay for page header image (HTML-enabled)',
        'default' => '',
        'id' => $prefix . 'header_label',
        'type' => 'text'
  ) );*/


}




/**
 * Gets a number of webinar posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_webinar_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'webinar',
        'numberposts' => 100,
    ) );

    $posts = get_posts( $args );

    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }

    return $post_options;
}

/**
 * Gets 100 posts for your_post_type and displays them as options
 * @return array An array of options that matches the CMB2 options array
 */
function cmb2_get_webinar_list() {
    return cmb2_get_webinar_post_options( array( 'post_type' => 'webinar', 'numberposts' => 100 ) );
}