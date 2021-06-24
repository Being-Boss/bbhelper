<?php

/**
 * Get the bootstrap! If using as a plugin, REMOVE THIS!
 */
require_once WPMU_PLUGIN_DIR . '/cmb2-attached-posts/cmb2-attached-posts-field.php';


/**
 * Being Boss
 */
add_action( 'cmb2_admin_init', 'bbsettings_register_theme_options_metabox' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function bbsettings_register_theme_options_metabox() {
	/**
	 * Registers options page menu item and form.
	 */
	$bb_options = new_cmb2_box( array(
		'id'           => 'bbsettings_option_metabox',
		'title'        => esc_html__( 'Being Boss', 'bbsettings' ),
		'object_types' => array( 'options-page' ),
		'option_key'      => 'bbsettings', // The option key and admin menu page slug.
		'menu_title'      => esc_html__( 'Being Boss', 'bbsettings' ), // Falls back to 'title' (above).
		'position'        => 5, // Menu position. Only applicable if 'parent_slug' is left empty.
	) );
	/*
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */
}





/*add_action( 'admin_menu', 'bbsettings_move_taxonomy_menu' );
function bbsettings_move_taxonomy_menu() {
	add_submenu_page( 'bbsettings', esc_html__( 'Webinars - Cats', 'bb-webinars' ), esc_html__( 'Webinars - Cats', 'bb-webinars' ), 'manage_categories', 'edit-tags.php?taxonomy=webinarcategories' );
}*/





/**
 * Being Boss
 */
add_action( 'cmb2_admin_init', 'bbsite_register_settings_metabox' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function bbsite_register_settings_metabox() {
	/**
	 * Registers options page menu item and form.
	 */
	$bb_settings = new_cmb2_box( array(
		'id'           => 'bbsite_settings_metabox',
		'title'        => esc_html__( 'Being Boss Settings', 'bboptions' ),
		'object_types' => array( 'options-page' ),
		/*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */
		'option_key'      => 'bboptions', // The option key and admin menu page slug.
		// 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
		'menu_title'      => esc_html__( 'Settings', 'bboptions' ), // Falls back to 'title' (above).
		'parent_slug'     => 'bbsettings', // Make options page a submenu item of the themes menu.
		// 'capability'      => 'manage_options', // Cap required to view options-page.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
	) );
	/*
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */

	$bb_settings->add_field( array(
		'name'    => 'All-Time Podcast Plays',
		'desc'    => 'This is used to update the play count that is mentioned in various places around the site.',
		'default' => '',
		'id'      => 'bboptions_playcount',
		'type'    => 'text',
	) );

	$bb_settings->add_field( array(
		'name'    => 'Making a Business Episode Count',
		'desc'    => 'This is used to update the total episode number of MAB episodes, includes episodes in the Clubhouse.',
		'default' => '',
		'id'      => 'bboptions_mabcount',
		'type'    => 'text',
	) );

	$bb_settings->add_field( array(
		'name'    => 'Featured Guest Order',
		'id'      => 'bbguest_featured_order',
		'type'    => 'custom_attached_posts',
		'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
		'options' => array(
			'show_thumbnails' => true, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => array(
				'post_type'      => 'guests',
				'post_status'	 => 'publish',
			), // override the get_posts args
		),
	) );

	$bb_settings->add_field( array(
		'name'    => 'Bestie Guest Order',
		'id'      => 'bbguest_bestie_order',
		'type'    => 'custom_attached_posts',
		'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
		'options' => array(
			'show_thumbnails' => true, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => array(
				'post_type'      => 'guests',
				'post_status'	 => 'publish',
			), // override the get_posts args
		),
	) );

}



/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function bbsettings_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'bboptions', $key, $default );
	}
	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'bboptions', $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}







// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-play-count', 'beingboss_playcount_shortcode' );
function beingboss_playcount_shortcode( $atts ) {
    ob_start();

    $playcount = cmb2_get_option( 'bboptions', 'bboptions_playcount' );
    echo $playcount;

    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}







// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-main-count', 'beingboss_maincount_shortcode' );
function beingboss_maincount_shortcode( $atts ) {
    ob_start();

    $args = array('cat' => 39,);
	$the_query = new WP_Query( $args );
	echo $the_query->found_posts;
	echo ' episodes';

    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}

// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-10minutes-count', 'beingboss_10minutescount_shortcode' );
function beingboss_10minutescount_shortcode( $atts ) {
    ob_start();

    $args = array('cat' => 211,);
	$the_query = new WP_Query( $args );
	echo $the_query->found_posts;
	echo ' episodes';

    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}

// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-mab-count', 'beingboss_mabcount_shortcode' );
function beingboss_mabcount_shortcode( $atts ) {
    ob_start();

    $mabcount = cmb2_get_option( 'bboptions', 'bboptions_mabcount' );
    echo $mabcount;
    echo ' episodes';

    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}

// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-minisodes-count', 'beingboss_minisodescount_shortcode' );
function beingboss_minisodescount_shortcode( $atts ) {
    ob_start();

    $args = array('cat' => 40,);
	$the_query = new WP_Query( $args );
	echo $the_query->found_posts;
	echo ' episodes';

    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}
