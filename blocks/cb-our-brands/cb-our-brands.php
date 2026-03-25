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
<a id="brands" class="anchor"></a>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-our-brands">
	<?php
	if ( get_field( 'pre_title' ) ) {
		?>
	<div class="cb-our-brands__pre-title">
		<div class="id-container px-4 px-md-5">
			<?= esc_html( get_field( 'pre_title' ) ); ?>
		</div>
	</div>
		<?php
	}
	?>
    <div class="cb-our-brands__intro mb-5">
        <div class="id-container px-4 px-md-5">
            <div class="row">
                <div class="col-md-9">
                    <?= wp_kses_post( get_field( 'intro_text' ) ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="cb-our-brands__brands id-container px-4 px-md-5 mb-5">
        <div class="row g-5">
            <?php
            $brands_raw    = get_field( 'brands' ) ?: array();
            $display_count = 0;
            foreach ( $brands_raw as $b ) {
                if ( ! empty( $b['brand_logo'] ) && ! empty( $b['link'] ) ) {
                    ++$display_count;
                }
            }

            // Calculate last-card column widths to fill the remaining row space.
            $xxl_rem     = $display_count % 4;
            $xl_rem      = $display_count % 3;
            $md_rem      = $display_count % 2;
            $last_col_xxl = $xxl_rem === 0 ? 12 : ( 4 - $xxl_rem ) * 3;
            $last_col_xl  = $xl_rem  === 0 ? 12 : ( 3 - $xl_rem )  * 4;
            $last_col_md  = $md_rem  === 0 ? 12 : 6;

            if ( have_rows( 'brands' ) ) {
                while ( have_rows( 'brands' ) ) {
                    the_row();
                    $brand_logo = get_sub_field( 'brand_logo' ) ?? '';
                    $brand_name = get_sub_field( 'brand_name' );
                    $brand_link = get_sub_field( 'link' );

                    if ( ! $brand_logo || ! $brand_link ) {
                        continue;
                    }

					?>
            <div class="col-md-6 col-xl-4 col-xxl-3">
					<?php
					if ( '#' !== $brand_link['url'] && '' !== $brand_link['url'] ) {
                    	?>
				<a href="<?= esc_url( $brand_link['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="brand-card">
					<div class="brand-card__front">
                        <?=
						wp_get_attachment_image(
							$brand_logo,
							'full',
							false,
							array(
								'class' => 'brand-card__logo',
								'alt'   => esc_attr( $brand_name ),
							)
						);
						?>
                    </div>
                    <div class="brand-card__back">
                        <div class="brand-card__name">
                            <?= esc_html( $brand_name ); ?>
                        </div>
                        <div class="brand-card__strap">
                            <?= esc_html( $brand_link['title'] ); ?> <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=23 height=21 alt="" class="" />
                        </div>
                    </div>
				</a>
						<?php
					} else {
						?>
				<div class="brand-card">
					<div class="brand-card__front">
                        <?=
						wp_get_attachment_image(
							$brand_logo,
							'full',
							false,
							array(
								'class' => 'brand-card__logo',
								'alt'   => esc_attr( $brand_name ),
							)
						);
						?>
                    </div>
                    <div class="brand-card__back">
                        <div class="brand-card__name">
                            <?= esc_html( $brand_name ); ?>
                        </div>
                        <div class="brand-card__strap">
                            <?= esc_html( $brand_link['title'] ); ?>
                        </div>
                    </div>
				</div>
						<?php
					}
					?>
					</div>
					<?php
                }
            }
            // Fill remaining space with the last card. Col widths are calculated above.
            ?>
            <div class="col-md-<?= esc_attr( $last_col_md ); ?> col-xl-<?= esc_attr( $last_col_xl ); ?> col-xxl-<?= esc_attr( $last_col_xxl ); ?>">
                <div class="brand-card__last">
                    Together, we deliver a breadth of expertise with the simplicity of one team.
                </div>
            </div>
        </div>
    </div>
</section>
