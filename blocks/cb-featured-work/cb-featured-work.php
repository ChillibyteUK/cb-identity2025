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
		<div class="row g-0">
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
					<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'cb-featured-work__image' ) ); ?>
					<div class="cb-featured-work__content">
						<div class="cb-featured-work__title">
							<?php the_title(); ?>
						</div>
						<div class="cb-featured-work__desc">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
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
