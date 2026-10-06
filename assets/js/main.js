document.addEventListener("DOMContentLoaded", () => {
    const autoScroll = document.querySelector(".auto-scroll");
    if (autoScroll) {
        let scrollAmount = 0;
        setInterval(() => {
            scrollAmount += 1;
            autoScroll.scrollLeft = scrollAmount;
            if (scrollAmount >= autoScroll.scrollWidth - autoScroll.clientWidth) {
                scrollAmount = 0;
            }
        }, 30);
    }
});
