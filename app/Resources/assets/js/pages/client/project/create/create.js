module.exports = function ()
{
    $(document).ready(function () {

        /*** FONCTION POUR AJOUTER PLUSIEURS IMAGES ****/

        var x = 1; //initlal text box count
        var max_fields = 7; //maximum input boxes allowed
        var wrapper = $('.containerOtherFile'); //Fields wrapper

        $(".addFiles span").on("click" , function (e) {
            e.preventDefault();
            var htmlInput = "<div class='blockImgOther block"+x+"'><label class='otherImg"+x+"' for='otherImg' >Uploader votre image <input id='otherImg"+x+"' type='file' name='otherImg[]' class='otherImg' accept='image/*'> </label></div>"
            if(x <= max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append(htmlInput); //add input box$
            }
        });

        $(wrapper).on("change",".blockImgOther input", function(e) { //user click on remove text
            var file = $(this)[0].files[0]
            $(this).parents('.blockImgOther').addClass('fileIn');
            $(this).parents('.blockImgOther').append("<div class='infoFiles'><p>" + file.name+ "<br clear=\"left\"/></p><span class='selFile'>+</span></div>").show('slow'); //add input box
        });

        $(wrapper).on("click",".selFile", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parents('.blockImgOther').remove();
            x--;
        });


        /*** FIN FONCTION POUR AJOUTER PLUSIEURS IMAGES ****/



        /*** AFFICHER L'IMAGE A LA UNE ****/
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.blockInput.file').addClass('imgAdd')
                    $('.imgFirstProject').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("[name='project[mainPicture]']").change(function() {
            readURL(this);
        });
        /*** FIN AFFICHER L'IMAGE A LA UNE ****/

        /*** REMOVE & ADD IMAGE A LA UNE ****/
        $(document).ready(function () {
            $('span.modif').on('click', function () {
                $("[name='project[mainPicture]']").click()
            });
            $('span.remove').on('click', function () {
                $("[name='project[mainPicture]']").val('');
                $('.file').removeClass('imgAdd')
            });
        })

        /*** FIN REMOVE & ADD IMAGE A LA UNE ****/

        /**** DATEPICKER *****/
        $("[name='project[date]']").pickadate({
            labelMonthNext: 'Mois suivant',
            labelMonthPrev: 'Mois précédent',
            labelMonthSelect: 'Selectionner le mois',
            labelYearSelect: 'Selectionner une année',
            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
            weekdaysFull: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            today: 'Aujourd\'hui',
            clear: 'Réinitialiser',
            close: 'Fermer',
            format: 'dd/mm/yyyy',
            closeOnSelect: true
        });

        /***** STYLE SELECT ****/
        $('select').niceSelect();

        $('.list').mouseenter(function () {
            $('li.selected',this).addClass('select')
        })

        /**** REMOVE CLASS noContain ON SELECT *****/

        $('.noContain').each(function () {
            $('.list', this).on('click', function () {
                $(this).parents('.blockInput').removeClass('noContain')
            })
        })

        /**** FIN REMOVE CLASS noContain ON SELECT *****/

        /***** INPUT CHECKBOX *****/
        $('#switch').on('change', function () {
            if ($(this).is(':checked')) {
                $('.statutProjet p.enCours').addClass('no');
                $('.statutProjet p.finis.no').removeClass('no')
            } else {
                $('.statutProjet p.finis').addClass('no');
                $('.statutProjet p.enCours.no').removeClass('no')
            }
        });
    });
    $('.adresse input').attr('id', "autocomplete");
    $('.adresse').on("focus"," input", function() {
        geolocate();
    });
    $('.adresse').on("change"," input", function() {
        let input = $(this);
        var geocoder = new google.maps.Geocoder();

        // Geocode the address
        geocoder.geocode({
            'address': input.value
        }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK && results.length > 0) {

                // set it to the correct, formatted address if it's valid
                input.value = results[0].formatted_address;

                // show an error if it's not
            } else input.val('');
        });
        /*
        $('.pac-container.pac-logo .pac-item').each(function() {
            if (valueInput !== $('span.pac-item-query', this).text()){
                console.log('------------------------ faux -----------------------')
                console.log('value = ' + valueInput)
                console.log($('span.pac-item-query', this).text());
            } else {
                console.log('------------------------ vrai -----------------------')
            }
            //
        })
        if (valueInput.length === 1) {  //check for no. of characters entered
            $(this).val('');  // clear the textbox
        }
        */
    })
};
