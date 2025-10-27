<?php
/**
 * CB Case Study Hero Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="case-study-hero has-primary-black-background-color pt-5">
    <h1 class="mt-5">
        <div class="id-container px-5 pt-2">
            <?= get_the_title(); ?>
        </div>
    </h1>
    <h2>
        <div class="id-container px-5 pt-2">
            <?= get_field( 'case_study_subtitle' ); ?>
        </div>
    </h2>
    <?php
    $video = get_field( 'vimeo_url', get_the_ID() );
    if ( $video ) {
        ?>
    <div class="id-container case-study-hero-video-container">
        <div class="overlay"></div>
        <iframe class="case-study-hero-video" src="<?= esc_url( $video ); ?>&&autoplay=1" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
    </div>
        <?php
    }
    ?>
</section>
