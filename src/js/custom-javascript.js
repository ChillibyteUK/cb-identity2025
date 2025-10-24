// Add your custom JS here.
AOS.init({
  easing: 'ease-out',
  once: true,
  duration: 600,
});

// Add background to navbar on scroll
(function () {
  var navbar = document.getElementById("wrapper-navbar");

  var addNavbarBackground = function () {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };

  window.addEventListener("scroll", addNavbarBackground);
})();

// header logo clip animation
(function () {
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        // Don't animate — ensure fully visible
        const _clip_rm = document.getElementById('site-logo-clip');
        if (_clip_rm && _clip_rm.style && typeof _clip_rm.style.setProperty === 'function') {
            _clip_rm.style.setProperty('--logo-final-offset', '0px');
        } else {
            document.documentElement.style.setProperty('--logo-final-offset', '0px');
        }
        _clip_rm?.classList.add('animate');
        return;
    }

    // compute and apply pixel offset based on the bars group's bbox.
    function computeOffset() {
        const clip = document.getElementById('site-logo-clip');
        const svg = clip && clip.querySelector ? clip.querySelector('svg') : null;
        if (!clip || !svg) return;

        const leftGroup = svg.getElementById ? svg.getElementById('logo-bars') : svg.querySelector('#logo-bars');
        // fallback: try the first nested <g> if no id present
        const group = leftGroup || svg.querySelector('g > g');

        let viewBoxWidth = 0;
        if (svg.viewBox && svg.viewBox.baseVal && svg.viewBox.baseVal.width) {
            viewBoxWidth = svg.viewBox.baseVal.width;
        } else {
            const vb = svg.getAttribute('viewBox');
            if (vb) viewBoxWidth = parseFloat(vb.split(' ')[2]) || 0;
        }

            if (!viewBoxWidth || !group || typeof group.getBBox !== 'function') {
                const _clip = document.getElementById('site-logo-clip');
                if (_clip && _clip.style && typeof _clip.style.setProperty === 'function') {
                    _clip.style.setProperty('--logo-final-offset', '0px');
                } else {
                    document.documentElement.style.setProperty('--logo-final-offset', '0px');
                }
                return;
            }

        const bbox = group.getBBox();
        const visibleSvgUnits = bbox.x + bbox.width;
        const svgRect = svg.getBoundingClientRect();
        const svgDisplayWidth = svgRect.width || 0;
        const offsetPx = (visibleSvgUnits / viewBoxWidth) * svgDisplayWidth;
        const safeOffset = Math.ceil(offsetPx + 1);
            const _clip = document.getElementById('site-logo-clip');
            if (_clip && _clip.style && typeof _clip.style.setProperty === 'function') {
                _clip.style.setProperty('--logo-final-offset', `-${safeOffset}px`);
            } else {
                document.documentElement.style.setProperty('--logo-final-offset', `-${safeOffset}px`);
            }
    }

    function initLogoClip() {
        const clip = document.getElementById('site-logo-clip');
        if (!clip) return;
        computeOffset();
        // trigger paint then animate back to 0 (reveal the bars)
        requestAnimationFrame(() => {
            setTimeout(() => clip.classList.add('animate'), 60);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLogoClip);
    } else {
        initLogoClip();
    }

    // Single debounced resize listener (does not reattach on each run)
    let rTO = null;
    window.addEventListener('resize', function () {
        clearTimeout(rTO);
        rTO = setTimeout(function () {
            const clip = document.getElementById('site-logo-clip');
            if (!clip) return;
            clip.classList.remove('animate');
            // recompute and retrigger
            computeOffset();
            void clip.offsetWidth;
            requestAnimationFrame(() => setTimeout(() => clip.classList.add('animate'), 60));
        }, 150);
    });
})();

// footer logo animation
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

// (function() {
//   // Hide header on scroll
//   var doc = document.documentElement;
//   var w = window;

//   var prevScroll = w.scrollY || doc.scrollTop;
//   var curScroll;
//   var direction = 0;
//   var prevDirection = 0;

//   var header = document.getElementById('wrapper-navbar');

//   var checkScroll = function() {
//       // Find the direction of scroll (0 - initial, 1 - up, 2 - down)
//       curScroll = w.scrollY || doc.scrollTop;
//       if (curScroll > prevScroll) {
//           // Scrolled down
//           direction = 2;
//       } else if (curScroll < prevScroll) {
//           // Scrolled up
//           direction = 1;
//       }

//       if (direction !== prevDirection) {
//           toggleHeader(direction, curScroll);
//       }

//       prevScroll = curScroll;
//   };

//   var toggleHeader = function(direction, curScroll) {
//       if (direction === 2 && curScroll > 125) {
//           // Replace 52 with the height of your header in px
//           if (!document.getElementById('navbar').classList.contains('show')) {
//               header.classList.add('hide');
//               prevDirection = direction;
//           }
//       } else if (direction === 1) {
//           header.classList.remove('hide');
//           prevDirection = direction;
//       }
//   };

//   window.addEventListener('scroll', checkScroll);
// }
// )();


/*

  // Header background
  document.addEventListener('scroll', function() {
      var nav = document.getElementById('navbar');
    //   var primaryNav = document.getElementById('primaryNav');
    //   if (!primaryNav.classList.contains('show')) {
    //       nav.classList.toggle('scrolled', window.scrollY > nav.offsetHeight);
    //   }
      document.querySelectorAll('.dropdown-menu').forEach(function(dropdown) {
          dropdown.classList.remove('show');
      });
      document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
          toggle.classList.remove('show');
          toggle.blur();
      });
  });

*/