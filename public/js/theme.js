export function theme() {

    const updateSourceMedia = function(colorPreference) {
        const pictures = document.querySelectorAll('picture');

        pictures.forEach((picture) => {
            const sources = picture.querySelectorAll('source[media*="prefers-color-scheme"], source[data-media*="prefers-color-scheme"]');

            sources.forEach((source) => {
                // Preserve the source `media` as a data-attribute
                // to be able to switch between preferences
                if (source.media.includes('prefers-color-scheme')) {
                    source.dataset.media = source.media
                }

                // If the source element `media` target is the `preference`,
                // override it to 'all' to show
                // or set it to 'none' to hide
                if (source.dataset.media.includes(colorPreference)) {
                    source.media = 'all'
                } else if (source) {
                    source.media = 'none'
                }
            })
        })
    }


    const toggleTheme = document.querySelector('[data-theme-toggle]');
    // Get the user's theme preference from local storage, if it's available
    const currentTheme = localStorage.getItem('theme');
    // If the user's preference in localStorage is dark...
    if (currentTheme == 'dark') {
        document.firstElementChild.setAttribute('data-theme', 'dark');
        updateSourceMedia('dark');
    // Otherwise, if the user's preference in localStorage is light...
    } else if (currentTheme == 'light') {
        document.firstElementChild.setAttribute('data-theme', 'light')
        updateSourceMedia('light');
    }

    // Listen for a click on the button
    toggleTheme.addEventListener('click', function() {
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            if (currentTheme == 'dark') {
                document.firstElementChild.setAttribute('data-theme', 'light');
                updateSourceMedia('light');
                var theme = 'light';
            } else {
                document.firstElementChild.setAttribute('data-theme', 'dark');
                updateSourceMedia('dark');
                var theme = 'dark';
            }

            // save the current preference to localStorage
            localStorage.setItem('theme', theme);

        } else {
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

            if (prefersDarkScheme.matches) {
                document.firstElementChild.setAttribute('data-theme', 'light');
                updateSourceMedia('light');
                var theme = 'light';
            } else {
                document.firstElementChild.setAttribute('data-theme', 'dark');
                updateSourceMedia('dark');
                var theme = 'dark';
            }

            // save the current preference to localStorage
            localStorage.setItem('theme', theme);
        }
    });

}
