module.exports = function () {
    $('.input-placeholder-effect input').on('change', function ()
    {
        var input = $(this);
        if (input.val().length) {
            input.addClass('populated');
        } else {
            input.removeClass('populated');
        }
    });

    $('.input-placeholder-effect input').on('focus', function ()
    {
        var input = $(this);
        input.siblings(".before-border").fadeTo('fast', 1);
    });

    $('.input-placeholder-effect input').on('blur', function ()
    {
        var input = $(this);
        input.siblings(".before-border").fadeTo('fast', 0);
    });
};