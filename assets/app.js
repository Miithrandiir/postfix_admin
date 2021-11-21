import "./styles/themes/_vars.scss"
import "./styles/app.scss"

window.addEventListener("load", () => {
    // Check to see if Media-Queries are supported
    if (window.matchMedia) {
        // Check if the dark-mode Media-Query matches
        if(window.matchMedia('(prefers-color-scheme: dark)').matches){
            document.querySelector("body").classList.add("theme-dark");
            document.querySelector("body").classList.remove("theme-light");
        } else {
            document.querySelector("body").classList.add("theme-light");
            document.querySelector("body").classList.remove("theme-dark");
        }
    } else {
        // Default (when Media-Queries are not supported)
    }

});