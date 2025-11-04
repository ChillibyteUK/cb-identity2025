<?php
/**
 * CB Contact Form Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-contact-form">
	<div class="id-container px-4 px-md-5">
		<h2 class="cb-contact-form__title pt-4 pb-3">
			<?= esc_html( get_field( 'title' ) ); ?>
		</h2>
		<div class="row">
			<div class="col-md-6">
				<div class="cb-contact-form__intro mb-4">
					<?= wp_kses_post( get_field( 'intro' ) ); ?>
				</div>
			</div>
			<div class="col-md-6">
				<?= do_shortcode( get_field( 'contact_form_shortcode' ) ); ?>
			</div>
		</div>
	</div>
</section>
<div class="cb-contact-form-spacer"></div>
<script>
document.addEventListener( 'DOMContentLoaded', function() {
	// Hide Gravity Forms required field asterisk
	document.querySelectorAll('.gform_wrapper.gravity-theme .gfield_required').forEach(function(el) {
		el.style.display = 'none';
	});
	// Add id-button class to Gravity Forms submit button
	document.querySelectorAll('.gform_button.button').forEach(function(btn) {
		btn.classList.add('id-button');
	});
} );
</script>