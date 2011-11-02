$(document).ready(function(){
	var url = window.location.href;
	var param = url.substring(url.lastIndexOf('/') + 1);
	var itwas = 0;
		$('#cabinet_img, #solder_img').jqzoom({zoomType: 'drag'});

	showPartType = function (part) {
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
	}

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
});