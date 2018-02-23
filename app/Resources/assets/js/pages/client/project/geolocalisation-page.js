module.exports = function ()
{
    var selectOption1 = '<option data-display="Catégorie du projet" selected disabled>Catégorie du projet</option>'
    var selectOption2 = '<option data-display="Statut du projet" selected disabled>Status du projet</option>'
    var selectOption3 = '<option data-display="Km autour de moi" selected disabled>Km autour de moi</option>'
    $('#location_category').prepend(selectOption1)
    $('#location_status').prepend(selectOption2)
    $('#location_range').prepend(selectOption3)

    $('option').each(function(){
        if ($(this).text() == ""){
            $(this).remove()
        }
    });

    $('#location_range option').each(function () {
        var i = $(this).text() + ' Km'
        $(this).text(i)
    })


    var WindowSize = $(window).width();
    if (WindowSize < 1024){

        $('.popUpContainer.geoloc').on('click', function () {
            $('#map-col, .mapSwitch').show()
            $('.mapSwitch').addClass('active')
            $('.location-content .main-container .no-padding.col-sm-7, .popUpContainer.geoloc').hide()
        })

        $('#location_search').on('click', function () {
            $('.filter-container').hide().removeClass('active')
            $('.mapSwitch').show().addClass('active')
        })

        $('.mapSwitch .left').on('click', function () {
            $('.location-content .main-container .no-padding.col-sm-7').show()
            $('#map-col').hide()
            $('.mapSwitch').removeClass('active')
            $('.popUpContainer.geoloc').show().addClass('active')
        })

        $('.mapSwitch .right').on('click', function () {
            $('.filter-container').show().addClass('active')
            $('.mapSwitch').hide().removeClass('active')
        })
        $('.closeFilter').on('click', function () {
            $('.filter-container').hide().removeClass('active')
            $('.mapSwitch').show().addClass('active')
        })
    }

}