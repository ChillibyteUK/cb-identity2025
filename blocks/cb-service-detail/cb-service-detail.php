<?php
/**
 * CB Service Detail Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-service-detail">
	<div class="id-container px-4 px-md-5">
		<?php
		if ( have_rows( 'details' ) ) {
			$c = 0;
			while ( have_rows( 'details' ) ) {
				the_row();
				$item_title  = get_sub_field( 'title' );
				$description = get_sub_field( 'description' );
				?>
			<div class="row service-detail-row" data-aos="fade-up" data-aos-delay="<?= esc_attr( $c ); ?>">
				<div class="col-md-6">
					<h2 class="service-detail-title"><?= esc_html( $item_title ); ?></h2>
				</div>
				<div class="col-md-1"></div>
				<div class="col-md-5">
					<div class="service-detail-description">
						<?= wp_kses_post( $description ); ?>
					</div>
				</div>
			</div>
				<?php
				$c += 100;
			}
		}
		?>
	</div>
</section>