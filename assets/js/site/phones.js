$(document).ready(function(){
	var param = window.location.hash,
		itwas = 0,
		showPartType = function (part) {
				if (part == '') part = '#c';

				if(part == '#c') {
					$('.cabinet').show();
					$('.solder').hide();
					$('.c').removeClass("selected");
					$('.s').addClass("selected");
					setTimeout(function(){
						$('div.cabinet a.zoom').jqzoom({
								zoomType: 'drag',
								lens:true,
								zoomWidth: $('div.cabinet').width() - $('div.cabinet img.zoom_src').width() - 15,
								zoomHeight: 300,
								position:'right'
							});
					}, 50);
				}

				if(part == '#s') {
					$('.solder').show();
					$('.cabinet').hide();
					$('.s').removeClass("selected");
					$('.c').addClass("selected");
					setTimeout(function(){
						$('div.solder a.zoom').jqzoom({
								zoomType: 'drag',
								lens:true,
								zoomWidth: $('div.solder').width() - $('div.solder img.zoom_src').width() - 15,
								zoomHeight: 300,
								position:'right'
							});
					}, 50);
				}

				window.location.hash = part;
			};

	// prepare layout, fade in
	$('.solder').hide();
	$('div.full-transparent').delay(500).animate({opacity: 1}, 500);

	showPartType(param);

	$('.s').click(function() {
		showPartType('#c');
		return false;
	});

	$('.c').click(function() {
		showPartType('#s');
		return false;
	});

	$('#showparts').click(function(){
		this.href += window.location.hash;
	});
	// tablesorter
	$("table").tablesorter();
});