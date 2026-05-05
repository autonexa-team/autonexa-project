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