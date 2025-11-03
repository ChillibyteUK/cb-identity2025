<?php
/**
 * Template Name: Text Page
 *
 * @package cb-identity2025
 */

defined( 'ABSPATH' ) || exit;
get_header();

?>
<main id="main" class="text-page">
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
		<div class="row post-content-row py-5 mb-5">
			<div class="col-md-3"></div>
			<div class="col-md-9 text-white post-content px-5 ps-md-0 pe-md-5">
				<?php
				echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
?>