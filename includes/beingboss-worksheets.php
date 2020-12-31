<?php

/*
* Initializing the Worksheets custom post type
*/

function worksheets_post_type() {

// Set UI labels for Worksheets post type
    $labels = array(
        'name'                => _x( 'Worksheets', 'Post Type General Name' ),
        'singular_name'       => _x( 'Worksheet', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Worksheets' ),
        'parent_item_colon'   => __( 'Parent Worksheet' ),
        'all_items'           => __( 'Worksheets' ),
        'view_item'           => __( 'View Worksheet' ),
        'add_new_item'        => __( 'Add New Worksheet' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Worksheet' ),
        'update_item'         => __( 'Update Worksheet' ),
        'search_items'        => __( 'Search Worksheets' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );

// Set other options for Worksheets post type

    $args = array(
        'label'               => __( 'worksheets' ),
        'description'         => __( 'Being Boss Worksheets' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'author', 'thumbnail', 'revisions', 'custom-fields', ),
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
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    // Registering your Custom Post Type
    register_post_type( 'worksheets', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action( 'init', 'worksheets_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_worksheets_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_worksheets_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbworksheets_';

	/**
	 * Initiate the metabox
	 */
	$bbworksheets = new_cmb2_box( array(
		'id'            => 'bbworksheets_metabox',
		'title'         => __( 'Worksheet Details', 'cmb2' ),
		'object_types'  => array( 'worksheets', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


}
