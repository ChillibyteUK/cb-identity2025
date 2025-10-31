<?php
/**
 * CB Full Video Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$vimeo_url = get_field( 'vimeo_url' );

if ( ! $vimeo_url ) {
    return;
}
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-full-video">
    <div class="id-container ratio ratio-16x9">
        <iframe class="full-video" src="<?= esc_url( $vimeo_url ); ?>" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
    </div>
</section>