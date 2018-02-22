module.exports = function () {
    let popupTab = document.querySelectorAll('.popUpTuto');

    popupTab.forEach( function ( popup, index ) {
        let id = popup.getAttribute('id');
        let isStored = getItem(id);

        if ( isStored == 'false' ) {
            hidePopupHard(popup);
        } else {
            showPopup(popup);
            setItem(id, true);
        }

        popup.addEventListener('click', function () {
            hidePopup(popup);
            setItem(id, false);
        })
    });

    function hidePopup(popup) {
        popup.classList.add('hide-popup');
        popup.classList.remove('show-popup');
    }

    function hidePopupHard(popup) {
        popup.classList.add('hide');
    }

    function showPopup (popup) {
        popup.classList.add('show-popup');
        popup.classList.remove('hide-popup');
    }

    function setItem (key, value) {
        value = JSON.stringify(value);
        localStorage.setItem(key, value)
    }

    function getItem ( key ) {
       return localStorage.getItem(key);
    }

};
