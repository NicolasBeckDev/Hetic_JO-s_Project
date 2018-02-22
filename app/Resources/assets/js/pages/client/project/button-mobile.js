module.exports = function ()
{
    $('.principaleButton3Btn').on('click', function () {
        $('.button3Btn').toggleClass('active');
        $(this).toggleClass('active')
    });
    $('.button3Btn.social').on('click', function () {
        $('.share-container').addClass('active ');
        $('.share-container').show()
    });
    $('.share-container span').on('click', function () {
        $('.share-container').removeClass('active ');
        $('.share-container').hide()
    });
};

