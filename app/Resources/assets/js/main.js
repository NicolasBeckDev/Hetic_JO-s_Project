const $ = require('jquery').noConflict(true);

require('bootstrap-sass');

require('../img/background-homepage.jpg');
require('../img/connexion.png');
require('../img/inscription.png');

require('./layout/header')();
require('./pages/form/form-widget')();
require('./pages/client/project/show/display-text')();

$(function () {

    function addRandomSizeClass(project)
    {
        const widthClass = ['', 'width-2'];

        if (!$(project).prev('.grid-item').hasClass('width-2')) {
            $(project).addClass(widthClass[Math.round(Math.random())]);
        }
    }

    let elem = document.querySelector('.grid');

    let item = elem.querySelectorAll('.grid-item');

    item.forEach(function (currentItem) {
        addRandomSizeClass(currentItem);
    });

    let iso = new Isotope( elem, {
        // options
        itemSelector: '.grid-item',
        percentPosition: true,
        masonry: {
            columnWidth: '.grid-sizer'
        },
        stagger: 50
    });

    $('.filter-wrapper button').on('click', function ()
    {
        let filterValue = $(this).attr('data-filter');

        $('.filter-wrapper button').removeClass('active');
        $(this).addClass('active');

        iso.arrange({
            filter: filterValue
        });
    })
});