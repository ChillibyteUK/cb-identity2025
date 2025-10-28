<?php
/**
 * Custom Post Types Registration
 *
 * This file contains the code to register custom post types for the theme.
 *
 * @package cb-identity2025
 */

/**
 * Register custom post types for the theme.
 *
 * @return void
 */
function cb_register_post_types() {

	register_post_type(
		'case_study',
		array(
			'labels'          => array(
				'name'               => 'Case Studies',
				'singular_name'      => 'Case Study',
				'add_new_item'       => 'Add New Case Study',
				'edit_item'          => 'Edit Case Study',
				'new_item'           => 'New Case Study',
				'view_item'          => 'View Case Study',
				'search_items'       => 'Search Case Studies',
				'not_found'          => 'No case studies found',
				'not_found_in_trash' => 'No case studies in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-portfolio',
			'supports'        => array( 'title', 'editor', 'thumbnail' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'work',
				'with_front' => false,
			),
		)
	);
}

add_action( 'init', 'cb_register_post_types' );

// Enable sticky posts for case_study post type in admin and queries.
add_action( 'init', function() {
	add_post_type_support( 'case_study', 'sticky-posts' );
} );

// Add sticky checkbox meta box for case_study post type
add_action( 'add_meta_boxes', function() {
	add_meta_box(
		'cb_case_study_sticky',
		'Stick to the top',
		function( $post ) {
			$is_sticky = get_post_meta( $post->ID, '_cb_case_study_sticky', true );
			wp_nonce_field( 'cb_case_study_sticky_nonce_action', 'cb_case_study_sticky_nonce' );
			echo '<input type="checkbox" id="cb_case_study_sticky" name="cb_case_study_sticky" value="1"' . checked( $is_sticky, '1', false ) . ' />';
			echo '<label for="cb_case_study_sticky"> Stick this case study to the top</label>';
		},
		'case_study',
		'side',
		'high'
	);
} );

// Save sticky status for case_study post type
add_action( 'save_post', function( $post_id ) {
	if ( get_post_type( $post_id ) !== 'case_study' ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) return;
	if ( wp_is_post_revision( $post_id ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( ! isset( $_POST['cb_case_study_sticky_nonce'] ) || ! wp_verify_nonce( $_POST['cb_case_study_sticky_nonce'], 'cb_case_study_sticky_nonce_action' ) ) return;
	if ( isset( $_POST['cb_case_study_sticky'] ) ) {
		update_post_meta( $post_id, '_cb_case_study_sticky', '1' );
	} else {
		delete_post_meta( $post_id, '_cb_case_study_sticky' );
	}
} );