<?php
/**
 * Hover Video Card Partial
 * Usage: include this partial and pass $post_id, $thumb_size, $vimeo_field
 *
 * @package cb-identity2025
 *
 * @param int    $the_post     The post ID.
 * @param string $thumb_size   The thumbnail size (default 'full').
 * @param string $vimeo_field  The ACF field name for the Vimeo URL (default 'vimeo_video').
 */

if ( ! isset( $the_post ) ) {
    return;
}
$thumb_size  = isset( $thumb_size ) ? $thumb_size : 'full';
$vimeo_field = isset( $vimeo_field ) ? $vimeo_field : 'vimeo_url';
$thumb_html  = get_the_post_thumbnail( $the_post, $thumb_size, array( 'class' => 'hover-video-card__poster' ) );
$vimeo_url   = get_field( $vimeo_field, $the_post );
if ( ! $vimeo_url ) {
    echo wp_kses_post( $thumb_html );
    return;
}
?>
<div class="hover-video-card" data-vimeo-url="<?php echo esc_url( $vimeo_url ); ?>">
    <?php echo wp_kses_post( $thumb_html ); ?>
    <div class="hover-video-card__video"></div>
</div>
