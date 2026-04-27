window.addEventListener('load', function () {
    const preloader  = document.getElementById('preloader');
    const progressBar = document.getElementById('progress-bar');
    if (!preloader || !progressBar) return;

    let progress = 0;

    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            setTimeout(() => {
                preloader.style.opacity = '0';
                setTimeout(() => { preloader.style.display = 'none'; }, 500);
            }, 300);
        }
        progressBar.style.width = `${progress}%`;
    }, 100);
});
