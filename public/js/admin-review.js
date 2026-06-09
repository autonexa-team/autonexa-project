document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".count-up");

    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-target"));
        let count = 0;

        const speed = target / 40;

        const update = () => {
            count += speed;

            if (count < target) {
                counter.innerText = target % 1 !== 0
                    ? count.toFixed(1)
                    : Math.floor(count);
                requestAnimationFrame(update);
            } else {
                counter.innerText = target;
            }
        };

        update();
    });
});

/**
 * admin-review-detail.js
 * Letakkan di: public/js/admin-review-detail.js
 *
 * Fitur:
 * 1. Klik card review di halaman list → navigasi ke halaman detail
 * 2. Animasi progress bar bintang saat halaman detail dimuat
 * 3. Back button dengan history API
 */

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════════════════
    //  1. KLIK CARD REVIEW → NAVIGASI KE DETAIL
    //     (dijalankan di halaman LIST, bukan detail)
    // ═══════════════════════════════════════════════

    // const reviewCards = document.querySelectorAll('.review-card');

    // if (reviewCards.length > 0) {
    //     reviewCards.forEach(card => {
    //         // Cursor pointer agar terasa klikable
    //         card.style.cursor = 'pointer';

    //         // Tambah class transisi hover jika belum ada di CSS
    //         card.addEventListener('mouseenter', function () {
    //             this.style.transform    = 'translateY(-2px)';
    //             this.style.boxShadow    = '0 8px 24px rgba(0,0,0,0.09)';
    //             this.style.transition   = 'all 0.18s ease';
    //         });

    //         card.addEventListener('mouseleave', function () {
    //             this.style.transform = '';
    //             this.style.boxShadow = '';
    //         });

    //         card.addEventListener('click', function (e) {
    //             // Jangan navigate jika klik di tombol / link di dalam card
    //             if (e.target.closest('a, button')) return;

    //             const reviewId = this.dataset.reviewId;
    //             if (!reviewId) return;

    //             // Tambah efek ripple klik
    //             this.style.opacity = '0.7';

    //             // Navigasi ke halaman detail
    //             const baseUrl = document.getElementById('reviewBaseUrl')?.value
    //                          || '/admin-pusat/review';
    //             window.location.href = `${baseUrl}/${reviewId}`;
    //         });
    //     });
    // }

    // ═══════════════════════════════════════════════
    //  2. ANIMASI BAR BINTANG (halaman detail)
    // ═══════════════════════════════════════════════

    const barFills = document.querySelectorAll('.rd-star-bar-fill.bar-fill-active');

    if (barFills.length > 0) {
        // Reset dulu ke 0, lalu animate ke nilai target
        barFills.forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width   = '0%';

            // Delay sedikit agar animasi terlihat
            setTimeout(() => {
                bar.style.transition = 'width 0.7s cubic-bezier(.4,0,.2,1)';
                bar.style.width      = targetWidth;
            }, 200);
        });
    }

    // ═══════════════════════════════════════════════
    //  3. COUNT-UP ANGKA RATING (jika ada)
    // ═══════════════════════════════════════════════

    const ratingNum = document.querySelector('.rd-score-num');

    if (ratingNum) {
        const target   = parseFloat(ratingNum.textContent) || 0;
        const duration = 600; // ms
        const steps    = 30;
        const step      = target / steps;
        let   current  = 0;
        let   count    = 0;

        ratingNum.textContent = '0.0';

        const interval = setInterval(() => {
            count++;
            current += step;

            if (count >= steps) {
                ratingNum.textContent = target.toFixed(1);
                clearInterval(interval);
                return;
            }

            ratingNum.textContent = current.toFixed(1);
        }, duration / steps);
    }

    // ═══════════════════════════════════════════════
    //  4. HISTORY ITEM — hover indicator
    // ═══════════════════════════════════════════════

    document.querySelectorAll('.rd-history-item').forEach(item => {
        item.addEventListener('mouseenter', function () {
            this.style.paddingLeft = '22px';
            this.style.transition  = 'padding 0.15s ease';
        });

        item.addEventListener('mouseleave', function () {
            this.style.paddingLeft = '';
        });
    });

});