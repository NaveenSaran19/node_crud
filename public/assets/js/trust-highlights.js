/**
 * Establishment and Trust Highlights Section JavaScript
 * Handles accessibility, smooth transitions, and interaction hooks
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const trustSection = document.querySelector('.trust-highlights-section');
        if (!trustSection) return;

        const cards = trustSection.querySelectorAll('.trust-card');
        if (!cards.length) return;

        // Optional intersection observer for smooth entry fade-in
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('trust-card-visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });

            cards.forEach(card => observer.observe(card));
        }
    });
})();
