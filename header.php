<?php
/**
 * The header for the theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package cb-identity2025
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( session_status() === PHP_SESSION_NONE ) {
    session_start();
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta
        charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/SuisseIntl-Light.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/SuisseIntl-Regular.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/SuisseIntl-SemiBold.woff2' ); ?>"
        as="font" type="font/woff2" crossorigin="anonymous">
    <?php
    if ( ! is_user_logged_in() ) {
        if ( get_field( 'ga_property', 'options' ) ) {
            ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async
                src="<?= esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . get_field( 'ga_property', 'options' ) ); ?>">
            </script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config',
                    '<?= esc_js( get_field( 'ga_property', 'options' ) ); ?>'
                );
            </script>
        	<?php
        }
        if ( get_field( 'gtm_property', 'options' ) ) {
            ?>
            <!-- Google Tag Manager -->
            <script>
                (function(w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({
                        'gtm.start': new Date().getTime(),
                        event: 'gtm.js'
                    });
                    var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s),
                        dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer',
                    '<?= esc_js( get_field( 'gtm_property', 'options' ) ); ?>'
                );
            </script>
            <!-- End Google Tag Manager -->
    		<?php
        }
    }
	if ( get_field( 'google_site_verification', 'options' ) ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( get_field( 'google_site_verification', 'options' ) ) . '" />';
	}
	if ( get_field( 'bing_site_verification', 'options' ) ) {
		echo '<meta name="msvalidate.01" content="' . esc_attr( get_field( 'bing_site_verification', 'options' ) ) . '" />';
	}

	wp_head();
	?>
</head>

<body <?php body_class( is_front_page() ? 'homepage' : '' ); ?>
    <?php understrap_body_attributes(); ?>>
    <?php
	do_action( 'wp_body_open' );
	if ( ! is_user_logged_in() ) {
    	if ( get_field( 'gtm_property', 'options' ) ) {
        	?>
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe
                    src="<?= esc_url( 'https://www.googletagmanager.com/ns.html?id=' . get_field( 'gtm_property', 'options' ) ); ?>"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
    		<?php
    	}
	}
	?>
<header id="wrapper-navbar" class="fixed-top p-0">
	<nav class="navbar navbar-expand-lg">
		<div class="id-container d-flex px-5 gap-4">
            <div class="d-flex justify-content-between w-100 w-lg-auto align-items-center py-3 py-lg-0">
                <div class="logo-container"><a href="/" class="logo navbar-brand" aria-label="simco Homepage"></a></div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
				<!-- Navigation -->
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary_nav',
						'container'      => false,
						'menu_class'     => 'navbar-nav w-100 justify-content-end gap-4',
						'fallback_cb'    => '',
						'depth'          => 3,
						'walker'         => new Understrap_WP_Bootstrap_Navwalker(),
					)
				);
				?>
            </div>
		</div>
	</nav>
</header>