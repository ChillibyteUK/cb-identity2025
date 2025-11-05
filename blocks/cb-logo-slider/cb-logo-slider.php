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
		<div class="cb-logo-slider__track">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var wrapper = document.querySelector('.cb-logo-slider__track .swiper-wrapper');
	if (!wrapper) return;
	var wrapperWidth = wrapper.scrollWidth;
	var container = document.querySelector('.cb-logo-slider__marquee');
	var containerWidth = container.offsetWidth;
	// Duplicate logos for seamless loop if needed
	if (wrapperWidth < containerWidth * 2) {
		wrapper.innerHTML += wrapper.innerHTML;
		wrapperWidth = wrapper.scrollWidth;
	}
	var pxPerSecond = 80; // Adjust for desired speed
	var distance = wrapperWidth / 2;
	var duration = distance / pxPerSecond;
	gsap.to(wrapper, {
		x: -distance,
		duration: duration,
		ease: 'none',
		repeat: -1,
	});
});
</script>