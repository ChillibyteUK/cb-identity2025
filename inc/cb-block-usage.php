<?php
/**
 * Block usage shortcode for debugging/QA.
 *
 * @package cb-idtravel2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders a table of all blocks and the pages/posts that use them.
 *
 * @return string HTML table of block usage.
 */
function block_usage_table_shortcode() {
	// Get all block files from /blocks subdirectories.
	$blocks_dir   = get_stylesheet_directory() . '/blocks/';
	$block_files  = glob( $blocks_dir . '*/*cb-*.php' );

	if ( ! $block_files ) {
		return '<p>No blocks found.</p>';
	}

	// Extract block names from subdirectory and filename (e.g., cb-about-detail/cb-about-detail.php -> cb_about_detail).
	$block_names   = array();
	foreach ( $block_files as $file ) {
		$dir_name    = basename( dirname( $file ) );
		$block_name  = str_replace( '-', '_', $dir_name );
		$block_names[] = $block_name;
	}

	// Query all pages and posts.
	$posts = get_posts(
		array(
			'post_type'      => array( 'page', 'post' ),
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	// Build usage map: block_name => [post1, post2, ...].
	$usage_map = array_fill_keys( $block_names, array() );

	foreach ( $posts as $post ) {
		$content = $post->post_content;

		// Parse block comments from post content.
		// Match both ACF and custom cb blocks: <!-- wp:acf/cb-block-name ... --> and <!-- wp:cb/cb-block-name ... -->
		preg_match_all( '/<!-- wp:(?:acf|cb)\/(cb-[a-z0-9\-_]+)\s/', $content, $matches );

		if ( ! empty( $matches[1] ) ) {
			$found_blocks = array_unique( $matches[1] );
			foreach ( $found_blocks as $found_block ) {
				// Normalize block name: cb-block-name -> cb_block_name.
				$normalized = str_replace( '-', '_', $found_block );
				if ( isset( $usage_map[ $normalized ] ) ) {
					$usage_map[ $normalized ][] = $post;
				}
			}
		}
	}

	// Render table.
	ob_start();
	?>
	<div class="container py-5 has-white-color">
	<table class="block-usage-table" style="width: 100%; border-collapse: collapse;">
		<thead>
			<tr style="border-bottom: 2px solid #ccc;">
				<th style="text-align: left; padding: 8px; font-weight: bold;">Block Name</th>
				<th style="text-align: left; padding: 8px; font-weight: bold;">Used In</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $usage_map as $block_name => $posts_using_block ) :
				$block_display = str_replace( '_', '-', $block_name );
				?>
				<tr style="border-bottom: 1px solid #eee;">
					<td style="padding: 8px; vertical-align: top;"><?= esc_html( $block_display ); ?></td>
					<td style="padding: 8px;">
						<?php if ( empty( $posts_using_block ) ) : ?>
							<em style="color: #999;">Not used</em>
						<?php else : ?>
							<ul style="margin: 0; padding-left: 20px;">
								<?php foreach ( $posts_using_block as $post ) : ?>
									<li>
										<a href="<?= esc_url( get_edit_post_link( $post->ID ) ); ?>" target="_blank">
											<?= esc_html( $post->post_title ); ?>
										</a>
										<span style="color: #999; font-size: 0.9em;">(<?= esc_html( ucfirst( $post->post_type ) ); ?>)</span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'block_usage_table', 'block_usage_table_shortcode' );
