<?php
/**
 * CB Service Page Header Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-service-page-header">
	<div class="id-container px-5">
		<h1><?= esc_html( get_field( 'service_title' ) ); ?></h1>
		<div class="row">
			<div class="col-md-9">
				<p class="cb-service-page-header__intro-text"><?= wp_kses_post( get_field( 'service_intro_text' ) ); ?></p>
			</div>
		</div>
	</div>
</section>
