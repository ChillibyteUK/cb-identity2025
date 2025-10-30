<?php
/**
 * CB About Detail Block Template
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
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-about-detail">
    <?php
    if ( get_field( 'pre_title' ) ) {
        ?>
    <div class="cb-related-work__pre-title">
        <div class="id-container pt-4 pb-3 px-5">
			<?= esc_html( $pretitle ); ?>
		</div>
	</div>
        <?php
    }
    ?>
	<div class="id-container px-5">
		<?php
		if ( have_rows( 'details' ) ) {
			$c = 0;
			while ( have_rows( 'details' ) ) {
				the_row();
				$item_title  = get_sub_field( 'title' );
				$description = get_sub_field( 'description' );
                $colour = 0 === $c ? 'has-purple-600-color' : '';
                $title_size = 0 === $c ? 'has-800-font-size' : '';
				?>
			<div class="row about-detail-row pb-5 <?= esc_attr( $colour ); ?>" data-aos="fade-up" data-aos-delay="<?= esc_attr( $c ); ?>">
				<div class="col-md-6">
					<h2 class="about-detail-title <?= esc_attr( $title_size ); ?>"><?= wp_kses_post( $item_title ); ?></h2>
				</div>
				<div class="col-md-1"></div>
				<div class="col-md-5">
					<div class="about-detail-description">
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
