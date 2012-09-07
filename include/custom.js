$(document).ready(function() {
	$('tr').bind('mouseover mouseout', function() {
		$(this).toggleClass('hover');
	});
	
// 	setInterval( function() {
// 		$('.boxnews li.visible').removeClass('visible').next().addClass('visible');
// 	},
// 	1000 );
});
