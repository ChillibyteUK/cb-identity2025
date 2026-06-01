<?php
/**
 * CB FAQ Block Template
 *
 * Multiple instances of this block on the same page are supported. Each
 * instance registers its Q&A pairs via cb_faq_add_schema_items(); a single
 * wp_footer hook outputs one FAQPage JSON-LD block covering all instances,
 * satisfying Google's one-FAQPage-per-page requirement.
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// cb_faq_add_schema_items() is defined once (function_exists guard prevents
// fatal errors when multiple instances of this block appear on the same page).
if ( ! function_exists( 'cb_faq_add_schema_items' ) ) {
	/**
	 * Collect FAQ items and output a single FAQPage schema in wp_footer.
	 *
	 * @param array $items Array of items with 'question' and 'answer' keys.
	 * @return void
	 */
	function cb_faq_add_schema_items( array $items ) {
		static $all_items = array();
		static $hooked    = false;

		foreach ( $items as $item ) {
			$all_items[] = $item;
		}

		if ( ! $hooked ) {
			$hooked = true;
			add_action(
				'wp_footer',
				function () use ( &$all_items ) {
					if ( empty( $all_items ) ) {
						return;
					}

					$entities = array_map(
						function ( $item ) {
							return array(
								'@type'          => 'Question',
								'name'           => $item['question'],
								'acceptedAnswer' => array(
									'@type' => 'Answer',
									'text'  => $item['answer'],
								),
							);
						},
						$all_items
					);

					$schema = array(
						'@context'   => 'https://schema.org',
						'@type'      => 'FAQPage',
						'mainEntity' => $entities,
					);

					echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
				}
			);
		}
	}
}

// Block ID.
$block_id = $block['id'] ?? '';

// Collect this block's Q&A pairs for schema.
$block_faq_items = array();
if ( have_rows( 'faqs' ) ) {
	while ( have_rows( 'faqs' ) ) {
		the_row();
		$block_faq_items[] = array(
			'question' => wp_strip_all_tags( get_sub_field( 'question' ) ),
			'answer'   => wp_strip_all_tags( get_sub_field( 'answer' ) ),
		);
	}
}

cb_faq_add_schema_items( $block_faq_items );

if ( $block['anchor'] ?? '' ) {
	?>
<a id="<?= esc_attr( $block['anchor'] ); ?>" class="anchor"></a>
	<?php
}
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cb-faq">
	<div class="id-container px-4 px-md-5">
		<?php
		if ( have_rows( 'faqs' ) ) :
			$c = 0;
			while ( have_rows( 'faqs' ) ) :
				the_row();
				$question = get_sub_field( 'question' );
				$answer   = get_sub_field( 'answer' );
				?>
			<div class="cb-faq__item row" data-aos="fade-up" data-aos-delay="<?= esc_attr( $c ); ?>">
				<div class="col-md-6">
					<p class="cb-faq__question"><?= esc_html( $question ); ?></p>
				</div>
				<div class="col-md-1"></div>
				<div class="col-md-5">
					<div class="cb-faq__answer">
						<?= wp_kses_post( $answer ); ?>
					</div>
				</div>
			</div>
				<?php
				$c += 100;
			endwhile;
		endif;
		?>
	</div>
</section>
