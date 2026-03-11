/* ===========================
   SCROLL REVEAL ANIMATIONS
   Native Intersection Observer
   =========================== */

(function () {
    'use strict';

    const REVEAL_CLASSES = [
        'scroll-reveal',
        'scroll-reveal-left',
        'scroll-reveal-right',
        'scroll-reveal-scale'
    ];

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -60px 0px',
        threshold: 0.005
    };

    function handleIntersect(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                // Once revealed, stop observing
                observer.unobserve(entry.target);
            }
        });
    }

    function initScrollAnimations() {
        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            // Fallback: reveal everything immediately
            REVEAL_CLASSES.forEach(cls => {
                document.querySelectorAll('.' + cls).forEach(el => {
                    el.classList.add('revealed');
                });
            });
            return;
        }

        const observer = new IntersectionObserver(handleIntersect, observerOptions);

        REVEAL_CLASSES.forEach(cls => {
            document.querySelectorAll('.' + cls).forEach(el => {
                observer.observe(el);
            });
        });

        // Also observe staggered children
        document.querySelectorAll('.scroll-stagger').forEach(container => {
            Array.from(container.children).forEach(child => {
                // Add the base reveal class if not already present
                if (!child.classList.contains('scroll-reveal') &&
                    !child.classList.contains('scroll-reveal-left') &&
                    !child.classList.contains('scroll-reveal-right') &&
                    !child.classList.contains('scroll-reveal-scale')) {
                    child.classList.add('scroll-reveal');
                }
                observer.observe(child);
            });
        });

        // Store observer globally so new elements can be observed
        window._scrollObserver = observer;
    }

    // Utility: observe new dynamically added elements
    window.observeScrollReveal = function (element) {
        if (window._scrollObserver && element) {
            window._scrollObserver.observe(element);
        } else if (element) {
            element.classList.add('revealed');
        }
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollAnimations);
    } else {
        initScrollAnimations();
    }
})();
