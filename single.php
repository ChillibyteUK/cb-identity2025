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
?>
<main id="main" class="blog">
	<div class="container">
		<div class="post_hero">
			<?= get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'blog_hero__image' ) ); ?>
		</div>
		<div class="pt-5 pb-4">
			<div class="h2">Insights</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="category <?= esc_attr( $first_category->slug ); ?>">// <?= esc_html( $first_category->name ); ?></div>
			</div>
			<div class="col-md-9 pb-5">
				<h1 class="h2"><?= esc_html( get_the_title() ); ?></h1>
				<?php
				if ( $first_category && (
					'podcast' === $first_category->slug ||
					'video' === $first_category->slug ||
					'interview' === $first_category->slug
					) ) {
					$video = get_field( 'video_link' );
					if ( $video ) {
						?>
				<div class="ratio ratio-16x9 mb-4">
					<iframe src="<?= esc_attr( $video ); ?>"></iframe>	
				</div>
						<?php
					}
				}

				echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
    </div>
</main>
<?php
get_footer();
?>