<?php
/**
 * Enhanced Block Registration System
 *
 * This file provides an auto-discovery system for blocks with support for:
 * - Self-contained block directories with block.json
 * - Block-specific CSS compilation and conditional loading
 * - Legacy block support
 * - ACF field group auto-loading
 *
 * @package cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Auto-register blocks from the blocks directory.
 */
function cb_register_blocks() {
    // Check if ACF Pro is active.
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    $blocks = cb_get_blocks();

    // Debug: Log found blocks.
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        // error_log( 'CB Blocks found: ' . print_r( $blocks, true ) );
    }

    foreach ( $blocks as $block ) {
        $block_path = get_stylesheet_directory() . '/blocks/' . $block;
        $block_json = $block_path . '/block.json';

        // Check if block.json exists.
        if ( file_exists( $block_json ) ) {
            // Register using block.json (modern approach).
            $result = register_block_type( $block_path );

            // Debug: Log registration result.
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                if ( is_wp_error( $result ) ) {
                    error_log( 'Block registration failed for ' . $block . ': ' . $result->get_error_message() );
                } else {
                    // error_log( 'Successfully registered block: ' . $block . ' with name: ' . $result->name );
                }
            }

            // Enqueue block-specific CSS.
            cb_enqueue_block_css( $block );
        } else {
            // Fallback to manual registration for legacy blocks.
            cb_register_legacy_block( $block );
        }
    }
}
add_action( 'acf/init', 'cb_register_blocks' );

/**
 * Register custom block categories.
 *
 * @param array $categories Existing block categories.
 * @return array Modified categories array.
 */
function cb_register_block_categories( $categories ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'hub-blocks',
                'title' => __( 'Hub Blocks', 'cb-identity2025' ),
                'icon'  => 'admin-generic',
            ),
        )
    );
}
add_filter( 'block_categories_all', 'cb_register_block_categories' );

/**
 * Get list of block directories.
 *
 * @return array Array of block directory names.
 */
function cb_get_blocks() {
    $blocks_dir = get_stylesheet_directory() . '/blocks';

    if ( ! is_dir( $blocks_dir ) ) {
        return array();
    }

    $blocks = array_filter(
        scandir( $blocks_dir ),
        function ( $item ) use ( $blocks_dir ) {
            return is_dir( $blocks_dir . '/' . $item ) && ! in_array( $item, array( '.', '..' ), true );
        }
    );

    return array_values( $blocks );
}

/**
 * Conditionally enqueue block-specific CSS.
 *
 * @param string $block_name The block directory name.
 */
function cb_enqueue_block_css( $block_name ) {
    $css_file = get_stylesheet_directory() . '/blocks/' . $block_name . '/' . $block_name . '.css';
    $css_url  = get_stylesheet_directory_uri() . '/blocks/' . $block_name . '/' . $block_name . '.css';

    if ( file_exists( $css_file ) ) {
        // Conditionally enqueue on block usage.
        add_action(
            'wp_enqueue_scripts',
            function () use ( $block_name, $css_url, $css_file ) {
                wp_enqueue_style( 'block-' . $block_name, $css_url, array(), filemtime( $css_file ) );
            }
        );
    }
}

/**
 * Register legacy blocks manually.
 *
 * @param string $block_name The block directory name.
 */
function cb_register_legacy_block( $block_name ) {
    $block_path = get_stylesheet_directory() . '/blocks/' . $block_name;

    // Check if we have the old structure (single PHP file in blocks/).
    $legacy_file = get_stylesheet_directory() . '/blocks/' . $block_name . '.php';
    $new_file    = $block_path . '/' . $block_name . '.php';

    if ( file_exists( $new_file ) ) {
        $template = 'blocks/' . $block_name . '/' . $block_name . '.php';
    } elseif ( file_exists( $legacy_file ) ) {
        $template = 'blocks/' . $block_name . '.php';
    } else {
        return; // No template found.
    }

    // Convert block name to title.
    $title = ucwords( str_replace( array( '-', '_' ), ' ', $block_name ) );

    if ( function_exists( 'acf_register_block_type' ) ) {
        acf_register_block_type(
            array(
                'name'            => $block_name,
                'title'           => $title,
                // translators: %s is the block title.
                'description'     => sprintf( __( 'A custom %s block.', 'cb-identity2025' ), $title ),
                'render_template' => $template,
                'category'        => 'hub-blocks',
                'icon'            => 'admin-generic',
                'keywords'        => array( $block_name, 'hub' ),
                'supports'        => array(
                    'align'  => array( 'wide', 'full' ),
                    'anchor' => true,
                ),
            )
        );
    }
}

/**
 * Add block ACF JSON paths.
 *
 * @param array $paths Array of paths to load ACF JSON from.
 * @return array Modified paths array.
 */
function cb_load_block_acf_fields( $paths ) {
    // Add the theme's acf-json directory.
    $paths[] = get_stylesheet_directory() . '/acf-json';

    // Add individual block directories that contain ACF JSON files.
    $blocks = cb_get_blocks();
    foreach ( $blocks as $block ) {
        $block_dir = get_stylesheet_directory() . '/blocks/' . $block;
        // Check if this block directory contains any ACF JSON files.
        $json_files = glob( $block_dir . '/group_*.json' );
        if ( ! empty( $json_files ) ) {
            $paths[] = $block_dir;
        }
    }

    return $paths;
}
add_filter( 'acf/settings/load_json', 'cb_load_block_acf_fields' );

/**
 * Save ACF block field group JSON to the block's directory if it matches a block's field group key.
 *
 * @param string $path Default save path.
 * @param array  $field_group The field group being saved.
 * @return string Modified save path.
 */
function cb_save_block_acf_json_path( $path, $field_group = null ) {
    if ( is_array( $field_group ) && isset( $field_group['key'] ) ) {
        $blocks = cb_get_blocks();
        foreach ( $blocks as $block ) {
            $block_dir = get_stylesheet_directory() . '/blocks/' . $block;
            $json_file = $block_dir . '/group_' . $field_group['key'] . '.json';
            // If this block directory contains the field group, save there.
            if ( file_exists( $json_file ) || ( isset( $field_group['location'][0][0]['param'] ) && 'block' === $field_group['location'][0][0]['param'] && strpos( $field_group['location'][0][0]['value'], $block ) !== false ) ) {
                return $block_dir;
            }
        }
    }
    // Default to theme acf-json.
    return $path;
}
add_filter( 'acf/settings/save_json', 'cb_save_block_acf_json_path', 10, 2 );

/**
 * Force ACF field group sync on admin init (temporary debugging).
 */
function cb_force_acf_sync() {
    if ( is_admin() && function_exists( 'acf_get_field_groups' ) ) {
        // Clear all ACF caches aggressively.
        wp_cache_flush();

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'ACF caches cleared and reset' );
        }
    }
}
// Temporarily disabled to avoid fatal error.
/* add_action( 'admin_init', 'cb_force_acf_sync' ); */


/**
 * After ACF saves a field group, copy its JSON from /acf-json to the correct block folder if it matches a block.
 */
add_action(
	'acf/save_post',
	function ( $post_id ) {
    	// Only run for field groups.
    	if ( strpos( $post_id, 'group_' ) !== 0 ) {
        	return;
    	}
    	$acf_json_dir = get_stylesheet_directory() . '/acf-json';
    	$json_file    = $acf_json_dir . '/' . $post_id . '.json';
    	if ( ! file_exists( $json_file ) ) {
        	return;
    	}
    	$blocks = cb_get_blocks();
    	$json   = json_decode( file_get_contents( $json_file ), true );
    	if ( ! is_array( $json ) || ! isset( $json['location'][0][0]['param'] ) ) {
        	return;
    	}
    	if ( 'block' !== $json['location'][0][0]['param'] ) {
        	return;
    	}
		$location_value = $json['location'][0][0]['value'];
		foreach ( $blocks as $block ) {
			$block_json_path = get_stylesheet_directory() . '/blocks/' . $block . '/block.json';
			if ( file_exists( $block_json_path ) ) {
				$block_json = json_decode( file_get_contents( $block_json_path ), true );
				if ( isset( $block_json['name'] ) && $block_json['name'] === $location_value ) {
					$block_dir   = get_stylesheet_directory() . '/blocks/' . $block;
					$target_file = $block_dir . '/' . $post_id . '.json';
					copy( $json_file, $target_file );
					// Force reload of block editor to reflect changes (AJAX or redirect).
					if ( is_admin() && isset( $_GET['post'] ) ) {
						echo '<script>window.location.reload();</script>';
					}
					break;
				}
			}
		}
	}
);
