<?php
/**
 * CB Policies Page Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

?>
<style>
.cb-policies-page {
	--_bg-url: url('<?= esc_url( wp_get_attachment_image_url( get_field( 'secondary_background' ), 'full' ) ); ?>');
}
</style>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-policies-page">
	<div class="id-container px-4 px-md-5">
		<div class="cb-policies-page__inner">
			<h1 class="cb-policies-page__title pt-1">
				<?= esc_html( get_field( 'title' ) ); ?>
			</h1>
			<div class="row">
				<div class="col-md-9 cb-policies-page__content">
					<?= wp_kses_post( get_field( 'intro_content' ) ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="cb-policies-page__secondary">
		<div class="id-container px-4 px-md-5 py-5">
			<div class="overlay"></div>
			<div class="row">
				<div class="col-md-9 cb-policies-page__secondary-content">
					<?= wp_kses_post( get_field( 'secondary_content' ) ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
