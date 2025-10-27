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

// Build maps between services and themes based on the queried posts so JS can restrict options.
$service_to_themes = array();
$theme_to_services = array();
if ( ! empty( $q->posts ) ) {
	foreach ( $q->posts as $post_item ) {
		$pid           = $post_item->ID;
		$post_services = get_the_terms( $pid, 'service' );
		$post_themes   = get_the_terms( $pid, 'theme' );

		$s_slugs = array();
		$t_slugs = array();
		if ( ! is_wp_error( $post_services ) && ! empty( $post_services ) ) {
			foreach ( $post_services as $service_term ) {
				// Include the term itself.
				$s_slugs[] = $service_term->slug;
				// Include all ancestor (parent) slugs so parent filters match posts with child terms.
				$anc = get_ancestors( $service_term->term_id, 'service' );
				if ( ! empty( $anc ) ) {
					foreach ( $anc as $anc_id ) {
						$anc_term = get_term( $anc_id, 'service' );
						if ( $anc_term && ! is_wp_error( $anc_term ) ) {
							$s_slugs[] = $anc_term->slug;
						}
					}
				}
			}
			// Ensure uniqueness.
			$s_slugs = array_values( array_unique( $s_slugs ) );
		}
		if ( ! is_wp_error( $post_themes ) && ! empty( $post_themes ) ) {
			foreach ( $post_themes as $theme_term ) {
				$t_slugs[] = $theme_term->slug;
			}
			$t_slugs = array_values( array_unique( $t_slugs ) );
		}

		// Map services -> themes and themes -> services.
		foreach ( $s_slugs as $s_slug ) {
			if ( ! isset( $service_to_themes[ $s_slug ] ) ) {
				$service_to_themes[ $s_slug ] = array();
			}
			foreach ( $t_slugs as $t_slug ) {
				if ( ! in_array( $t_slug, $service_to_themes[ $s_slug ], true ) ) {
					$service_to_themes[ $s_slug ][] = $t_slug;
				}
				if ( ! isset( $theme_to_services[ $t_slug ] ) ) {
					$theme_to_services[ $t_slug ] = array();
				}
				if ( ! in_array( $s_slug, $theme_to_services[ $t_slug ], true ) ) {
					$theme_to_services[ $t_slug ][] = $s_slug;
				}
			}
			if ( empty( $t_slugs ) && ! isset( $service_to_themes[ $s_slug ] ) ) {
				$service_to_themes[ $s_slug ] = array();
			}
		}
		// Ensure themes without services get an empty array key.
		foreach ( $t_slugs as $t_slug ) {
			if ( ! isset( $theme_to_services[ $t_slug ] ) ) {
				$theme_to_services[ $t_slug ] = array();
			}
		}
	}
}
$service_map_json = wp_json_encode( $service_to_themes );
$theme_map_json   = wp_json_encode( $theme_to_services );

?>
<section class="work-index-hero has-primary-black-background-color pt-5">
    <h1 class="mt-5">
        <div class="id-container px-5 pt-2">
            What we create.
        </div>
    </h1>
    <h2>
        <div class="id-container px-5 pt-2">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </div>
    </h2>
</section>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-work-index">
	<div class="cb-work-index__filter-bar py-4 px-5">
		<div class="id-container py-4 px-5 d-flex justify-content-start gap-4 align-items-center cb-work-index__filters" data-service-map='<?php echo esc_attr( $service_map_json ); ?>' data-theme-map='<?php echo esc_attr( $theme_map_json ); ?>'>
			<span style="min-width: 100px; color: var(--col-green-400);">
				FILTER BY:
			</span>
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
			<select id="cb-work-index-service-filter" class="cb-work-index__filter-select form-select">
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
			$theme_terms = get_terms(
				array(
					'taxonomy'   => 'theme',
					'hide_empty' => true,
				)
			);
			if ( ! is_wp_error( $theme_terms ) && ! empty( $theme_terms ) ) {
				?>
			<select id="cb-work-index-theme-filter" class="cb-work-index__filter-select form-select">
				<option value="all">All themes</option>
				<?php
				foreach ( $theme_terms as $theme_term ) {
					?>
					<option value="<?php echo esc_attr( $theme_term->slug ); ?>"><?php echo esc_html( $theme_term->name ); ?></option>
					<?php
				}
				?>
			</select>
				<?php
			}
			?>
			<button id="cb-work-index-filter-reset" class="btn btn-id-outline-green">Reset</button>
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
					// get theme terms for filtering.
					$theme_terms   = get_the_terms( get_the_ID(), 'theme' );
					$theme_classes = '';
					if ( ! is_wp_error( $theme_terms ) && ! empty( $theme_terms ) ) {
						foreach ( $theme_terms as $theme_term ) {
							$theme_classes .= ' theme-' . $theme_term->slug;
						}
					}
					?>
			<div class="col-md-6" data-service-terms="<?php echo esc_attr( trim( $service_classes ) ); ?>" data-theme-terms="<?php echo esc_attr( trim( $theme_classes ) ); ?>">
				<!-- <div class="has-white-background-color has-black-color"><?php echo esc_html( trim( $service_classes ) ); ?></div>
				<div class="has-white-background-color has-black-color"><?php echo esc_html( trim( $theme_classes ) ); ?></div> -->
				<a href="<?= esc_url( get_the_permalink() ); ?>" class="cb-work-index__card">
					<?php
					$video = get_field( 'vimeo_url', get_the_ID() );
					if ( $video ) {
						?>
					<iframe class="work-video" src="<?= esc_url( $video ); ?>&background=1&autoplay=0" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
						<?php
					}
					?>
					<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'cb-work-index__image' ) ); ?>
					<div class="cb-work-index__content px-5">
						<div class="cb-work-index__title">
							<?php the_title(); ?>
						</div>
						<div class="cb-work-index__desc">
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

	let serviceMap = {};
	let themeMap = {};
	try {
		serviceMap = JSON.parse(filterContainer.getAttribute('data-service-map') || '{}');
		themeMap   = JSON.parse(filterContainer.getAttribute('data-theme-map') || '{}');
	} catch (e) {
		// parsing failed; leave maps empty and fall back to simple filtering.
		serviceMap = {};
		themeMap = {};
	}

	const serviceSelect = document.getElementById('cb-work-index-service-filter');
	const themeSelect = document.getElementById('cb-work-index-theme-filter');
	const cardsContainer = document.querySelector('.cb-work-index__cards');
	const resetButton = document.getElementById('cb-work-index-filter-reset');

	// store originals for full resets and to preserve label ordering
	const serviceOriginalHTML = serviceSelect ? serviceSelect.innerHTML : null;
	const themeOriginalHTML = themeSelect ? themeSelect.innerHTML : null;

	function filterCards() {
		const selectedService = serviceSelect ? serviceSelect.value : 'all';
		const selectedTheme = themeSelect ? themeSelect.value : 'all';

		document.querySelectorAll('.cb-work-index__card').forEach(function(card) {
			const cardWrapper = card.parentElement;
			const cardServiceTerms = cardWrapper.getAttribute('data-service-terms') || '';
			const cardThemeTerms = cardWrapper.getAttribute('data-theme-terms') || '';

			const serviceMatch = (selectedService === 'all') || (cardServiceTerms && cardServiceTerms.includes('service-' + selectedService));
			const themeMatch = (selectedTheme === 'all') || (cardThemeTerms && cardThemeTerms.includes('theme-' + selectedTheme));

			if (serviceMatch && themeMatch) {
				cardWrapper.style.display = '';
			} else {
				cardWrapper.style.display = 'none';
			}
		});

		if (cardsContainer) {
			cardsContainer.scrollTop = 0;
		}
	}

	function rebuildOptions(changedType) {
		// When a service is selected, disable theme options that don't appear with that service, and vice versa.
		if ( changedType === 'service' && serviceSelect && themeSelect ) {
			const svc = serviceSelect.value;
			let allowed = null;
			if ( svc === 'all' ) {
				allowed = null; // allow all
			} else {
				allowed = serviceMap[ svc ] || [];
			}

			// Enable/disable theme options instead of removing them so users can still see unavailable choices.
			Array.from( themeSelect.options ).forEach( function( opt ) {
				if ( opt.value === 'all' ) {
					opt.disabled = false;
					return;
				}
				opt.disabled = ( allowed !== null && allowed.indexOf( opt.value ) === -1 );
			} );

			// If the currently selected theme is now disabled, reset it to 'all'.
			if ( themeSelect.selectedOptions.length && themeSelect.selectedOptions[0].disabled ) {
				themeSelect.value = 'all';
			}
		}

		if ( changedType === 'theme' && themeSelect && serviceSelect ) {
			const th = themeSelect.value;
			let allowed = null;
			if ( th === 'all' ) {
				allowed = null;
			} else {
				allowed = themeMap[ th ] || [];
			}

			Array.from( serviceSelect.options ).forEach( function( opt ) {
				if ( opt.value === 'all' ) {
					opt.disabled = false;
					return;
				}
				opt.disabled = ( allowed !== null && allowed.indexOf( opt.value ) === -1 );
			} );

			if ( serviceSelect.selectedOptions.length && serviceSelect.selectedOptions[0].disabled ) {
				serviceSelect.value = 'all';
			}
		}
	}

	if (serviceSelect) {
		serviceSelect.addEventListener('change', function() {
			rebuildOptions('service');
			filterCards();
			// also update the other select's options entirely
            
		});
	}
	if (themeSelect) {
		themeSelect.addEventListener('change', function() {
			rebuildOptions('theme');
			filterCards();
		});
	}
	function resetFilters() {
		if (serviceSelect && serviceOriginalHTML !== null) {
			serviceSelect.innerHTML = serviceOriginalHTML;
			serviceSelect.value = 'all';
		}
		if (themeSelect && themeOriginalHTML !== null) {
			themeSelect.innerHTML = themeOriginalHTML;
			themeSelect.value = 'all';
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
