$(document).ready(function () {
    $('#btnMessage').on('click', function () {
        //configurer le formulaire
        $('#frmMessage').attr('action','/messages/sent');
        $('#frmMessage').attr('method','post');
        //envoyer le formulaire en mode POST
        $('#frmMessage').submit();
    });
});