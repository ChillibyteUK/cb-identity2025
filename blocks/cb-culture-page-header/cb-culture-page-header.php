<?php
/**
 * CB Culture Page Header Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$bg = get_query_var( 'background', get_field( 'background' ) );

?>
<style>
.cb-culture-page-header {
	--_bg-url: url('<?= esc_url( wp_get_attachment_image_url( $bg, 'full' ) ); ?>');
}
</style>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-culture-page-header">
    <div class="cb-culture-page-header__top">
        <div class="intro-overlay"></div>
        <div class="id-container px-5">
            <h1><?= wp_kses_post( get_field( 'title' ) ); ?></h1>
            <div class="row">
                <div class="col-md-9">
                    <div class="cb-culture-page-header__intro-text"><?= wp_kses_post( get_field( 'intro_text' ) ); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="cb-culture-page-header__content-wrapper">
        <div class="culture-overlay"></div>
        <div class="id-container px-5">
            <div class="row">
                <div class="col-md-9">
                    <div class="cb-culture-page-header__intro">
                        <?= wp_kses_post( get_field( 'secondary_text' ) ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
