import "./styles/themes/_vars.scss"
import "./styles/app.scss"

window.addEventListener("load", () => {
    // Check to see if Media-Queries are supported
    if (window.matchMedia) {
        // Check if the dark-mode Media-Query matches
        window.matchMedia('(prefers-color-scheme: white)').addEventListener("change",(e) => {
            if (e.matches) {
                document.querySelector("body").classList.add("theme-light");
                document.querySelector("body").classList.remove("theme-dark");
            } else {
                document.querySelector("body").classList.add("theme-dark");
                document.querySelector("body").classList.remove("theme-light");
            }
        });
    } else {
        // Default (when Media-Queries are not supported)
        document.querySelector("body").classList.add("theme-dark");
    }

});