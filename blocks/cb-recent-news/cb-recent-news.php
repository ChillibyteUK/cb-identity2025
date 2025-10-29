<?php
/**
 * CB Recent News Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id  = $block['id'] ?? '';
$blog_type = get_query_var( 'blog_type', 'insights' );

$background    = '';
$section_title = '';

switch ( $blog_type ) {
	case 'press':
		$background    = 'has-purple-900-background-color';
		$section_title = 'has-purple-800-background-color';
		$arrow         = '/img/arrow-p200.svg';
		break;
	case 'insights':
		$background    = 'has-neutral-1100-background-color';
		$section_title = 'has-neutral-1000-background-color';
		$arrow         = '/img/arrow-n600.svg';
		break;
	case 'perspectives':
		$background    = 'has-neutral-1100-background-color';
		$section_title = 'has-neutral-1000-background-color';
		$arrow         = '/img/arrow-n600.svg';
		break;
	default:
		$background    = 'has-primary-black-background-color';
		$section_title = 'has-primary-black-background-color';
		$arrow         = '/img/arrow-n600.svg';
		break;
}

?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-latest-insights cb-recent-news <?= esc_attr( $background ); ?>">
	<div class="cb-latest-insights__pre-title <?= esc_attr( $section_title ); ?>">
		<div class="id-container pt-2 pb-1 px-5">
			RECENT NEWS
		</div>
	</div>
	<div class="id-container">
		<div class="insight-type-grid grid-type-1 id-container p-5">
			<div class="row g-5">
			<?php
			$args = array(
                'post_type'      => 'post',
                'post_status'    => array( 'publish' ),
                'orderby'        => 'date',
                'order'          => 'DESC', // Descending order.
                'posts_per_page' => 3,    // Get all posts.
				'post__not_in'   => array( get_the_ID() ), // exclude current post.
				'category_name'  => $blog_type,
            );
			$q = new WP_Query( $args );

			$counter = 0;
			while ( $q->have_posts() ) {
				$q->the_post();
				++$counter;
				switch ( $counter ) {
					case 1:
						$col_class = 'col-md-3 insight-type-grid__card-1';
						break;
					case 2:
						$col_class = 'col-md-6 insight-type-grid__card-2';
						break;
					case 3:
						$col_class = 'col-md-3 insight-type-grid__card-3';
						break;
					default:
						$col_class = 'col-md-6';
						break;
				}

				?>
			<div class="<?php echo esc_attr( $col_class ); ?>">			
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="insight-type-grid__card">
					<div class="insight-type-grid__image-wrapper">
						<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'insight-type-grid__image' ) ); ?>
					</div>
					<div class="insight-type-grid__content">
						<div class="insight-type-grid__category">
							<?php
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								echo esc_html( $categories[0]->name );
							}
							?>
						</div>
						<div class="insight-type-grid__title">
							<?php the_title(); ?>
						</div>
						<div class="insight-type-grid__date d-flex align-items-center gap-2">
							<?php echo get_the_date( 'j F Y' ); ?> 
 							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . $arrow ); ?>" width=14 height=13 alt="" />
						</div>
					</div>
				</a>	
			</div>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
