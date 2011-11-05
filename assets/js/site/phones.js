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
				}

				if(part == '#s') {
					$('.solder').show();
					$('.cabinet').hide();
					$('.s').removeClass("selected");
					$('.c').addClass("selected");
				}

				window.location.hash = part;
			};

	$('#cabinet_img, #solder_img').jqzoom({zoomType: 'drag'});

	// prepare layout, fade in
	$('.solder').hide();
	$('div.full-transparent').delay(500).animate({opacity: 1}, 500);

	showPartType(param);

	$('.s').click(function() {
		showPartType('#c');
		if(itwas == 0) {
			$("#showparts").attr("href", $("#showparts").attr("href") + '/#c');
		} else {
			$("#showparts")
			.attr("href", $("#showparts").attr("href").substring(0, $("#showparts").attr("href").length - 1) + 'c')
		}
		itwas = 1;
		return false;
	});

	$('.c').click(function() {
		showPartType('#s');
		if(itwas == 0) {
			$("#showparts").attr("href", $("#showparts").attr("href") + '/#s');
		} else {
			$("#showparts")
			.attr("href", $("#showparts").attr("href").substring(0, $("#showparts").attr("href").length - 1) + 's');
		}
		itwas = 1;
		return false;
	});

	$('#showparts').click(function(){
		this.href += window.location.hash;
	});
});