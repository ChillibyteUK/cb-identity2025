<?php
/**
 * CB Testimonial Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$background = get_field( 'style' );

$quote   = get_field( 'quote' );
$author  = get_field( 'author' );
$company = get_field( 'company' );

if ( ! $quote ) {
	return;
}
?>
<section id="<?= esc_attr( $block_id ); ?>" class="cb-testimonial <?= esc_attr( $background ); ?>">
    <div class="id-container p-5" data-aos="fade-up">
            <div class="cb-testimonial__quote">
                “<?= wp_kses_post( $quote ); ?>”
            </div>
            <div class="cb-testimonial__author">
                <?= esc_html( $author ); ?>
            </div>
			<?php
			if ( $company ) {
				?>
			<br>
            <div class="cb-testimonial__company">
                <?= esc_html( $company ); ?>
            </div>
				<?php
			}
			?>
        </div>
    </div>
</section>
