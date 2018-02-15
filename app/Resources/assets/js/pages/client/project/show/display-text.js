module.exports = function ()
{
    $('.project-show-content .switch').on('click', function ()
    {
        const textTargetted = $(this).data('target');

        $('.project-show-content .switch').removeClass('active');
        $(this).addClass('active');
        $('.project-show-content .text-wrapper p').removeClass('active');
        $('.project-show-content .text-wrapper .' + textTargetted).addClass('active');
    });
};