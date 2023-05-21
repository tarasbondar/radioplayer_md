export function header() {
    const bodyTag = document.querySelector('body');
    const menuBackdrop = document.querySelector('[data-menu-backdrop]');
    const menuTrigger = document.querySelector('[data-menu-open]');
    const menu = document.querySelector('[data-menu]');
    const menuClose = document.querySelector('[data-menu-close]');

    const closeMenu = function () {
        menu.classList.remove('active');
        menuBackdrop.classList.remove('active');
        menuBackdrop.removeEventListener('click', closeMenu);
        bodyTag.classList.remove('menu-open');
    }

    const openMenu = function () {
        bodyTag.classList.add('menu-open');
        menu.classList.add('active');
        menuBackdrop.classList.add('active');
        menuBackdrop.addEventListener('click', closeMenu);
    }
    if (menuTrigger !== null)
        menuTrigger.addEventListener('click', openMenu);
    if (menuClose !== null)
        menuClose.addEventListener('click', closeMenu);
}
