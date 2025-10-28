<?php
/**
 * CB Case Study Key Stats Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

// Setup ACF meta for block fields.
acf_setup_meta( $block['data'] );

?>
<style>
.case-study-key-stats {
    --_bg-url: url('<?= esc_url( get_stylesheet_directory_uri() . '/blocks/cb-work-index/bg.jpg' ); ?>');
}
</style>
<section id="<?= esc_attr( $block_id ); ?>" class="case-study-key-stats">
    <div class="case-study-key-stats__container pb-5">
		<div class="case-study-key-stats__header">
			<div class="id-container px-5">
				KEY STATS
			</div>
		</div>
        <?php
        if ( have_rows( 'stats' ) ) {
            $delay = 0;
            while ( have_rows( 'stats' ) ) {
                the_row();
                ?>
        <div class="case-study-key-stats__item">
			<div class="id-container px-5" data-aos="fade-up" data-aos-delay="<?= esc_attr( $delay ); ?>">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="case-study-key-stats__stat"><?= esc_html( get_sub_field( 'stat' ) ); ?></div>
                    </div>
                    <div class="col-lg-8">
                        <div class="case-study-key-stats__descriptor"><?= esc_html( get_sub_field( 'descriptor' ) ); ?></div>
                    </div>
                </div>
			</div>
        </div>
                <?php
                $delay += 50;
            }
        } else {
            ?>
        <div class="case-study-key-stats__item">NO STATS ADDED</div>
            <?php
        }
        ?>
    </div>
</section>