import { debounce } from "./debounce.js";

export function scrollingText() {

    function updateScrollingText() {
        var scrollingEl = document.querySelectorAll('[data-scrolling-text-container]');

        scrollingEl.forEach(function(e){
            var scrollingContainer = e;
            var containerWidth = scrollingContainer.offsetWidth;

            var textData = scrollingContainer.querySelector('[data-scrolling-text-data]');
            var textDataWidth = textData.offsetWidth;
            var cloned = scrollingContainer.querySelectorAll('[data-clone]');

            cloned.forEach(function(e){
                e.remove();
            });

            scrollingContainer.classList.remove('scroll-anim');

            if (textDataWidth > containerWidth) {
                var scrollingInnerContainer = scrollingContainer.querySelector('[data-scrolling-text]');
                var clone = textData.cloneNode(true);
                clone.setAttribute('data-clone', '');
                scrollingContainer.classList.add('scroll-anim');
                scrollingInnerContainer.appendChild(clone);
            }
        });
    }

    updateScrollingText();

    const processScrollingText = debounce(() => updateScrollingText());

    window.addEventListener('resize', processScrollingText);

}
