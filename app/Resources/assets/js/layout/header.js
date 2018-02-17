module.exports = function ()
{
    $('.menuBurger').on('click', function () {
        $('header .left .content, .menu-mobile .menuBurger, .menuComplet').toggleClass('open');
        $('.menu-mobile').toggleClass('menu');
    });

    $('header .menu-mobile .right .user, header .menu-desktop .right .containerImg').on('click', function () {
        $('.menuUser').toggleClass('active');
        $(this).toggleClass('active')
    });
};