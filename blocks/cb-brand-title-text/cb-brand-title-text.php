<?php
/**
 * CB Brand Title Text Block Template
 *
 * @package cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

// Block classes.
$block_classes = array( 'block', 'cb-brand-title-text' );
if ( ! empty( $block['className'] ) ) {
    $block_classes[] = $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $block_classes[] = 'align' . $block['align'];
}

$l = get_field( 'link' );

?>
<section id="<?= esc_attr( $block_id ); ?>" class="<?= esc_attr( implode( ' ', $block_classes ) ); ?>">
	<div class="cb-brand-title-text__pre-title">
		<div class="id-container px-5">
			<?= esc_html( get_field( 'pre_title' ) ); ?>
		</div>
	</div>
	<div class="id-container p-5">
		<div class="cb-brand-title-text__container">
			<div class="row">
				<div class="col-md-7 mb-5 d-flex justify-content-center align-items-center">
					<div class="cb-brand-title-text__title">
					<?php
					$section_title = get_field( 'title' );

					$lines = preg_split( '/<br\s*\/?>/i', $section_title );

					$c = 1;

					$wrapped = array_map(
						function ( $line ) use ( &$c ) {
							$output = '<div class="line"><div class="bar bar' . $c . '"></div><div class="text text' . $c . '">' . trim( $line ) . '</div></div>';
							$c++;
							return $output;
						},
						$lines
					);

					echo wp_kses_post( implode( '', $wrapped ) );
					?>
				</div>
			</div>
		</div>
		<div class="cb-brand-title-text__content-wrapper pb-5">
			<div class="row">
				<div class="col-md-6 offset-md-6">
					<div class="cb-brand-title-text__content-heading mb-4">
						<?= wp_kses_post( get_field( 'content_heading' ) ); ?>
					</div>
					<div class="cb-brand-title-text__content">
						<?= wp_kses_post( get_field( 'content' ) ); ?>
					</div>
					<div class="cb-brand-title-text__link mt-4">
						<a href="<?= esc_url( $l['url'] ); ?>" target="<?= esc_attr( $l['target'] ); ?>" class="id-button">
							<?= esc_html( $l['title'] ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
	gsap.registerPlugin(ScrollTrigger);

	const tl = gsap.timeline({
		defaults: { ease: 'power3.out' },
		scrollTrigger: {
		trigger: '.cb-brand-title-text',
		start: 'top center',     // when the top of the section hits middle of viewport
		toggleActions: 'play none none none',
		once: true               // run once only
		}
	});

	tl.fromTo(".cb-brand-title-text__title .bar1", {x: "-150%", opacity: 0}, {x: 0, opacity: 1, duration: 0.8}, 0)
	.fromTo(".cb-brand-title-text__title .bar2", {x: "150%", opacity: 0}, {x: 0, opacity: 1, duration: 0.8}, 0.3)
	.fromTo(".cb-brand-title-text__title .bar3", {x: "-150%", opacity: 0}, {x: 0, opacity: 1, duration: 0.8}, 0.6)
	.to(".cb-brand-title-text__title .bar1", {rotate: -3, duration: 0.4}, "+=0.1")
	.to(".cb-brand-title-text__title .bar2", {rotate: 5, duration: 0.4}, "-=0.3")
	.to(".cb-brand-title-text__title .bar3", {rotate: -6, duration: 0.4}, "-=0.3")
	.to(".cb-brand-title-text__title .text", {opacity: 1, duration: 0.6, stagger: 0.2}, "+=0.3");

});
</script>
		<?php
	}
);