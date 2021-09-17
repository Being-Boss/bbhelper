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
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'rewrite'            => array( 'slug' => 'worksheet' ),
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



add_action( 'cmb2_admin_init', 'cmb2_bboptin_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bboptin_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bboptin_';

	/**
	 * Initiate the metabox
	 */
	$bboptin = new_cmb2_box( array(
		'id'            => 'bboptin_metabox',
		'title'         => __( 'Optin Details', 'cmb2' ),
		'object_types'  => array( 'optins', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

  $bboptin->add_field( array(
  		'name' => 'Universal Clean Slug',
  		'desc' => '',
  		'id' => $prefix . 'slug',
      'type'             => 'select',
      'show_option_none' => true,
      'default'          => 'none',
      'options'          => array(
          '4-goal-setting' => __( '4 Goal-Setting Business Questions', 'cmb2' ),
          'bullet-journal'   => __( 'Bullet Journal', 'cmb2' ),
          'chalkboard-method'     => __( 'Chalkboard Method', 'cmb2' ),
          'content-brainstorm'     => __( 'Content Brainstorm', 'cmb2' ),
          'getting-shit-done'     => __( 'Getting Shit Done', 'cmb2' ),
          'hot-shit-200'     => __( 'Hot Shit 200', 'cmb2' ),
          'make-100-today'     => __( 'Make $100 Today', 'cmb2' ),
          'map-out-your-thoughts'     => __( 'Map Out Your Thoughts', 'cmb2' ),
          'push-goals'     => __( 'Push Goals', 'cmb2' ),
          'tarot-spread-for-bosses'     => __( 'Tarot Spread for Bosses', 'cmb2' ),
          'tarot-spread-for-taking-action'     => __( 'Tarot Spread for Taking Action', 'cmb2' ),
          'track-your-metrics'     => __( 'Track Your Metrics', 'cmb2' ),
          'wellness-challenge'     => __( 'Wellness Challenge', 'cmb2' ),
          'whats-working'     => __( 'What is Working Kinda Not', 'cmb2' ),
          'work-life-balance'     => __( 'Work Life Balance', 'cmb2' ),
          'ideal-day'     => __( 'Your Ideal Day', 'cmb2' ),
      ),
	) );

	$bboptin->add_field( array(
    		'name' => 'Convertkit Form',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'convertkit',
    		'type' => 'textarea_code'
	) );

  $bboptin->add_field( array(
    		'name' => 'Ontraport Form',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'ontraport',
    		'type' => 'textarea_code'
	) );
}


// create shortcode
add_shortcode( 'bboptin', 'beingboss_optin_block' );
function beingboss_optin_block( $atts ) {
  // define attributes and their defaults
  extract( shortcode_atts( array (
    'id' => null,
  ), $atts ) );

  // define query parameters based on attributes
  $options = array(
    'post_type' => 'optins',
    'posts_per_page' => 1,
    'p' => $id
  );
  $query = new WP_Query( $options );
  ob_start();
  // run the loop based on the query
  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) : $query->the_post();
				$postid = get_the_ID(); ?>

    <div class="optin-block alignwide mt-3 mb-5 optin-id-<?php echo $postid; ?>">
      <div class="row">
        <div class="col col-lg-9 col-xl-7 offset-lg-3 offset-xl-5 pb-5 pb-md-0">
          <p class="center fs46 lust italic">featured download</p>
        </div>
      </div>
      <div class="row row-eq-height align-items-end bg-accent mt-3 mt-md-0">
        <div class="col-md-5 text-center">
          <img src="/wp-content/themes/beingboss/img/Optin_Worksheets.png" style="margin-top: -75px;">
        </div>
        <div class="col-md-7 pt-4 pb-2 px-5 mb-0">
          <p class="italic">In this episode, the <span class="upper heavy brandon"><?php echo get_the_title($postid); ?></span> worksheet was mentioned. Download your copy here!</p>
          <p class="text-center"><?php echo get_post_meta( $postid, 'bboptin_ontraport', true ); ?></p>
        </div>
      </div>
    </div>

    <?php endwhile;
			wp_reset_postdata();
		}

		$optin = ob_get_clean();
		return $optin;
}
