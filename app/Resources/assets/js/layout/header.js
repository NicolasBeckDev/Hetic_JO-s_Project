module.exports = function ()
{
    console.clear();

    alert('test');

    const app = (() => {
        let menu;

        const init = () => {
            menu = document.querySelector('.menu-icon');

            applyListeners();
        };

        const applyListeners = () => {
            menu.addEventListener('click', () => toggleClass(menu, 'menu-active'));
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