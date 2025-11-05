<?php
/**
 * CB Related Work Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

// Get service and theme terms from current post.


// Get all selected services for this post.
$services = wp_get_post_terms( get_the_ID(), 'service' );
// $themes   = wp_get_post_terms( get_the_ID(), 'theme' );

// Resolve all selected services to their parent (if any), ignore children.
$primary_parent_service = null;
if ( ! empty( $services ) ) {
	$parent_services = array();
	foreach ( $services as $service ) {
        	if ( 0 !== $service->parent && $service->parent ) {
			$parent_term = get_term( $service->parent, 'service' );
			if ( $parent_term && ! is_wp_error( $parent_term ) ) {
				$parent_services[] = $parent_term;
			}
		} else {
			$parent_services[] = $service;
		}
	}
	// Use only the first parent service (primary).
	if ( ! empty( $parent_services ) ) {
		$primary_parent_service = $parent_services[0];
	}
}

$pretitle         = 'RELATED';
$pretitle_padding = 'pt-4 pb-3';

// If no service terms, try to derive from page slug (for service description pages).
if ( empty( $services ) && is_page() ) {
	$page_slug          = get_post_field( 'post_name', get_the_ID() );
	$maybe_service_term = get_term_by( 'slug', $page_slug, 'service' );
	if ( $maybe_service_term && ! is_wp_error( $maybe_service_term ) ) {
		// Use only the parent and its direct children for related work selection.
		$primary_parent_service = $maybe_service_term;
	}
	$pretitle         = get_the_title( get_the_ID() );
	$pretitle_padding = 'pt-2 pb-1';
}



// Only include posts tagged with the selected parent service or its direct children.
$service_ids = array();
if ( $primary_parent_service ) {
	$service_ids[] = $primary_parent_service->term_id;
	// Get all direct children of the parent service.
	$child_terms = get_terms( array(
		'taxonomy' => 'service',
		'parent' => $primary_parent_service->term_id,
		'hide_empty' => false
	) );
	if ( ! is_wp_error( $child_terms ) && ! empty( $child_terms ) ) {
		foreach ( $child_terms as $child ) {
			$service_ids[] = $child->term_id;
		}
	}
}

// $theme_ids = array();
// foreach ( $themes as $theme ) {
// 	$theme_ids[] = $theme->term_id;
// }

$tax_query = array( 'relation' => 'OR' );
if ( ! empty( $service_ids ) ) {
	$tax_query[] = array(
		'taxonomy' => 'service',
		'field'    => 'term_id',
		'terms'    => $service_ids,
	);
}
// if ( ! empty( $theme_ids ) ) {
// 	$tax_query[] = array(
// 		'taxonomy' => 'theme',
// 		'field'    => 'term_id',
// 		'terms'    => $theme_ids,
// 	);
// }

$q = new WP_Query(
	array(
		'post_type'      => 'case_study',
		'posts_per_page' => 4,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => $tax_query,
		'post__not_in'   => array( get_the_ID() ),
	)
);



if ( $q->have_posts() ) {
	?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-related-work">
	<div class="cb-related-work__pre-title">
		<div class="id-container <?= esc_attr( $pretitle_padding ); ?> px-4 px-md-5">
			<?= esc_html( $pretitle ); ?> WORK
		</div>
	</div>
	<div class="id-container">
		<div class="row g-2">
	<?php
	while ( $q->have_posts() ) {
		$q->the_post();
		?>
			<div class="col-md-6">
				<a href="<?= esc_url( get_the_permalink() ); ?>" class="cb-related-work__card">
					<?= wp_kses_post( get_work_image( get_the_ID(), 'cb-related-work__image' ) ); ?>
					<div class="cb-related-work__content px-4 px-md-5">
						<div class="cb-related-work__title">
							<?php the_title(); ?> <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=23 height=21 alt="" class="cb-services-nav__item-icon" />
						</div>
						<div class="cb-related-work__desc">
							<?php
							// get the case_study_subtitle field from the cb-case-study-hero block if available.
							if ( ! function_exists( 'cb_find_hero_subtitle' ) ) {
								/**
								 * Recursively find the hero subtitle from blocks.
								 *
								 * @param array $blocks The parsed blocks array.
								 * @return string Subtitle if found, empty string otherwise.
								 */
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
	?>
		</div>
	</div>
</section>
	<?php
	wp_reset_postdata();
}
