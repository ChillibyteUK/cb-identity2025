<?php
/**
 * CB Brand Title Text Block Template
 *
 * @package cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

// Block classes.
$block_classes = array( 'block', 'cb-brand-title-text' );
if ( ! empty( $block['className'] ) ) {
    $block_classes[] = $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $block_classes[] = 'align' . $block['align'];
}

$l = get_field( 'link' );

?>
<section id="<?= esc_attr( $block_id ); ?>" class="<?= esc_attr( implode( ' ', $block_classes ) ); ?>">
	<div class="cb-brand-title-text__pre-title">
		<div class="id-container px-5">
			<?= esc_html( get_field( 'pre_title' ) ); ?>
		</div>
	</div>
	<div class="id-container p-5">
		<div class="cb-brand-title-text__container">
			<div class="row">
				<div class="col-md-7 cb-brand-title-text__title">
					<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/Experience.svg' ); ?>" alt="">
					<?php
					/*
					$section_title = get_field( 'title' );

					$lines = preg_split( '/<br\s*\/?>/i', $section_title );

					// Wrap each line in a span.
					$wrapped = array_map(
						function ( $line ) {
							return '<span>' . trim( $line ) . '</span>';
						},
						$lines
					);

					echo wp_kses_post( implode( '', $wrapped ) );
					*/
					?>
				</div>
			</div>
		</div>
		<div class="cb-brand-title-text__content-wrapper pb-5">
			<div class="row">
				<div class="col-md-6 offset-md-6">
					<div class="cb-brand-title-text__content-heading mb-4">
						<?= wp_kses_post( get_field( 'content_heading' ) ); ?>
					</div>
					<div class="cb-brand-title-text__content">
						<?= wp_kses_post( get_field( 'content' ) ); ?>
					</div>
					<div class="cb-brand-title-text__link mt-4">
						<a href="<?= esc_url( $l['url'] ); ?>" target="<?= esc_attr( $l['target'] ); ?>" class="id-button">
							<?= esc_html( $l['title'] ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
