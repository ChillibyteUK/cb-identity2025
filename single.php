<?php
/**
 * Template for displaying single posts.
 *
 * @package cb-identity2025
 */

defined( 'ABSPATH' ) || exit;
get_header();

// get categories.
$categories     = get_the_category();
$first_category = null;
if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
	// get space separated list of category slugs.
	$first_category = $categories[0];
}

$post_style = $first_category->slug;

switch ( $post_style ) {
	case 'press':
		$post_style = 'post-press';
		break;
	case 'insights':
		$post_style = 'post-insight';
		break;
	case 'perspectives':
		$post_style = 'post-insight';
		break;
	default:
		$post_style = 'post-press';
		break;
}

?>
<main id="main" class="single-blog <?= esc_attr( $post_style ); ?>">
	<?php
	if ( get_the_post_thumbnail( get_the_ID() ) ) {
		?>
	<div class="id-container pt-5 pb-4">
		<div class="post-hero-clip-group">
			<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'post-hero-image' ) ); ?>
		</div>
	</div>
		<?php
	}
	?>
	<div class="category-wrapper">
		<div class="id-container px-5">
			<div class="category <?= esc_attr( $first_category->slug ); ?>"><?= esc_html( $first_category->name ); ?></div>
		</div>
	</div>
	<div class="post-title">
		<div class="id-container px-5">
			<div class="row">
				<div class="col-md-9">
					<h1 class="pt-1"><?= esc_html( get_the_title() ); ?></h1>
				</div>
			</div>
		</div>
	</div>
	<div class="id-container">
		<div class="row post-content-row mb-5">
			<div class="col-md-3"></div>
			<div class="col-md-9 post-content px-5 ps-md-0 pe-md-5">
				<?php
				echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</div>
	<div class="post-date-row">
		<div class="id-container">
			<div class="row post-content-row">
				<div class="col-md-3"></div>
				<div class="col-md-9 post-content px-5 ps-md-0 pe-md-5">
					<div class="container post-date pt-3">
						<?= get_the_date( 'j F Y' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<section class="recent-news">
		<?php
		set_query_var( 'blog_type',  $first_category->slug );
		get_template_part( 'blocks/cb-recent-news/cb-recent-news' );
		?>
	</section>
	<?php

	// include cta template.
	set_query_var( 'cta_background', 114 );
	set_query_var( 'cta_image', 164 );
	set_query_var( 'cta_title', 'Experience<br>Changes<br>Everything' );
	set_query_var( 'cta_content', 'What do you want to change?<br>We want to hear what matters most to you.' );
	set_query_var(
		'cta_link',
		array(
			'url'   => '/contact/',
			'title' => 'Start your brief',
		)
	);
	get_template_part( 'blocks/cb-cta/cb-cta' );

	?>
</main>
<?php
get_footer();
?>