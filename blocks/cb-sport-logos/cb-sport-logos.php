<?php
/**
 * CB Sport Logos Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-logo-slider py-3">
	<div class="id-container cb-logo-slider__marquee">
		<div class="cb-logo-slider__track">
			<?php
			$logos = get_field( 'logos' );
			if ( $logos ) {
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-logo-slider__logo img-fluid', 'alt' => get_post_meta( $logo, '_wp_attachment_image_alt', true ) ) ); ?>
					<?php
				}
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-logo-slider__logo img-fluid', 'alt' => get_post_meta( $logo, '_wp_attachment_image_alt', true ) ) ); ?>
					<?php
				}
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-logo-slider__logo img-fluid', 'alt' => get_post_meta( $logo, '_wp_attachment_image_alt', true ) ) ); ?>
					<?php
				}
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-logo-slider__logo img-fluid', 'alt' => get_post_meta( $logo, '_wp_attachment_image_alt', true ) ) ); ?>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>
<?php
