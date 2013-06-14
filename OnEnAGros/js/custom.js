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
    
    $('#wp-synchro').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url:  '/wp_synchro.php',
            success: function(data) {
                $('#wp-synchro').html(data);
            },
            error: function() {
                $('#wp-synchro').html('… Y a du avoir un problème au moment où j’ai mis le feu…');
            }
        });
        
    });
});
