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

?>
<section id="<?= esc_attr( $block_id ); ?>" class="cb-testimonial <?= esc_attr( $background ); ?>">
    <div class="id-container p-5" data-aos="fade-up">
            <div class="cb-testimonial__quote">
                “<?= wp_kses_post( get_field( 'quote' ) ); ?>”
            </div>
            <div class="cb-testimonial__author">
                <?= esc_html( get_field( 'author' ) ); ?>
            </div><br>
            <div class="cb-testimonial__company">
                <?= esc_html( get_field( 'company' ) ); ?>
            </div>
        </div>
    </div>
</section>
