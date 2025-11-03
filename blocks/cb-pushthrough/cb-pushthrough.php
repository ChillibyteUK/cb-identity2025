<?php
/**
 * CB Pushthrough Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$l = get_field( 'link' );

$background  = get_field( 'background' );
$link_colour = $background ? 'has-green-400-color' : '';
$arrow = $background ? 'arrow-g400.svg' : 'arrow-wh.svg';
if ( $background ) {
	?>
<style>
.cb-pushthrough {
	--_bg-url: url('<?= esc_url( wp_get_attachment_image_url( $background, 'full' ) ); ?>');
}
</style>
	<?php
}
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-pushthrough <?php echo $background ? 'has-bg' : ''; ?>">
	<?php
	if ( get_field( 'pretitle' ) ) {
		?>
	<div class="cb-pushthrough__pretitle">
		<div class="id-container px-5">
			<?= esc_html( get_field( 'pretitle' ) ); ?>
		</div>
	</div>
		<?php
	}
	?>
	<?php
	if ( $background ) {
		$overlay = get_field( 'left_content' ) ? 'overlay--white' : '';
		?>
	<div class="overlay <?= esc_attr( $overlay ); ?>"></div>
		<?php
	}

	$desc_class = '';
	?>
	<div class="id-container px-5">
		<div class="row py-4">
			<div class="col-md-6">
				<h2><?= esc_html( get_field( 'title' ) ); ?></h2>
				<?php
				if ( get_field( 'left_content' ) ) {
					?>
				<div class="cb-pushthrough__left-content mb-3">
					<?= wp_kses_post( get_field( 'left_content' ) ); ?>
				</div>
					<?php
					$desc_class = 'larger-left';
				}
				?>
			</div>
			<div class="col-md-6">
				<div class="cb-pushthrough__desc <?= esc_attr( $desc_class ); ?>">
					<?= wp_kses_post( get_field( 'description' ) ); ?>
				</div>
				<?php
				if ( $l && isset( $l['url'], $l['title'] ) ) {
					?>
				<a href="<?= esc_url( $l['url'] ); ?>" class="cb-pushthrough__link <?= esc_attr( $link_colour ); ?>">
					<?= esc_html( $l['title'] ); ?>&nbsp;
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/' . esc_attr( $arrow ) ); ?>" width=33 height=26 alt="" />
				</a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
