<?php

/*
* Initializing the Guests custom post type
*/

function guests_post_type() {

// Set UI labels for Guests post type
    $labels = array(
        'name'                => _x( 'Guests', 'Post Type General Name' ),
        'singular_name'       => _x( 'Guest', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Guests' ),
        'parent_item_colon'   => __( 'Parent Guest' ),
        'all_items'           => __( 'Guests' ),
        'view_item'           => __( 'View Guest' ),
        'add_new_item'        => __( 'Add New Guest' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Guest' ),
        'update_item'         => __( 'Update Guest' ),
        'search_items'        => __( 'Search Guests' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );

// Set other options for Guests post type

    $args = array(
        'label'               => __( 'guests' ),
        'description'         => __( 'Being Boss Guests' ),
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
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    // Registering your Custom Post Type
    register_post_type( 'guests', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/
add_action( 'init', 'guests_post_type', 0 );



function guests_posts_per_page( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
       return;
    }

    if ( is_post_type_archive( 'guests' ) ) {
       $query->set( 'posts_per_page', -1 );
    }
}
add_filter( 'pre_get_posts', 'guests_posts_per_page' );
