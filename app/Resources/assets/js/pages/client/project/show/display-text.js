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

    var animateButton = function(e) {

        e.preventDefault;
        //reset animation
        e.target.classList.remove('animate');

        e.target.classList.add('animate');
        setTimeout(function(){
            e.target.classList.remove('animate');
        },700);
    };

    var bubblyButtons = document.getElementsByClassName("bubbly-button");

    for (var i = 0; i < bubblyButtons.length; i++) {
        bubblyButtons[i].addEventListener('click', animateButton, false);
    }
};