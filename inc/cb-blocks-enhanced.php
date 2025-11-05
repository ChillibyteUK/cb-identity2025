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
 * Save ACF block field group JSON to /acf-json (let the save_post hook handle copying to blocks).
 *
 * @param string $path Default save path.
 * @param array  $field_group The field group being saved.
 * @return string Modified save path.
 */
function cb_save_block_acf_json_path( $path, $field_group = null ) {
    // Always save to default acf-json path.
    // The acf/save_post hook will handle copying to block directories.
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
 * After ACF saves a field group, sync JSON between /acf-json and block folders.
 * ACF may save directly to block folder, so we need to ensure both locations are synced.
 */
add_action(
	'acf/save_post',
	function ( $post_id ) {
    	// Only run for field groups.
    	if ( strpos( $post_id, 'group_' ) !== 0 ) {
        	return;
    	}
    	
    	$acf_json_dir = get_stylesheet_directory() . '/acf-json';
    	$acf_json_file = $acf_json_dir . '/' . $post_id . '.json';
    	$blocks = cb_get_blocks();
    	
    	// Check if the file was saved to a block directory or acf-json.
    	$source_file = null;
    	$source_dir = null;
    	$target_block_dir = null;
    	
    	// First check if it exists in acf-json.
    	if ( file_exists( $acf_json_file ) ) {
        	$source_file = $acf_json_file;
        	$source_dir = 'acf-json';
    	} else {
        	// Check if it exists in any block directory.
        	foreach ( $blocks as $block ) {
            	$block_dir = get_stylesheet_directory() . '/blocks/' . $block;
            	$block_json_file = $block_dir . '/' . $post_id . '.json';
            	if ( file_exists( $block_json_file ) ) {
                	$source_file = $block_json_file;
                	$source_dir = $block;
                	break;
            	}
        	}
    	}
    	
    	if ( ! $source_file ) {
        	return;
    	}
    	
    	$json = json_decode( file_get_contents( $source_file ), true );
    	if ( ! is_array( $json ) || ! isset( $json['location'][0][0]['param'] ) ) {
        	return;
    	}
    	if ( 'block' !== $json['location'][0][0]['param'] ) {
        	return;
    	}
    	
		$location_value = $json['location'][0][0]['value'];
		
		// Find the matching block directory.
		foreach ( $blocks as $block ) {
			$block_json_path = get_stylesheet_directory() . '/blocks/' . $block . '/block.json';
			if ( file_exists( $block_json_path ) ) {
				$block_json = json_decode( file_get_contents( $block_json_path ), true );
				if ( isset( $block_json['name'] ) && $block_json['name'] === $location_value ) {
					$target_block_dir = get_stylesheet_directory() . '/blocks/' . $block;
					break;
				}
			}
		}
		
		if ( ! $target_block_dir ) {
			return;
		}
		
		$target_block_file = $target_block_dir . '/' . $post_id . '.json';
		
		// Sync: Copy to both locations.
		if ( $source_dir === 'acf-json' ) {
			// Source is acf-json, copy to block directory.
			copy( $source_file, $target_block_file );
		} else {
			// Source is block directory, copy to acf-json.
			if ( ! is_dir( $acf_json_dir ) ) {
				mkdir( $acf_json_dir, 0755, true );
			}
			copy( $source_file, $acf_json_file );
			// Also ensure the block directory has the latest.
			if ( $source_file !== $target_block_file ) {
				copy( $source_file, $target_block_file );
			}
		}
	}
);
