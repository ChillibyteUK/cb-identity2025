<?php
/**
 * Template for displaying the blog index page.
 *
 * @package cb-identity2025
 */

defined( 'ABSPATH' ) || exit;

$page_for_posts = get_option( 'page_for_posts' );

get_header();
?>
<main id="main" class="news-insights">
	<section id="<?php echo esc_attr( $block_id ); ?>" class="news-hero has-primary-black-background-color pt-5">
		<h1 class="mt-5">
			<div class="id-container px-5 pt-1">
				News, Insights &amp; Press
			</div>
		</h1>
		<h2>
			<div class="id-container px-5 pt-2">
				Creating news and leading conversations that shape our industry	
			</div>
		</h2>
		<div class="row">
			<div class="col-md-9 offset-md-3 news-hero__content">
				<?php
				// get content from page_for_posts.
				echo wp_kses_post(
					apply_filters(
						'the_content',
						$page_for_posts ? get_post_field( 'post_content', $page_for_posts ) : ''
					)
				);
				?>
			</div>
		</div>
	</section>
	<section class="insight-type has-primary-black-background-color">
		<a class="insight-type__header" href="/news/category/insights/">
			<div class="id-container d-flex align-items-center justify-content-between px-5">
				<div>Insights</div>
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=65 height=60 alt="" class="cb-services-nav__item-icon" />
			</div>
		</a>
		<div class="insight-type-grid grid-type-1 id-container p-5">
			<div class="row g-5">
			<?php
			$args = array(
                'post_type'      => 'post',
                'post_status'    => array( 'publish' ),
                'orderby'        => 'date',
                'order'          => 'DESC', // Descending order.
                'posts_per_page' => 3,    // Get all posts.
				'category_name'  => 'insights,perspectives',
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
 							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-n600.svg' ); ?>" width=14 height=13 alt="" />
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
	<section class="insight-type has-purple-900-background-color">
		<a class="insight-type__header" href="/news/category/press/">
			<div class="id-container d-flex align-items-center justify-content-between px-5">
				<div>Press</div>
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=65 height=60 alt="" class="cb-services-nav__item-icon" />
			</div>
		</a>
		<div class="insight-type-grid grid-type-2 id-container p-5">
			<div class="row g-5">
			<?php
			$args = array(
                'post_type'      => 'post',
                'post_status'    => array( 'publish' ),
                'orderby'        => 'date',
                'order'          => 'DESC', // Descending order.
                'posts_per_page' => 3,    // Get all posts.
				'category_name'  => 'press',
            );
			$q = new WP_Query( $args );

			$counter = 3;
			while ( $q->have_posts() ) {
				$q->the_post();
				++$counter;
				switch ( $counter ) {
					case 4:
						$col_class = 'col-md-6 insight-type-grid__card-4';
						break;
					case 5:
						$col_class = 'col-md-3 insight-type-grid__card-5';
						break;
					case 6:
						$col_class = 'col-md-3 insight-type-grid__card-6';
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
 							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-p200.svg' ); ?>" width=14 height=13 alt="" />
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
	<?php

	// include cta template.
	set_query_var( 'cta_background', 114 );
	set_query_var( 'cta_image', 164 );
	set_query_var( 'cta_title', 'Experience<br>Changes<br>Everything' );
	set_query_var( 'cta_content', 'What do you want to change?<br>We want to hear what matters most to you.' );
	set_query_var( 'cta_link', array( 'url' => '/contact/', 'title' => 'Start your brief' ) );
	get_template_part( 'blocks/cb-cta/cb-cta' );

	?>
</main>
<?php
get_footer();
?>