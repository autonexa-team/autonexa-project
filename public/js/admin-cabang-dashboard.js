/**
 * dashboard.js
 * Animated number counter — easeOutExpo easing.
 *
 * Cara pakai: tambahkan class "counter-value" pada elemen angka,
 * lalu set atribut data berikut sesuai kebutuhan:
 *
 *   data-target   {number}  angka tujuan            (wajib)
 *   data-decimals {number}  jumlah desimal, default 0
 *   data-prefix   {string}  teks sebelum angka, misal "Rp "
 *   data-suffix   {string}  teks setelah angka, misal "M"
 */

(() => {
    'use strict';

    const DURATION = 1500; // ms

    /** Easing: easeOutExpo */
    function easeOutExpo(t) {
        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
    }

    /** Animate a single counter element */
    function animateCounter(el) {
        const target   = parseFloat(el.dataset.target ?? '0');
        const decimals = parseInt(el.dataset.decimals ?? '0', 10);
        const prefix   = el.dataset.prefix ?? '';
        const suffix   = el.dataset.suffix ?? '';

        let startTs = null;

        function tick(ts) {
            if (!startTs) startTs = ts;

            const elapsed  = ts - startTs;
            const progress = Math.min(elapsed / DURATION, 1);
            const value    = easeOutExpo(progress) * target;

            el.textContent = prefix + value.toFixed(decimals) + suffix;

            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                // Snap to exact final value
                el.textContent = prefix + target.toFixed(decimals) + suffix;
            }
        }

        requestAnimationFrame(tick);
    }

    /** Boot all counters once DOM is ready */
    function init() {
        document.querySelectorAll('.counter-value').forEach(animateCounter);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init(); // DOM already parsed
    }
})();