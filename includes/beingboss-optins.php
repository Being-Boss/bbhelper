<?php

/*
* Initializing the Opt-Ins custom post type
*/

function shownote_optins_post_type() {

// Set UI labels for Optins post type
    $labels = array(
        'name'                => _x( 'Optins', 'Post Type General Name' ),
        'singular_name'       => _x( 'Optin', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Optins' ),
        'parent_item_colon'   => __( 'Parent Optin' ),
        'all_items'           => __( 'Optins' ),
        'view_item'           => __( 'View Optin' ),
        'add_new_item'        => __( 'Add New Optin' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Optin' ),
        'update_item'         => __( 'Update Optin' ),
        'search_items'        => __( 'Search Optins' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );

// Set other options for Optins post type

    $args = array(
        'label'               => __( 'optins' ),
        'description'         => __( 'Being Boss Optins' ),
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
    register_post_type( 'optins', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action( 'init', 'shownote_optins_post_type', 0 );



// Add new Optins Category taxonomy
add_action( 'init', 'create_optincat_hierarchical_taxonomy', 0 );

function create_optincat_hierarchical_taxonomy() {

  $labels = array(
    'name' => _x( 'Display Style', 'taxonomy general name' ),
    'singular_name' => _x( 'Display Style', 'taxonomy singular name' ),
    'search_items' =>  __( 'Display Styles' ),
    'all_items' => __( 'All Display Styles' ),
    'parent_item' => __( 'Parent Style' ),
    'parent_item_colon' => __( 'Parent Style:' ),
    'edit_item' => __( 'Edit Display Style' ),
    'update_item' => __( 'Update Display Style' ),
    'add_new_item' => __( 'Add New Display Style' ),
    'new_item_name' => __( 'New Display Style Name' ),
    'menu_name' => __( 'Display Styles' ),
  );

// Registers the taxonomy

  register_taxonomy('displaystyle',array('optins'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'displaystyle' ),
  ));

}
