<?php


add_action( 'cmb2_admin_init', 'cmb2_bbinstagram_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbinstagram_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbinstagram_';

	/**
	 * Initiate the metabox
	 */
	$bbinstagram = new_cmb2_box( array(
		'id'            => 'bbinstagram_metabox',
		'title'         => __( 'Being Boss - Instagram Page', 'cmb2' ),
		'object_types'  => array( 'page', ), // Post type
        'show_on'      => array( 'key' => 'id', 'value' => '3931' ),
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

    /*$bbinstagram->add_field( array(
        'name' => 'TOP SECTION',
        'desc' => '',
        'type' => 'title',
        'id'   => $prefix . 'toptitle',
    ) );
*/
    $bbinstagramtop = $bbinstagram->add_field( array(
            'id'          => $prefix . 'top',
            'type'        => 'group',
            'description' => __( 'The Instagram button section', 'cmb2' ),
            'repeatable'  => true, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'   => __( 'Button {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'    => __( 'Add Another Button', 'cmb2' ),
                'remove_button' => __( 'Remove Button', 'cmb2' ),
                'sortable'      => true, // beta
                // 'closed'     => true, // true to have the groups closed by default
            ),
    ) );

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    /*$bbinstagram->add_group_field( $bbinstagramtop, array(
            'name' => 'Image',
            'id'   => $prefix . 'image_top',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
            ),
    ) );*/

    $bbinstagram->add_group_field( $bbinstagramtop, array(
            'name' => 'Link',
            'id'   => $prefix . 'link_top',
            'type' => 'text',
    ) );

    $bbinstagram->add_group_field( $bbinstagramtop, array(
            'name' => 'Label',
            'id'   => $prefix . 'label_top',
            'type' => 'text',
    ) );

    $bbinstagram->add_group_field( $bbinstagramtop, array(
			'name' 				=> 'Button Color',
			'desc' 				=> 'Select an option',
			'id' 				=> $prefix . 'button_color_top',
			'type' 				=> 'select',
			'show_option_none' 	=> false,
			'default' 			=> 'button-yellow',
			'options' 			=> array(
				'button-yellow' 	=> 'Black Text/Yellow BG',
				'button-pink'		=> 'White Text/Pink BG',
				'button'			=> 'White Text/Black BG',
			),
	) );

}

   /* $bbinstagram->add_field( array(
        'name' => 'BOTTOM SECTION',
        'desc' => '',
        'type' => 'title',
        'id'   => $prefix . 'bottomtitle',
    ) );

    $bbinstagrambottom = $bbinstagram->add_field( array(
            'id'          => $prefix . 'bottom',
            'type'        => 'group',
            'description' => __( 'The Instagram button bottom section', 'cmb2' ),
            'repeatable'  => true, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'   => __( 'Button {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'    => __( 'Add Another Button', 'cmb2' ),
                'remove_button' => __( 'Remove Button', 'cmb2' ),
                'sortable'      => true, // beta
                // 'closed'     => true, // true to have the groups closed by default
            ),
    ) );

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $bbinstagram->add_group_field( $bbinstagrambottom, array(
            'name' => 'Image',
            'id'   => $prefix . 'image_bottom',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
            ),
    ) );

    $bbinstagram->add_group_field( $bbinstagrambottom, array(
            'name' => 'Link',
            'id'   => $prefix . 'link_bottom',
            'type' => 'text',
    ) );

    $bbinstagram->add_group_field( $bbinstagrambottom, array(
            'name' => 'Link',
            'id'   => $prefix . 'label_bottom',
            'type' => 'text',
    ) );*/






// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-instagram-posts-top', 'beingboss_instagram_shortcode_top' );
function beingboss_instagram_shortcode_top( $atts ) {
    ob_start();

    $instagramitems = get_post_meta( '3931', 'bbinstagram_top', true );
                                        
    if ( !empty( $instagramitems ) ) {
        foreach ( (array) $instagramitems as $key => $entry ) {

            $itemimage = $itemlink = '';

            if ( isset( $entry['bbinstagram_image_top'] ) ) {
                $itemimage = esc_html( $entry['bbinstagram_image_top'] );
            }

            if ( isset( $entry['bbinstagram_link_top'] ) ) {
                $itemlink = esc_html( $entry['bbinstagram_link_top'] );
            } ?>
        
            <a href="<?php echo $itemlink; ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 auto 25px; display: table;"><img src="<?php echo $itemimage; ?>"></a>
        <?php } 
    }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}




// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-instagram-posts-bottom', 'beingboss_instagram_shortcode_bottom' );
function beingboss_instagram_shortcode_bottom( $atts ) {
    ob_start();

    $instagramitems = get_post_meta( '3931', 'bbinstagram_bottom', true );
                                        
    if ( !empty( $instagramitems ) ) {
        foreach ( (array) $instagramitems as $key => $entry ) {

            $itemimage = $itemlink = '';

            if ( isset( $entry['bbinstagram_image_bottom'] ) ) {
                $itemimage = esc_html( $entry['bbinstagram_image_bottom'] );
            }

            if ( isset( $entry['bbinstagram_link_bottom'] ) ) {
                $itemlink = esc_html( $entry['bbinstagram_link_bottom'] );
            } ?>
        
            <a href="<?php echo $itemlink; ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 auto 25px; display: table;"><img src="<?php echo $itemimage; ?>"></a>
        <?php } 
    }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}





// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-instagram-episodes', 'beingboss_instagram_episodes' );
function beingboss_instagram_episodes( $atts ) {
    ob_start();

    $query_args = array(
        'post_type' => array( 'post' ),
        'posts_per_page' => 3,
        'orderby'    => 'date',
        'order'      => 'DESC'
    );
        
    $instagram_query = new WP_Query( $query_args );
    if ( $instagram_query->have_posts() ) { ?>

        <div class="row">

        <?php while ( $instagram_query->have_posts() ) {
            $instagram_query->the_post();
            $postid = $instagram_query->ID; ?>

            <div class="col-lg-4 col-md-12">
                <div class="instagramitem" style="background-image: url('<?php echo get_the_post_thumbnail_url( $postid, 'archive-thumb' ); ?>');">
                    <a href="<?php echo get_the_permalink($postid); ?>">
                        <span class="itemcontainer">
                            <h3><?php echo get_the_title($postid); ?></h3>
                        </span>
                    </a>
                </div>
            </div>

    <?php } ?>
        </div>
    <?php }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}




// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'bb-instagram-posts', 'beingboss_instagram_shortcode_all' );
function beingboss_instagram_shortcode_all( $atts ) {
    ob_start();

    $instagramitems = get_post_meta( '3931', 'bbinstagram_top', true );
                                        
    if ( !empty( $instagramitems ) ) {
        foreach ( (array) $instagramitems as $key => $entry ) {

            $itemlink = $itemlabel = $buttoncolor = '';

            if ( isset( $entry['bbinstagram_link_top'] ) ) {
                $itemlink = esc_html( $entry['bbinstagram_link_top'] );
            }

            if ( isset( $entry['bbinstagram_label_top'] ) ) {
                $itemlabel = esc_html( $entry['bbinstagram_label_top'] );
            }

            if ( isset ( $entry['bbinstagram_button_color_top'] ) ) {
            	$buttoncolor = esc_html( $entry['bbinstagram_button_color_top'] );
            } ?>
        
            <a href="<?php echo $itemlink; ?>" target="_blank" rel="noopener noreferrer" class="<?php echo $buttoncolor; ?> instagram-button"><?php echo $itemlabel; ?></a>
        <?php } 
    }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}