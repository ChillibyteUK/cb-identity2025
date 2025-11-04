<?php
/**
 * CB Services Nav Block Template
 *
 * @package cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

$c = is_front_page() ? 'cb-services-nav--front' : '';
?>
<div id="<?php echo esc_attr( $block_id ); ?>" class="cb-services-nav <?= esc_attr( $c ); ?>">
    <div class="cb-services-nav__container pb-5">
		<div class="cb-services-nav__header">
			<div class="id-container px-4 px-md-5">
				SERVICES
			</div>
		</div>
        <?php
        $services = get_page_by_path( 'services' );
		if ( $services ) {
			// get child pages.
			$child_pages = get_pages(
				array(
					'child_of'    => $services->ID,
					'sort_column' => 'menu_order',
					'sort_order'  => 'ASC',
				)
			);
			if ( $child_pages ) {
				foreach ( $child_pages as $service_page ) {
					if ( get_the_ID() === $service_page->ID ) {
						continue;
					}
					?>
		<a href="<?php echo esc_url( get_permalink( $service_page->ID ) ); ?>" class="cb-services-nav__item" tabindex="0">
			<div class="id-container px-4 px-md-5 d-flex justify-content-between" data-aos="fade-up" data-aos-delay="<?= esc_attr( 50 * ( array_search( $service_page, $child_pages, true ) + 1 ) ); ?>">
				<div class="cb-services-nav__item-title"><?php echo esc_html( get_the_title( $service_page->ID ) ); ?></div>
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/arrow-wh.svg' ); ?>" width=65 height=60 alt="" class="cb-services-nav__item-icon" />
			</div>
		</a>
					<?php
				}
			}
		}
		?>
    </div>
</div>
