<?php
/**
 * CB Work Index Block Template
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
.cb-work-index {
	--_bg-url: url('<?= esc_url( get_stylesheet_directory_uri() . '/blocks/cb-work-index/bg.jpg' ); ?>');
}
</style>
<?php

$q = new WP_Query(
	array(
		'post_type'      => 'case_study',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);



?>
<section class="work-index-hero has-primary-black-background-color pt-5">
    <h1 class="mt-5">
        <div class="id-container px-4 px-md-5 pt-2">
            Our work
        </div>
    </h1>
    <h2>
        <div class="id-container px-4 px-md-5 pt-2 pb-1">
            Where experience changes everything
        </div>
    </h2>
	<a href="<?= esc_url( get_the_permalink() ); ?>" class="work-index-hero__background">
		<?php
		// get title and thumbnail of first sticky or latest case study for background image.
		$bg_case_study = get_field( 'hero_case_study' )[0] ?? null;


		if ( ! $bg_case_study ) {
			$latest_query = new WP_Query(
				array(
					'post_type'      => 'case_study',
					'posts_per_page' => 1,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			if ( $latest_query->have_posts() ) {
				$latest_query->the_post();
				$bg_case_study = get_the_ID();
				wp_reset_postdata();
			}
		}
		if ( $bg_case_study ) {
			$bg_image_id = get_post_thumbnail_id( $bg_case_study );
			if ( $bg_image_id ) {
				echo wp_get_attachment_image( $bg_image_id, 'full', false, array( 'class' => 'work-index-hero__image' ) );
			}
		}
		?>
		<div class="overlay"></div>
		<div class="work-index-hero__content px-4 px-md-5">
			<div class="work-index-hero__title">
				<?php echo esc_html( get_the_title( $bg_case_study ) ); ?> <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=23 height=21 alt="" class="cb-services-nav__item-icon" />
			</div>
			<div class="work-index-hero__desc">
				<?php
				// get the case_study_subtitle field from the cb-case-study-hero block if available.
				if ( ! function_exists( 'cb_find_hero_subtitle' ) ) {
					function cb_find_hero_subtitle( $blocks ) {
						foreach ( $blocks as $block ) {
							if (
								isset( $block['blockName'] ) &&
								'cb/cb-case-study-hero' === $block['blockName'] &&
								! empty( $block['attrs']['data']['case_study_subtitle'] )
							) {
								return $block['attrs']['data']['case_study_subtitle'];
							}
							if ( ! empty( $block['innerBlocks'] ) ) {
								$found = cb_find_hero_subtitle( $block['innerBlocks'] );
								if ( $found ) {
									return $found;
								}
							}
						}
						return '';
					}
				}
				$post_blocks = parse_blocks( get_post_field( 'post_content', $bg_case_study ) );
				$subtitle    = cb_find_hero_subtitle( $post_blocks );
				if ( $subtitle ) {
					echo esc_html( $subtitle );
				} else {
					$excerpt = get_the_excerpt( $bg_case_study );
					echo wp_kses_post( wp_trim_words( $excerpt, 18, '...' ) );
				}
				?>
			</div>
		</div>
	</a>
</section>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-work-index">
	<div class="cb-work-index__filter-bar py-4 px-4 px-md-5">
		<div class="id-container py-4 cb-work-index__filters" data-service-map='<?php echo esc_attr( $service_map_json ); ?>' data-theme-map='<?php echo esc_attr( $theme_map_json ); ?>'>
			<div class="row g-4 align-items-center">
				<div class="col-12 col-md-2 col-lg-2 col-x1-1">
					FILTER BY:
				</div>
				<div class="col-md-6">
			<?php
			$service_terms = get_terms(
				array(
					'taxonomy'   => 'service',
					'hide_empty' => true,
					'parent'     => 0, // only top-level parent terms.
				)
			);
			if ( ! is_wp_error( $service_terms ) && ! empty( $service_terms ) ) {
				?>
			<select id="cb-work-index-service-filter" class="cb-work-index__filter-select">
				<option value="all">All Services</option>
				<?php
				foreach ( $service_terms as $service_term ) {
					?>
					<option value="<?php echo esc_attr( $service_term->slug ); ?>"><?php echo esc_html( $service_term->name ); ?></option>
					<?php
				}
				?>
			</select>
				<?php
			}
			?>
				</div>
				<div class="col-md-2">
					<button id="cb-work-index-filter-reset" class="btn btn-id-outline-green">Reset</button>
				</div>
			</div>
		</div>
	</div>
	<div class="id-container">
		<div class="cb-work-index__cards row g-2">
			<?php
			if ( $q->have_posts() ) {
				while ( $q->have_posts() ) {
					$q->the_post();
					// get service terms for filtering and include ancestors so parent filters match.
					$service_terms   = get_the_terms( get_the_ID(), 'service' );
					$service_classes = '';
					if ( ! is_wp_error( $service_terms ) && ! empty( $service_terms ) ) {
						$s_slugs = array();
						foreach ( $service_terms as $service_term ) {
							$s_slugs[] = $service_term->slug;
							$ancestors = get_ancestors( $service_term->term_id, 'service' );
							if ( ! empty( $ancestors ) ) {
								foreach ( $ancestors as $anc_id ) {
									$anc_term = get_term( $anc_id, 'service' );
									if ( $anc_term && ! is_wp_error( $anc_term ) ) {
										$s_slugs[] = $anc_term->slug;
									}
								}
							}
						}
						$s_slugs = array_values( array_unique( $s_slugs ) );
						foreach ( $s_slugs as $slug ) {
							$service_classes .= ' service-' . $slug;
						}
					}
						// ...existing code...
					?>
			<div class="col-md-6" data-service-terms="<?php echo esc_attr( trim( $service_classes ) ); ?>">
				<?php
				/*
				<!-- <div class="has-white-background-color has-black-color"><?php echo esc_html( trim( $service_classes ) ); ?></div>
				<div class="has-white-background-color has-black-color"><?php echo esc_html( trim( $theme_classes ) ); ?></div> -->
				*/
				$video = get_field( 'vimeo_url', get_the_ID() );
				$has_video = $video ? 'has_video' : '';
				?>
				<a href="<?= esc_url( get_the_permalink() ); ?>" class="cb-work-index__card <?= esc_attr( $has_video ); ?>">
					<?php
					if ( $video ) {
						?>
					<iframe class="work-video" src="<?= esc_url( $video ); ?>&background=1&autoplay=0" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
						<?php
					}
					?>
					<?php echo get_work_image( get_the_ID(), 'cb-work-index__image' ); ?>
					<div class="cb-work-index__content px-4 px-md-5">
						<div class="cb-work-index__title">
							<?php the_title(); ?> <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=23 height=21 alt="" class="cb-services-nav__item-icon" />
						</div>
						<div class="cb-work-index__desc">
							<?php
							// get the case_study_subtitle field from the cb-case-study-hero block if available.
							if ( ! function_exists( 'cb_find_hero_subtitle' ) ) {
								function cb_find_hero_subtitle( $blocks ) {
									foreach ( $blocks as $block ) {
										if (
											isset( $block['blockName'] ) &&
											'cb/cb-case-study-hero' === $block['blockName'] &&
											! empty( $block['attrs']['data']['case_study_subtitle'] )
										) {
											return $block['attrs']['data']['case_study_subtitle'];
										}
										if ( ! empty( $block['innerBlocks'] ) ) {
											$found = cb_find_hero_subtitle( $block['innerBlocks'] );
											if ( $found ) {
												return $found;
											}
										}
									}
									return '';
								}
							}
							$post_blocks = parse_blocks( get_the_content( null, false, get_the_ID() ) );
							$subtitle    = cb_find_hero_subtitle( $post_blocks );
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
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Video hover play/pause binding.
	document.querySelectorAll('.cb-work-index__card').forEach(function(card) {
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

	// Cross-filtering selects and maps.
	const filterContainer = document.querySelector('.cb-work-index__filters');
	if (!filterContainer) return;

	const serviceSelect = document.getElementById('cb-work-index-service-filter');
	const cardsContainer = document.querySelector('.cb-work-index__cards');
	const resetButton = document.getElementById('cb-work-index-filter-reset');

	let serviceTom = serviceSelect ? new TomSelect(serviceSelect, {
		allowEmptyOption: true,
		closeAfterSelect: true
	}) : null;

	function filterCards() {
		const selectedService = serviceTom ? serviceTom.getValue() : (serviceSelect ? serviceSelect.value : 'all');

		document.querySelectorAll('.cb-work-index__card').forEach(function(card) {
			const cardWrapper = card.parentElement;
			const cardServiceTerms = cardWrapper.getAttribute('data-service-terms') || '';
			const serviceMatch = (selectedService === 'all') || (cardServiceTerms && cardServiceTerms.includes('service-' + selectedService));
			if (serviceMatch) {
				cardWrapper.style.display = '';
			} else {
				cardWrapper.style.display = 'none';
			}
		});

		if (cardsContainer) {
			cardsContainer.scrollTop = 0;
		}
	}

	if (serviceTom) {
		serviceTom.on('change', function() {
			filterCards();
		});
	}

	function resetFilters() {
		if (serviceTom) {
			serviceTom.setValue('all');
		}
		filterCards();
	}

	if (resetButton) {
		resetButton.addEventListener('click', function(e) {
			e.preventDefault();
			resetFilters();
		});
	}
});
</script>
		<?php
	}
);
