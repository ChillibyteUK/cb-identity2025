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
				<div class="mb-5">Let's talk.</div>
				<?= do_shortcode( '[contact_email]' ); ?>
				</strong>
			</div>
			<div class="col-md-2">
				<div class="footer-title mb-5"><a href="/work/">Work</a></div>
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
			<div id="footer-logo-clip" class="footer__logo-clip">
				<div id="footer-logo-inner" class="footer__logo-inner">
					<!-- Inline logo SVG (long form) so we can animate precisely -->
					<svg id="footer-logo-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1352.05 81.84" aria-hidden="true" focusable="false">
					  <defs>
					    <style>
					      .cls-1{fill:#fff}
					    </style>
					  </defs>
					  <g>
					    <g>
					      <polygon class="cls-1" points="333.17 0 0 0 0 14.97 333.17 14.97 666.3 14.97 666.3 0 333.17 0"/>
					      <polygon class="cls-1" points="0 33.42 0 48.4 333.17 48.4 666.3 48.4 666.3 33.42 333.17 33.42 0 33.42"/>
					      <polygon class="cls-1" points="0 66.84 0 81.82 333.17 81.82 666.3 81.82 666.3 66.84 333.17 66.84 0 66.84"/>
					    </g>
					    <g>
					      <polygon class="cls-1" points="996.41 56.99 996.41 0 1013.97 0 1013.97 81.83 998.42 81.83 937.88 25.21 937.88 81.83 920.32 81.83 920.32 0 935.86 0 996.41 56.99"/>
					      <path class="cls-1" d="M1271.38,0c10.14,13.09,29.99,40.55,29.99,40.55L1331.01,0h21.04l-42.07,54.98v26.85h-17.56v-26.48L1249.98.02h21.4Z"/>
					      <polygon class="cls-1" points="1244.59 0 1244.59 14.99 1208.73 14.99 1208.73 81.83 1191.17 81.83 1191.17 14.99 1155.32 14.99 1155.32 0 1244.59 0"/>
					      <polygon class="cls-1" points="1114.74 0 1114.74 14.99 1078.89 14.98 1078.89 81.83 1061.33 81.83 1061.33 14.98 1025.48 14.98 1025.48 0 1114.74 0"/>
					      <rect class="cls-1" x="685.75" y="0" width="17.93" height="81.82"/>
					      <polygon class="cls-1" points="904.6 14.98 904.6 0 837.45 0 822.47 0 822.47 81.83 837.45 81.83 904.6 81.83 904.6 66.86 837.45 66.85 837.45 48.41 904.6 48.41 904.6 33.43 837.45 33.43 837.45 14.98 904.6 14.98"/>
					      <path class="cls-1" d="M798.61,10.7C791.15,3.61,780.27,0,766.29,0h-43.17v81.82h43.17c28.02,0,43.46-14.55,43.46-40.97,0-12.99-3.75-23.14-11.14-30.16h0ZM740.55,15.05h24.61c8.85,0,15.58,2.08,20,6.19,4.57,4.24,6.88,10.84,6.88,19.62,0,17.44-8.8,25.92-26.89,25.92h-24.61V15.04h0Z"/>
					      <rect class="cls-1" x="1126.25" y="0" width="17.56" height="81.82"/>
					    </g>
					  </g>
					</svg>
				</div>
			</div>
		</div>
	</div>
	<div class="id-container px-5 pt-4 footer__colophon">
		Identity Events Management Ltd, Registered Number - 04217845 | VAT Number - GB 813 0913 60
	</div>
</footer>
<script>
(function(){
	const clip = document.getElementById('footer-logo-clip');
	const inner = document.getElementById('footer-logo-inner');
	const svg = document.getElementById('footer-logo-svg');
	if (!clip || !inner || !svg) return;

	const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	let triggered = false;

	// Ensure initial visual state shows the three bars (left half) on load
	// Make clip fill container and inner be 200% width, sitting at translateX(0)
	clip.style.width = '100%';
	inner.style.width = '200%';
	inner.style.transform = 'translateX(0)';
	// disable any transition until animated explicitly
	inner.style.transition = 'none';

	function prepareAndAnimate() {
		// Make clip fill the container width
		clip.style.width = '100%';
		// Ensure inner uses left origin so translating reveals the right half
		inner.style.transformOrigin = 'left center';

		// Make inner element 200% width so its left-half fills the clip initially
		inner.style.width = '200%';
		inner.style.display = 'block';
		inner.style.transform = 'translateX(0)';

		// If user prefers reduced motion, jump to final state (fully revealed)
		if (prefersReduced) {
			inner.style.transform = 'translateX(-50%)';
			return;
		}

		// Animate to final state: move left by 50% of inner width (revealing right half)
		// Use a slightly slower duration and a smoother easing
		const animDuration = 1.6; // seconds
		const gsapEase = 'power3.out';
		if (window.gsap && typeof window.gsap.to === 'function') {
			window.gsap.to(inner, { xPercent: -50, duration: animDuration, ease: gsapEase });
		} else {
			// Fallback: animate via CSS transition with similar easing
			inner.style.transition = 'transform ' + animDuration + 's cubic-bezier(.22,.9,.32,1)';
			requestAnimationFrame(() => { inner.style.transform = 'translateX(-50%)'; });
		}
	}

	const observer = new IntersectionObserver((entries, obs) => {
		entries.forEach(entry => {
			if (triggered) return;
			if (entry.isIntersecting && entry.intersectionRatio >= 0.1) {
				triggered = true;
				prepareAndAnimate();
				obs.disconnect();
			}
		});
	}, { rootMargin: '0px 0px -10px 0px', threshold: [0] });

	// Prefer the colophon as the trigger element (fires when its bottom enters viewport)
	const triggerEl = document.querySelector('.footer__colophon') || document.querySelector('.footer__logo') || clip;
	if (triggerEl) observer.observe(triggerEl);

	// If window resizes before animation triggered, ensure inner stays 200% width
	let resizeTimer = null;
	window.addEventListener('resize', () => {
		if (triggered) return; // no need after animation
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(() => {
			clip.style.width = '100%';
			inner.style.width = '200%';
		}, 120);
	});
})();
</script>

<?php wp_footer(); ?>
</body>

</html>