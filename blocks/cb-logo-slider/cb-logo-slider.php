<?php
/**
 * CB Logo Slider Block Template
 *
 * @package cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-logo-slider">
	<div class="id-container cb-logo-slider__marquee">
		<div class="swiper cb-logo-slider__track">
			<div class="swiper-wrapper">
				<?php
				$logos = get_field( 'logos', 'option' );
				if ( $logos ) {
					foreach ( $logos as $logo ) {
						?>
						<div class="swiper-slide">
							<?= wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'cb-logo-slider__logo img-fluid' ) ); ?>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
  new Swiper('.cb-logo-slider__track', {
	slidesPerView: 'auto',
	spaceBetween: 0,
	loop: true,
	speed: 6000,
	autoplay: {
	  delay: 0,
	  disableOnInteraction: false,
	},
	allowTouchMove: false,
  });
});
</script>
<?php
