/* jQuery Tiny Pub/Sub - v0.7 - 10/27/2011 http://benalman.com/ Copyright (c) 2011 "Cowboy" Ben Alman; Licensed MIT, GPL */
(function(a){var b=a({});a.subscribe=function(){b.bind.apply(b,arguments)},a.unsubscribe=function(){b.unbind.apply(b,arguments)},a.publish=function(){b.trigger.apply(b,arguments)}})(jQuery);

/** splash functionality extension to jquery
 * just call $(elm).splash() then show\hide it when you need
 */
(function ($) {
	$.fn.splash = function(settings) {
		// tests if position:fixed; property is supported by browser
		function is_position_fixed_supported() {
			var container = document.body;
			if (document.createElement && container && container.appendChild && container.removeChild) {
				var el = document.createElement("div");
				if (!el.getBoundingClientRect) {
					return null;
				}
				el.innerHTML = "x";
				el.style.cssText = "position:fixed;top:100px;";
				container.appendChild(el);
				var originalHeight = container.style.height,
					originalScrollTop = container.scrollTop;
				container.style.height = "3000px";
				container.scrollTop = 500;
				var elementTop = el.getBoundingClientRect().top;
				container.style.height = originalHeight;
				var isSupported = elementTop === 100;
				container.removeChild(el);
				container.scrollTop = originalScrollTop;
				return isSupported;
			}
			return null;
		}

		var config = {
			is_position_fixed_supported: is_position_fixed_supported()
		};

		if (settings) $.extend(config, settings);

		this.each(function () {
			var splash = this;
			// properties support check
			if (!config.is_position_fixed_supported) {
				// register js events to simulate position fixed in IE6 and so on
				$(splash).css('position', 'absolute');
				$(window).scroll(function() {
					$(splash).css('top', $(this).scrollTop() + "px");
				});
			}

			// init splash's width height
			$(splash).width($(window).width()).height($.getDocHeight());

			// resize #splash on viewport resized
			$(window).resize(function () {
				$(splash).width($(window).width()).height($.getDocHeight());
			});
		});

		return this;
	};
})(jQuery);

jQuery.getDocHeight = function () {// fix for detecting window height
	return Math.max(
		$(document).height(),
		$(window).height(),
		// For opera:
		document.documentElement.clientHeight
	);
};

jQuery.fn.ForceNumericOnly = function(){
	return this.each(function(){
		$(this).keydown(function(e){
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			return (
				key == 8 ||
				key == 9 ||
				key == 46 ||
				(key >= 37 && key <= 40) ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));
		});
	});
};

function round(amount, precision){
	var a = Math.pow(10, precision);
	return Math.round(amount * a) / a;
}

/*
 * Tooltip script
 * powered by jQuery (http://www.jquery.com)
 *
 * written by Alen Grakalic (http://cssglobe.com)
 *
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */

this.tooltip = function(){
	/* CONFIG */
		xOffset = 10;
		yOffset = 20;
		// these 2 variable determine popup's distance from the cursor
		// you might want to adjust to get the right result
	/* END CONFIG */
	$("a.tooltip").hover(function(e){
		this.t = this.title;
		this.title = "";
		$("body").append("<p id='tooltip'>"+ this.t +"</p>");
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");
    },
	function(){
		this.title = this.t;
		$("#tooltip").remove();
    });
	$("a.tooltip").mousemove(function(e){
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});
};