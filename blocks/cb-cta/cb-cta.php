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



$cta_option = get_query_var( 'cta_choice', get_field( 'cta_choice' ) );

// If not set, and there are CTA rows, use the first CTA as default (without advancing pointer).
if ( ! $cta_option ) {
	$ctas = get_field( 'ctas', 'option' );
	if ( is_array( $ctas ) && count( $ctas ) > 0 && ! empty( $ctas[0]['cta_id'] ) ) {
		$cta_option = $ctas[0]['cta_id'];
	}
}

$cta_title = '';
$content   = '';
$l         = array();
$bg        = '';
$img       = '';
$mask      = '';

if ( have_rows( 'ctas', 'option' ) ) {
    while ( have_rows( 'ctas', 'option' ) ) {
        the_row();
        $cta_id = get_sub_field( 'cta_id' );

        if ( $cta_id === $cta_option ) {
            $cta_title = get_sub_field( 'title' );
            $content   = get_sub_field( 'content' );
            $l         = get_sub_field( 'link' );
            $bg        = get_sub_field( 'background' );
            $img       = get_sub_field( 'image' );
			$mask      = get_sub_field( 'mask' );
            break;
        }
    }
}

?>
<style>
.cb-cta {
	--_bg-url: url('<?= esc_url( wp_get_attachment_image_url( $bg, 'full' ) ); ?>');
}
</style>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-cta">
	<div class="id-container py-5 px-4 px-md-5">
		<div class="row g-5">
			<div class="col-md-6">
				<div class="cb-cta__clip-group cb-cta__clip-group--<?= esc_attr( $mask ); ?>">
					<?= wp_get_attachment_image( $img, 'full', false, array( 'class' => 'img-fluid cb-cta__image' ) ); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="cb-cta__content d-flex flex-column justify-content-center h-100" data-aos="fade">
					<h2 class="cb-cta__title mb-4">
						<?= wp_kses_post( $cta_title ); ?>
					</h2>
					<div class="cb-cta__content mb-4">
						<?= wp_kses_post( $content ); ?>
					</div>
					<div class="cb-cta__button">
						<?php if ( ! empty( $l ) && is_array( $l ) && ! empty( $l['url'] ) ) : ?>
							<a href="<?= esc_url( $l['url'] ); ?>" class="id-button">
								<?= ! empty( $l['title'] ) ? esc_html( $l['title'] ) : esc_html__( 'Learn More', 'cb-identity2025' ); ?>
							</a>
						<?php endif; ?>
					</div>
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
					// add translateY
					img.style.transform = 'translateY(' + translateY.toFixed(1) + 'px)';
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
