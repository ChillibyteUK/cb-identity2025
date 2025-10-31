<?php
/**
 * Custom taxonomies for the Hub simco theme.
 *
 * This file defines and registers custom taxonomies such as 'Teams' and 'Offices'.
 *
 * @package cb-identity2025
 */

/**
 * Register custom taxonomies for the theme.
 *
 * @return void
 */
function cb_register_taxes() {

    $args = array(
        'labels'             => array(
            'name'          => 'Services',
            'singular_name' => 'Service',
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => true,
        'show_tagcloud'      => false,
        'show_in_quick_edit' => true,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
        'rewrite'            => false,
    );
    register_taxonomy( 'service', array( 'case_study', 'post' ), $args );

	$args = array(
        'labels'             => array(
            'name'          => 'Themes',
            'singular_name' => 'Theme',
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => true,
        'show_tagcloud'      => false,
        'show_in_quick_edit' => true,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
        'rewrite'            => false,
    );
    register_taxonomy( 'theme', array( 'case_study', 'post' ), $args );

    $args = array(
        'labels'             => array(
            'name'          => 'Regions',
            'singular_name' => 'Region',
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => true,
        'show_tagcloud'      => false,
        'show_in_quick_edit' => true,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
        'rewrite'            => false,
    );
    register_taxonomy( 'theme', array( 'case_study', 'post' ), $args );
}
add_action( 'init', 'cb_register_taxes' );
