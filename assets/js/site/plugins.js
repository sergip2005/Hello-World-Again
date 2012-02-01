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