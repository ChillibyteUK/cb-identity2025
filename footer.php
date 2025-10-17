<?php
/**
 * Footer template for the Identity Group 2025 theme.
 *
 * This file contains the footer section of the theme, including navigation menus,
 * office addresses, and colophon information.
 *
 * @package cb-identity2025
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="footer-top"></div>

<footer class="footer pt-5 pb-4">
    <div class="id-container px-5">
        <div class="row pb-4 g-4">
			<div class="col-md-4">
				<div class="footer-title--lg mb-3">Connect. Share. Follow.</div>
				<?= do_shortcode( '[social_icons class="fa-2x"]' ); ?>
			</div>
			<div class="col-md-2">
				<div class="footer-title mb-4">Services</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_services',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>

            <div class="col-md-2">
				<div class="footer-title mb-4">About</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_about',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
            <div class="col-md-2">
				<div class="footer-title mb-4">Identity</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_identity',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
            <div class="col-md-2">
				<div class="footer-title mb-4">Legal &amp; info</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_legal',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>

			<!-- ROW 2 -->
			<div class="col-md-4">
				<strong>
				<div class="mb-5">Let's talk, ask us anything</div>
				<?= do_shortcode( '[contact_email]' ); ?>
				</strong>
			</div>
			<div class="col-md-2">
				<div class="footer-title mb-5">Work</div>
				<div class="footer-title mb-4">Innovation Lab</div>
            </div>
			<div class="col-md-2">
				<div class="footer-title mb-4">Media</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_media',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
			<div class="col-md-2">
				<div class="footer-title mb-4">Global</div>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_global',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
		</div>
	</div>
	<div class="footer__logo">
		<div class="id-container p-5">
			<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/identity-logo.svg' ); ?>" alt="Identity Group Logo">
		</div>
	</div>
	<div class="id-container px-5 pt-4 footer__colophon">
		Identity Events Management Ltd, Registered Number - 04217845 | VAT Number - GB 813 0913 60
	</div>
</footer>
<?php wp_footer(); ?>
</body>

</html>