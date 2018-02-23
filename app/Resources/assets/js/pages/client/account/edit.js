module.exports = function (){

    $(document).ready(function(){
        /*** AFFICHER L'IMAGE A LA UNE ****/
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.blockInput.file').addClass('imgAdd');
                    $('.imgFirstProject').attr('src', e.target.result);
                };

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
        });

        /*** FIN REMOVE & ADD IMAGE A LA UNE ****/
    })
};