<?php
/**
 * File responsible for registering custom ACF blocks and modifying core block arguments.
 *
 * @package cb-identity2025
 */

// Auto-sync ACF field groups from acf-json folder.
add_filter(
	'acf/settings/save_json',
	function ( $path ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		return get_stylesheet_directory() . '/acf-json';
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		unset( $paths[0] );
		$paths[] = get_stylesheet_directory() . '/acf-json';
		return $paths;
	}
);

/**
 * Modifies the arguments for specific core block types.
 *
 * @param array  $args The block type arguments.
 * @param string $name The block type name.
 * @return array Modified block type arguments.
 */
function core_block_type_args( $args, $name ) {

	if ( 'core/paragraph' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}
	if ( 'core/heading' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}
	if ( 'core/list' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}

    return $args;
}
add_filter( 'register_block_type_args', 'core_block_type_args', 10, 3 );

/**
 * Helper function to detect if footer.php is being rendered.
 *
 * @return bool True if footer.php is being rendered, false otherwise.
 */
function is_footer_rendering() {
    $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
    foreach ( $backtrace as $trace ) {
        if ( isset( $trace['file'] ) && basename( $trace['file'] ) === 'footer.php' ) {
            return true;
        }
    }
    return false;
}

/**
 * Adds a container div around the block content unless footer.php is being rendered.
 *
 * @param array  $attributes The block attributes.
 * @param string $content    The block content.
 * @return string The modified block content wrapped in a container div.
 */
function modify_core_add_container( $attributes, $content ) {
	if ( is_footer_rendering() ) {
		return $content;
	}

	// Prevent container if this is a list inside a list (nested list block).
	// Check if the parent block is also a list block by inspecting the global block context.
	global $cb_is_inside_list_block;
	$is_nested = ! empty( $cb_is_inside_list_block );

	$is_list_block = false;
	if ( ! empty( $attributes['blockName'] ) && $attributes['blockName'] === 'core/list' ) {
		$is_list_block = true;
	}

	// Set flag for nested list detection
	if ( $is_list_block ) {
		if ( ! $is_nested ) {
			$cb_is_inside_list_block = true;
		}
	}

	// Only add container if not a nested list
	$output = $content;
	if ( ! ( $is_list_block && $is_nested ) ) {
		ob_start();
		?>
		<div class="id-container">
			<?= wp_kses_post( $content ); ?>
		</div>
		<?php
		$output = ob_get_clean();
	}

	// Unset flag after rendering this block
	if ( $is_list_block && ! $is_nested ) {
		unset( $cb_is_inside_list_block );
	}

	return $output;
}