$(window).load(function() {
    $('#multiselectall').click(function() {
        text = $(this).text();
        if ( text == 'Tout sélectionner' ) {
            $(this).text('Déselectionner tout');
            $('.multidelete input[type=checkbox]').prop('checked', true);
        }
        else {
            $(this).text('Tout sélectionner');
            $('.multidelete input[type=checkbox]').prop('checked', false);
        }
        return false;
    });
});
