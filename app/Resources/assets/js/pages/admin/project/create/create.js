module.exports = function ()
{
    var x = 1; //initlal text box count
    var max_fields = 10; //maximum input boxes allowed

    $(document).ready(function () {

        /*** FONCTION POUR AJOUTER PLUSIEURS IMAGES ****/
        var wrapper = $('.containerOtherFile'); //Fields wrapper

        $(".addFiles span").on("click" , function (e) {
            e.preventDefault();
            var htmlInput = "<div class='blockImgOther block"+x+"'><label class='otherImg"+x+"' for='otherImg' >Uploader votre image <input id='otherImg"+x+"' onchange='handleFileSelect(this)' type='file' name='otherImg[]' class='otherImg' accept='image/*'> </label></div>"
            $(wrapper).append(htmlInput); //add input box$
            /*if(x <= max_fields) { //max input box allowed
             x ++;
             $(wrapper).append(htmlInput); //add input box$
             }
             */
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
        $("#imgProjet").change(function() {
            readURL(this);
        });
        /*** FIN AFFICHER L'IMAGE A LA UNE ****/

        /*** REMOVE & ADD IMAGE A LA UNE ****/
        $(document).ready(function () {
            $('span.modif').on('click', function () {
                $('#imgProjet').click()
            });
            $('span.remove').on('click', function () {
                $('#imgProjet').val('');
                $('.file').removeClass('imgAdd')
            });
        })

        /*** FIN REMOVE & ADD IMAGE A LA UNE ****/

        /**** DATEPICKER *****/
        $('#dateFirst').pickadate({
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

    function handleFileSelect(el){
        var file = $(el)[0].files[0]
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(el).parents('.blockImgOther').addClass('fileIn');
            $(el).parents('.blockImgOther').append("<div class='infoFiles'><p>" + file.name+ "<br clear=\"left\"/></p><span class='selFile'>+</span></div>").show('slow'); //add input box
        }
    }
};