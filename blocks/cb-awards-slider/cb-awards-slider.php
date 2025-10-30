<?php
/**
 * CB Awards Slider Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-awards-slider py-3">
	<div class="id-container cb-awards-slider__marquee">
		<div class="cb-awards-slider__track">
			<?php
			$logos = get_field( 'logos' );
			if ( $logos ) {
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-awards-slider__logo img-fluid' ) ); ?>
					<?php
				}
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-awards-slider__logo img-fluid' ) ); ?>
					<?php
				}
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-awards-slider__logo img-fluid' ) ); ?>
					<?php
				}
				foreach ( $logos as $logo ) {
					?>
					<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-awards-slider__logo img-fluid' ) ); ?>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>
<?php
