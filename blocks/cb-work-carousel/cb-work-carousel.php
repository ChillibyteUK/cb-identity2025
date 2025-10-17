<?php
/**
 * CB Work Carousel Block Template
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
$block_classes = array( 'block', 'cb-work-carousel' );
if ( ! empty( $block['className'] ) ) {
    $block_classes[] = $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $block_classes[] = 'align' . $block['align'];
}

// Get fields.
$block_title   = get_field( 'title' );
$block_content = get_field( 'content' );

// Output.
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>">
	<div class="cb-work-carousel__container">
		<?php
		$q = new WP_Query(
			array(
				'post_type'      => 'case_study',
				'posts_per_page' => 4,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		if ( $q->have_posts() ) {
			?>
		<div class="swiper work-swiper">
			<div class="swiper-wrapper">
				<?php
				while ( $q->have_posts() ) {
					$q->the_post();
					?>
				<div class="swiper-slide">
					<a href="<?php echo esc_url( get_permalink() ); ?>" class="work-carousel-link" tabindex="0">
						<?php
						$video = get_field( 'vimeo_url', get_the_ID() );
						if ( $video ) {
							?>
						<div class="iframe-cover swiper-video">
							<iframe src="<?= esc_url( $video ); ?>&background=1&autoplay=0" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
						</div>
							<?php
						}
						?>
						<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'img-fluid swiper-poster' ) ); ?>
						<div class="work-carousel-text">
							<div class="id-container pb-2">
								<div class="work-carousel-title"><?php the_title(); ?></div>
								<div class="work-carousel-excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?></div>
							</div>
						</div>
					</a>
				</div>
					<?php
				}
				wp_reset_postdata();
				?>
				<div class="id-container px-5">
					<div class="swiper-navigation">
						<div class="swiper-button-prev" tabindex="0" role="button" aria-label="Previous slide"></div>
						<div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide"></div>
					</div>
				</div>
			</div>
		</div>
			<?php
		}
		?>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	const swiper = new Swiper('.work-swiper', {
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		effect: 'fade',
		fadeEffect: {
			crossFade: true
		},
		autoplay: {
			delay: 4000,
			disableOnInteraction: true,
			pauseOnMouseEnter: true,
		},
		slidesPerView: 1,
		spaceBetween: 0,
		pagination: false,
	});
	document.querySelectorAll('.swiper-video, .work-carousel-link').forEach(function(card) {
		card.addEventListener('mouseenter', function() {
			if (swiper.autoplay) swiper.autoplay.stop();
		});
		card.addEventListener('mouseleave', function() {
			if (swiper.autoplay) swiper.autoplay.start();
		});
		card.addEventListener('focusin', function() {
			if (swiper.autoplay) swiper.autoplay.stop();
		});
		card.addEventListener('focusout', function() {
			if (swiper.autoplay) swiper.autoplay.start();
		});
	});
	document.querySelectorAll('.swiper-video').forEach(function(card) {
		const iframe = card.querySelector('iframe');
		if (!iframe) return;
		card.addEventListener('mouseenter', function() {
			if (iframe.contentWindow) {
				iframe.contentWindow.postMessage({ method: 'play' }, '*');
			}
		});
		card.addEventListener('mouseleave', function() {
			if (iframe.contentWindow) {
				iframe.contentWindow.postMessage({ method: 'pause' }, '*');
				iframe.contentWindow.postMessage({ method: 'setCurrentTime', value: 0 }, '*');
			}
		});
	});
});
</script>
		<?php
	}
);