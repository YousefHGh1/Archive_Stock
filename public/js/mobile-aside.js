(function () {
    var breakpoint = 992;
    var body = document.body;
    var aside = document.getElementById("kt_aside");
    var toggle = document.getElementById("mobile_aside_toggle");
    var overlay = document.getElementById("mobile_aside_overlay");

    if (!body || !aside || !toggle || !overlay) {
        return;
    }

    function isMobile() {
        return window.innerWidth < breakpoint;
    }

    function setOpen(open) {
        if (!isMobile()) {
            open = false;
        }

        body.classList.toggle("mobile-aside-open", open);
        toggle.setAttribute("aria-expanded", open ? "true" : "false");
        toggle.setAttribute(
            "aria-label",
            open ? "Close sidebar" : "Open sidebar",
        );
        overlay.hidden = !open;
    }

    toggle.addEventListener("click", function () {
        setOpen(!body.classList.contains("mobile-aside-open"));
    });

    overlay.addEventListener("click", function () {
        setOpen(false);
    });

    // Disabled aside click listener to prevent interference with form submissions
    // aside.addEventListener("click", function (event) {
    //     var link = event.target.closest("a[href]");

    //     if (
    //         link &&
    //         link.getAttribute("href") &&
    //         link.getAttribute("href").indexOf("javascript:") !== 0 &&
    //         link.getAttribute("href") !== "#" &&
    //         aside.contains(link)
    //     ) {
    //         setOpen(false);
    //     }
    // }, false);

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            setOpen(false);
        }
    });

    window.addEventListener("resize", function () {
        if (!isMobile()) {
            setOpen(false);
        }
    });

    setOpen(false);
})();
