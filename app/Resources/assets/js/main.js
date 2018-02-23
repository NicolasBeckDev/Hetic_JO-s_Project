const $ = require('jquery');

require('bootstrap-sass');

require('../img/pages/admin/project/create/arrow.png');
require('../img/pages/admin/project/create/delete.png');
require('../img/pages/admin/project/create/validate.png');

require('../img/background-homepage.jpg');
require('../img/connexion.png');
require('../img/inscription.png');
require('../img/user.png');

require('../img/pages/project/default_environnement.jpg');
require('../img/pages/project/default_mobilite_transport.jpg');
require('../img/pages/project/default_solidaire_citoyen.jpg');
require('../img/pages/project/default_technologie.jpg');
require('../img/pages/project/default_urbanisme.jpg');

require('../img/pages/about/campagne_celebration.jpg');
require('../img/pages/about/team.jpg');

require('./lib/jquery-nice-select/jquery.nice-select.min')();
require('./lib/picker/picker')();
require('./lib/picker/picker.date')();
require('./lib/picker/picker.time')();
require('./lib/owl-carousel/owl.carousel.min')();

require('./layout/header')();
require('./pages/form/form-widget')();
require('./pages/client/project/create/create')();
require('./pages/client/project/show/display-text')();
require('./pages/client/project/show/favoris-color')();
require('./pages/client/account/index')();
require('./pages/admin/user/edit')();

$(function () {

    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            items : 1,
            mouseDrag : false,
            nav: true,
            rewind: true,
            lazyLoad: true,
            dots: false,
            navText: [
                "<i class='fa fa-chevron-left'></i>",
                "<i class='fa fa-chevron-right'></i>"
            ]
        });
    });

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