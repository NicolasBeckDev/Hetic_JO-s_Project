module.exports = function ()
{
    const app = (() => {
        let menuIcon;
        let menu;
        let filter;

        const init = () => {
            menuIcon = document.querySelector('.menu-icon');
            menu = document.querySelector('.menu');
            filter = document.querySelector('.black-filter');
            applyListeners();
        };

        const applyListeners = () => {
            menuIcon.addEventListener('click', () => {
                toggleClass(menuIcon, 'menu-active');
                //toggleClass(menu, 'active');
                //toggleClass(filter, 'active');
            });
        };

        const toggleClass = (element, stringClass) => {
            if(element.classList.contains(stringClass))
                element.classList.remove(stringClass);
            else
                element.classList.add(stringClass);
        };

        init();
    })();
};