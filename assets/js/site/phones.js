$(document).ready(function(){
	$('.s').click(function() {
		$('.cabinet').show();
		$('.solder').hide();
		$('.c').removeClass("selected");
		$('.s').addClass("selected");
		return false;
	});
	$('.c').click(function() {
		$('.solder').show();
		$('.cabinet').hide();
		$('.s').removeClass("selected");
		$('.c').addClass("selected");
		return false;
	});

	$('#cabinet_img, #solder_img').jqzoom({zoomType: 'drag'});

	// prepare layout, fade in
	$('.solder').hide();
	$('div.full-transparent').delay(500).animate({opacity: 1}, 500);
});