<?php
/**
 * CB CTA Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$l = get_field( 'link' );

?>
<style>
.cb-cta {
	--_bg-url: url('<?= esc_url( wp_get_attachment_image_url( get_field( 'background' ), 'full' ) ); ?>');
}
</style>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-cta">
	<div class="id-container p-5">
		<div class="row g-5">
			<div class="col-md-6">
				<div class="cb-cta__clip-group">
					<?= wp_get_attachment_image( get_field( 'image' ), 'full', false, array( 'class' => 'img-fluid cb-cta__image' ) ); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="cb-cta__content d-flex flex-column justify-content-center h-100">
					<h2 class="cb-cta__title mb-4">
						<?= wp_kses_post( get_field( 'title' ) ); ?>
					</h2>
					<div class="cb-cta__content mb-4">
						<?= wp_kses_post( get_field( 'content' ) ); ?>
					</div>
					<div class="cb-cta__button">
						<a href="<?= esc_url( $l['url'] ); ?>" class="id-button">
							<?= esc_html( $l['title'] ); ?>
						</a>
					</div>
			</div>
		</div>
	</div>
</section>
