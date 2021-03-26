window.addEventListener("load", () => {
    let loader = document.querySelector("#loader");
    let alreadyLoaded = localStorage.getItem('isLoaded');

    if(alreadyLoaded !== 'true') {

        loader.style.opacity = '1';
        loader.style.display = 'flex';


        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';

                localStorage.setItem('isLoaded', true);

            }, 500);
        }, 1000);
    }
});


