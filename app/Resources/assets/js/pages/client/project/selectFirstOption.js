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
}