<?php
/**
 * CB Latest Insights Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-latest-insights">
	<div class="cb-latest-insights__pre-title">
		<div class="id-container pt-4 pb-3 px-4 px-md-5">
			LATEST NEWS &amp; INSIGHTS
		</div>
	</div>
	<div class="id-container py-5 px-4 px-md-5">

		<div class="row g-5">
			<?php
			$q = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => 6,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			if ( $q->have_posts() ) {
				$counter = 0;
				while ( $q->have_posts() ) {
					$q->the_post();
					++$counter;
					switch ( $counter ) {
						case 1:
							$col_class = 'col-md-4 col-lg-3 cb-latest-insights__card-1';
							break;
						case 2:
							$col_class = 'col-md-8 col-lg-6 cb-latest-insights__card-2';
							break;
						case 3:
							$col_class = 'col-md-4 col-lg-3 cb-latest-insights__card-3';
							break;
						case 4:
							$col_class = 'col-md-8 col-lg-6 cb-latest-insights__card-4';
							break;
						case 5:
							$col_class = 'col-md-6 col-lg-3 cb-latest-insights__card-5';
							break;
						case 6:
							$col_class = 'col-md-6 col-lg-3 cb-latest-insights__card-6';
							break;
						default:
							$col_class = 'col-md-6';
							break;
					}

					?>
			<div class="<?php echo esc_attr( $col_class ); ?>">			
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="cb-latest-insights__card">
					<div class="cb-latest-insights__image-wrapper">
						<?php
						if ( get_the_post_thumbnail( get_the_ID() ) ) {
							echo get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'cb-latest-insights__image' ) );
						} else {
							echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/img/default-post-image.png' ) . '" alt="" class="cb-latest-insights__image" />';
						}
						?>
					</div>
					<div class="cb-latest-insights__content">
						<div class="cb-latest-insights__title">
							<?php the_title(); ?>
						</div>
						<div class="cb-latest-insights__excerpt">
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
