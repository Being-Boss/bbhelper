<?php

/*
* Initializing the Jobs custom post type
*/

function jobs_post_type() {

// Set UI labels for Jobs post type
    $labels = array(
        'name'                => _x( 'Jobs', 'Post Type General Name' ),
        'singular_name'       => _x( 'Job', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Jobs' ),
        'parent_item_colon'   => __( 'Parent Job' ),
        'all_items'           => __( 'Jobs' ),
        'view_item'           => __( 'View Job' ),
        'add_new_item'        => __( 'Add New Job' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Job' ),
        'update_item'         => __( 'Update Job' ),
        'search_items'        => __( 'Search Jobs' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );

// Set other options for Optins post type

    $args = array(
        'label'               => __( 'jobs' ),
        'description'         => __( 'Being Boss Jobs' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
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
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'rewrite'            => array( 'slug' => 'jobs' ),
    );

    // Registering your Custom Post Type
    register_post_type( 'jobs', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action( 'init', 'jobs_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_bbjobs_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbjobs_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbjob_';

	/**
	 * Initiate the metabox
	 */
	$bbjob = new_cmb2_box( array(
		'id'            => 'bbjob_metabox',
		'title'         => __( 'Position Details', 'cmb2' ),
		'object_types'  => array( 'jobs', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbjob->add_field( array(
    		'name' => 'Subtext',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'subtext',
    		'type' => 'text'
	) );

}
