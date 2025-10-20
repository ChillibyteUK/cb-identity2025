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
<script>
// Subtle parallax effect for .cb-cta__image
document.addEventListener('DOMContentLoaded', function () {
	var img = document.querySelector('.cb-cta__image');
	if (!img) return;
	var lastScrollY = window.scrollY;
	var ticking = false;

	function onScroll() {
		lastScrollY = window.scrollY;
		if (!ticking) {
			window.requestAnimationFrame(update);
			ticking = true;
		}
	}

		function update() {
			var rect = img.getBoundingClientRect();
			var windowHeight = window.innerHeight;
			// Only apply if in viewport
			if (rect.bottom > 0 && rect.top < windowHeight) {
				// Calculate percent scrolled through the section
				var section = img.closest('.cb-cta');
				if (section) {
					var sectionRect = section.getBoundingClientRect();
					var percent = (windowHeight - sectionRect.top) / (windowHeight + sectionRect.height);
					// Clamp between 0 and 1
					percent = Math.max(0, Math.min(1, percent));
					// Parallax: move image up to 40px up or down
					var translateY = (percent - 0.5) * 80; // Range: -40px to +40px
					// Retain scaleX(-1) and add translateY
					img.style.transform = 'scaleX(-1) translateY(' + translateY.toFixed(1) + 'px)';
				}
			}
			ticking = false;
		}

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll);
	// Initial position
	onScroll();
});
</script>
