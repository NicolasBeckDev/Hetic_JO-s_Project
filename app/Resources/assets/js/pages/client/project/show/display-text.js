module.exports = function ()
{
    $('.project-show-content .switch').on('click', function ()
    {
        const textTargetted = $(this).data('target');

        $('.project-show-content .switch-container').removeClass('active');
        $(this).parent().addClass('active');
        $('.project-show-content .text-wrapper p').removeClass('active');
        $('.project-show-content .text-wrapper .' + textTargetted).addClass('active');
    });
};