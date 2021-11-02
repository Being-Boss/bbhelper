<?php

/*
* Initializing the Brewed custom post type
*/

function brewed_post_type() {

// Set UI labels for Articles post type
    $labels = array(
        'name'                => _x( 'Brewed', 'Post Type General Name' ),
        'singular_name'       => _x( 'Brewed', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Brewed' ),
        'parent_item_colon'   => __( 'Parent Brewed Post' ),
        'all_items'           => __( 'Brewed Posts' ),
        'view_item'           => __( 'View Brewed Posts' ),
        'add_new_item'        => __( 'Add New Brewed Post' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Brewed Post' ),
        'update_item'         => __( 'Update Brewed Post' ),
        'search_items'        => __( 'Search Brewed Posts' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );

// Set other options for Brewed post type

    $args = array(
        'label'               => __( 'brewed' ),
        'description'         => __( 'Brewed Newsletter Issues' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        // 'show_in_menu'        => 'bbsettings',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_in_rest'        => true,
        'capability_type'     => 'post',
    );

    // Registering your Custom Post Type
    register_post_type( 'brewed', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action( 'init', 'brewed_post_type', 0 );




add_action( 'cmb2_admin_init', 'cmb2_bbbrewed_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbbrewed_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbbrewed_';

	/**
	 * Initiate the metabox
	 */
	$bbbrewed = new_cmb2_box( array(
		'id'            => 'bbbrewed_metabox',
		'title'         => __( 'Brewed Details', 'cmb2' ),
		'object_types'  => array( 'brewed', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbbrewed->add_field( array(
    		'name' => 'Subheader (Preview) Text',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'preview',
    		'type' => 'textarea_small'
	) );

  $bbbrewed->add_field( array(
    		'name' => 'Byline',
    		'desc' => 'This email was prepared for you by...',
    		'default' => '',
    		'id' => $prefix . 'byline',
    		'type' => 'textarea_small'
	) );

}





// Add new Brewed category taxonomy
add_action( 'init', 'create_article_hierarchical_taxonomy', 0 );

function create_brewed_hierarchical_taxonomy() {

  $labels = array(
    'name' => _x( 'Brewed Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Brewed Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Brewed Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Brewed Category' ),
    'update_item' => __( 'Update Brewed Category' ),
    'add_new_item' => __( 'Add New Brewed Category' ),
    'new_item_name' => __( 'New Brewed Category Name' ),
    'menu_name' => __( 'Categories' ),
  );

// Registers the taxonomy

  register_taxonomy('brewedcategories',array('brewed'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'brewedcategories' ),
  ));

}











/**
 * Creates Resources Taxonomy
 */
function custom_resources_brewed_init(){

  //set some options for our new custom taxonomy
  $args = array(
    'label' => __( 'Related Resources' ),
    'hierarchical' => true,
    'show_admin_column' => true,
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
  register_taxonomy( 'related-resources-articles', 'brewed', $args);
}

add_action( 'init', 'custom_resources_brewed_init' );




/**
 * Populates Resources Taxonomy with Resources Custom Post Type
 */
function update_resources_brewed_terms($post_id) {

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
  $existing_terms = get_terms('related-resources-brewed', array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, 'related-resources-brewed', array(
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
  wp_insert_term($term_title, 'related-resources-brewed', array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_resources_brewed_terms');
