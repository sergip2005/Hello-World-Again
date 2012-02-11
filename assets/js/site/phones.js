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
						$('div.cabinet a.zoom').jqzoom({ zoomType: 'innerzoom' });
						setTimeout(function(){
							$('div.cabinet a.zoom').css('marginLeft', Math.floor(($('div.cabinet').width() - $('div.cabinet img.zoom_src').width()) / 2) + 'px');
						}, 50);
					}, 50);
				}

				if(part == '#s') {
					$('.solder').show();
					$('.cabinet').hide();
					$('.s').removeClass("selected");
					$('.c').addClass("selected");
					setTimeout(function(){
						$('div.solder a.zoom').jqzoom({ zoomType: 'innerzoom' });
						setTimeout(function(){
							$('div.solder a.zoom').css('marginLeft', Math.floor(($('div.solder').width() - $('div.solder img.zoom_src').width()) / 2) + 'px');
						}, 50);
					}, 50);
				}

				window.location.hash = part;
			},
		updateTotalSum = function(cont){
			var t = $(cont).find('table'), totalElm = t.find('#total'), total = 0;
			t.find('input.num[value!=0][data-price!="0.00"]').each(function(){
				var n = parseInt(this.value), p = parseFloat($(this).data('price'));
				n = n > 0 ? n : 0;
				p = p > 0 ? p : 0;
				total += n * p;
			});
			totalElm.html(round(total, 2));
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
	$('input.num').ForceNumericOnly();

	$('.cabinet').delegate('input.num', 'keyup', function(){
			updateTotalSum('.cabinet');
		});

	$('.solder').delegate('input.num', 'keyup', function(){
			updateTotalSum('.solder');
		});

	updateTotalSum('.cabinet');
	updateTotalSum('.solder');
});