<?php
/**
 * CB Signpost Header Block Template
 *
 * @package cb-identity2025
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$section_id = $block['anchor'] ?? '';
$extra      = $block['className'] ?? '';
$line_class = 'dark-lines';

if ( ! empty( $block['backgroundColor'] ) ) {
	if ( preg_match( '/(\d+)(?!.*\d)/', $block['backgroundColor'], $matches ) ) {
		$line_class = (int) $matches[1] >= 600 ? 'light-lines' : 'dark-lines';
	} else {
		$line_class = 'light-lines';
	}
}

$block_title = get_field( 'title' );
$block_title = is_string( $block_title ) ? trim( $block_title ) : '';

$section_attrs = 'class="cb-signpost-header ' . esc_attr( trim( $bg . ' ' . $fg . ' ' . $line_class . ' ' . $extra ) ) . '"';
if ( '' !== $section_id ) {
	$section_attrs = 'id="' . esc_attr( $section_id ) . '" ' . $section_attrs;
}
?>
<section <?= wp_kses_post( $section_attrs ); ?>>
	<div class="id-container px-4 px-md-5">
		<?= esc_html( $block_title ? $block_title : 'Signpost Subheader' ); ?>
	</div>
</section>
