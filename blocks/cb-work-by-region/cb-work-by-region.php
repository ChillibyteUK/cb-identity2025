<?php
/**
 * CB Work by Region Block Template
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

$region = get_field( 'region' );

$pretitle         = 'RELATED';
$pretitle_padding = 'pt-4 pb-3';

if ( $region ) {
	return;
}

$tax_query[] = array(
	'taxonomy' => 'region',
	'field'    => 'term_id',
	'terms'    => $region->term_id,
);

$q = new WP_Query(
	array(
		'post_type'      => 'case_study',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => $tax_query,
	)
);



if ( $q->have_posts() ) {
	?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-related-work">
	<div class="cb-related-work__pre-title">
		<div class="id-container <?= esc_attr( $pretitle_padding ); ?> px-5">
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
					<?= get_work_image( get_the_ID(), 'cb-related-work__image' ); ?>
					<div class="cb-related-work__content px-5">
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
