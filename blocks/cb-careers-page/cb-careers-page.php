<?php
/**
 * CB Careers Page Block Template
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
.cb-careers-page {
	--_bg-url: url('<?= esc_url( wp_get_attachment_image_url( get_field( 'background' ), 'full' ) ); ?>');
}
</style>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-careers-page">
	<div class="id-container">
		<div class="overlay"></div>
		<div class="cb-careers-page__inner px-5">
			<h1 class="cb-careers-page__title">
				<?= esc_html( get_field( 'title' ) ); ?>
			</h1>
			<div class="row">
				<div class="col-md-9 cb-careers-page__content">
					<?= wp_kses_post( get_field( 'content' ) ); ?>
					<?php
					if ( get_field( 'link' ) ) {
						$l = get_field( 'link' );
						?>
					<div class="mt-5">
						<a href="<?= esc_url( $l['url'] ); ?>" class="id-button"><?= esc_html( $l['title'] ); ?></a>
					</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
