$(window).load(function() {
	$('tr').bind('mouseover mouseout', function() {
		$(this).toggleClass('hover');
	});
	
// 	$('#brdmenu li').hover(function() {
// 		$(this).find('span').show(0);
// 	},
// 	function() {
// 		$(this).find('span').hide(0);
// 	});
});
