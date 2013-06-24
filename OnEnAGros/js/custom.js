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
            url:  '/OnEnAGros/wp_synchro.php',
            success: function(data) {
                $('#wp-synchro').html(data);
            },
            error: function() {
                $('#wp-synchro').html('… Y a du avoir un problème au moment où j’ai mis le feu…');
            }
        });
        
    });
    
    $('.nav a, .brdmenu a, .toolbar a').tooltip({
        placement: 'bottom'
    });
    
    $(window).scroll(function() {
        if ( window.scrollY > 460 )
            $('#oeag-toolbar').show();
        else
            $('#oeag-toolbar').hide();
    });
    
    $('a').click(function(e) {
        console.log(this.hash);
        if ( $(this.hash).length > 0 ) {
            e.preventDefault();
            $('body').animate({
                scrollTop: ( $(this.hash).offset().top - 42 )
            }, 250);
        }
    });
});
