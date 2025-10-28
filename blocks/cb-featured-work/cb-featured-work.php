<?php
/**
 * CB Featured Work Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-featured-work">
	<div class="cb-featured-work__pre-title">
		<div class="id-container py-4 px-5">
			FEATURED WORK
		</div>
	</div>
	<div class="id-container">
		<div class="row g-2">
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
				while ( $q->have_posts() ) {
					$q->the_post();
					?>
			<div class="col-md-6">
				<a href="<?= esc_url( get_the_permalink() ); ?>" class="cb-featured-work__card">
					<?php
					$video = get_field( 'vimeo_url', get_the_ID() );
					if ( $video ) {
						?>
					<iframe class="work-video" src="<?= esc_url( $video ); ?>&background=1&autoplay=0" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
						<?php
					}
					?>
					<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'cb-featured-work__image' ) ); ?>
					<div class="cb-featured-work__content px-5">
						<div class="cb-featured-work__title">
							<?php the_title(); ?> <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=23 height=21 alt="" class="cb-services-nav__item-icon" />
						</div>
						<div class="cb-featured-work__desc">
							<?php
							// get the case_study_subtitle field from the cb-case-study-hero block if available.
							if ( ! function_exists( 'cb_find_hero_subtitle' ) ) {
								function cb_find_hero_subtitle($blocks) {
									foreach ($blocks as $block) {
										if (
											isset($block['blockName']) &&
											$block['blockName'] === 'cb/cb-case-study-hero' &&
											!empty($block['attrs']['data']['case_study_subtitle'])
										) {
											return $block['attrs']['data']['case_study_subtitle'];
										}
										if (!empty($block['innerBlocks'])) {
											$found = cb_find_hero_subtitle($block['innerBlocks']);
											if ($found) return $found;
										}
									}
									return '';
								}
							}
							$post_blocks = parse_blocks( get_the_content( null, false, get_the_ID() ) );
							$subtitle = cb_find_hero_subtitle($post_blocks);
							if ( $subtitle ) {
								echo esc_html( $subtitle );
							} else {
								echo wp_kses_post( wp_trim_words( get_the_excerpt(), 18, '...' ) );
							}
							?>
						</div>
					</div>
				</a>
			</div>
					<?php
				}
				wp_reset_postdata();
			}
			?>
		</div>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.cb-featured-work__card').forEach(function(card) {
		const iframe = card.querySelector('iframe.work-video');
		if (!iframe) return;

		card.addEventListener('mouseenter', function() {
			iframe.contentWindow?.postMessage({ method: 'play' }, '*');
		});
		card.addEventListener('mouseleave', function() {
			iframe.contentWindow?.postMessage({ method: 'pause' }, '*');
			iframe.contentWindow?.postMessage({ method: 'setCurrentTime', value: 0 }, '*');
		});
		card.addEventListener('focusin', function() {
			iframe.contentWindow?.postMessage({ method: 'play' }, '*');
		});
		card.addEventListener('focusout', function() {
			iframe.contentWindow?.postMessage({ method: 'pause' }, '*');
			iframe.contentWindow?.postMessage({ method: 'setCurrentTime', value: 0 }, '*');
		});
	});
});
</script>
		<?php
	}
);
