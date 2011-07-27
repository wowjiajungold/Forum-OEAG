$(document).ready(function() {
	$('tr').bind('mouseover mouseout', function() {
		$(this).toggleClass('hover');
	});
});
