<?php
/**
 * CB Styled Text Image Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$section_title = get_field( 'section_title' );
$title_colour  = get_field( 'title_colour' );
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-styled-text-image">
    <div class="cb-styled-text-image__pre-title <?= esc_attr( $title_colour ); ?>">
		<div class="id-container pt-2 pb-1 px-5">
			<?= esc_html( $section_title ); ?>
		</div>
	</div>
    <div class="id-container ps-5">
        <div class="row">
            <div class="col-md-6 py-5 cb-styled-text-image__text-content">
                <?= wp_kses_post( get_field( 'text_content' ) ); ?>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <div class="cb-styled-text-image__image-wrapper">
                    <?= wp_get_attachment_image( get_field( 'image' ), 'full', false, array( 'class' => 'cb-styled-text-image__image' ) ); ?>
                </div>
            </div>
        </div>
    </div>
</section>
