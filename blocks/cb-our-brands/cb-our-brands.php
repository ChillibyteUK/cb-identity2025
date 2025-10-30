<?php
/**
 * CB Our Brands Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-our-brands">
	<?php
	if ( get_field( 'pre_title' ) ) {
		?>
	<div class="cb-our-brands__pre-title">
		<div class="id-container px-5">
			<?= esc_html( get_field( 'pre_title' ) ); ?>
		</div>
	</div>
		<?php
	}
	?>
    <div class="cb-our-brands__intro mb-5">
        <div class="id-container px-5">
            <div class="row">
                <div class="col-md-9">
                    <?= wp_kses_post( get_field( 'intro_text' ) ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="cb-our-brands__brands id-container px-5 mb-5">
        <div class="row g-4">
            <?php
            if ( have_rows( 'brands' ) ) {
                while ( have_rows( 'brands' ) ) {
                    the_row();
                    $brand_logo = get_sub_field( 'brand_logo' );
                    $brand_name = get_sub_field( 'brand_name' );
                    $brand_link  = get_sub_field( 'link' );
                    ?>
            <div class="col-lg-6 col-xl-3">
                <a href="<?= esc_url( $brand_link['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="brand-card">
                    <div class="brand-card__front">
                        <?= wp_get_attachment_image( $brand_logo, 'full', false, array( 'class' => 'brand-card__logo', 'alt' => esc_attr( $brand_name ) ) ); ?>
                    </div>
                    <div class="brand-card__back">
                        <div class="brand-card__name">
                            <?= esc_html( $brand_name ); ?>
                        </div>
                        <div class="brand-card__strap">
                            <?= esc_html( $brand_link['title'] ); ?> <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=23 height=21 alt="" class="cb-services-nav__item-icon" />
                        </div>
                    </div>
                </a>
            </div>
                    <?php
                }
            }
            ?>
            <div class="col-md-6">
                <div class="brand-card__last">
                    Together, we deliver a breadth of expertise with the simplicity of one team.
                </div>
            </div>
        </div>
    </div>
</section>
