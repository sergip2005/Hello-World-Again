/** jQuery pub/sub plugin by Peter Higgins (dante@dojotoolkit.org)
	Loosely based on Dojo publish/subscribe API, limited in scope. Rewritten blindly.
	Original is (c) Dojo Foundation 2004-2009. Released under either AFL or new BSD, see:
	http://dojofoundation.org/license for more information.
*/

;(function(d){

	// the topic/subscription hash
	var cache = {};

	d.publish = function(/* String */topic, /* Array? */args){
		// summary:
		//		Publish some data on a named topic.
		// topic: String
		//		The channel to publish on
		// args: Array?
		//		The data to publish. Each array item is converted into an ordered
		//		arguments on the subscribed functions.
		//
		// example:
		//		Publish stuff on '/some/topic'. Anything subscribed will be called
		//		with a function signature like: function(a,b,c){ ... }
		//
		//	|		$.publish("/some/topic", ["a","b","c"]);
		d.each(cache[topic], function(){
			this.apply(d, args || []);
		});
	};

	d.subscribe = function(/* String */topic, /* Function */callback){
		// summary:
		//		Register a callback on a named topic.
		// topic: String
		//		The channel to subscribe to
		// callback: Function
		//		The handler event. Anytime something is $.publish'ed on a
		//		subscribed channel, the callback will be called with the
		//		published array as ordered arguments.
		//
		// returns: Array
		//		A handle which can be used to unsubscribe this particular subscription.
		//
		// example:
		//	|	$.subscribe("/some/topic", function(a, b, c){ /* handle data */ });
		//
		if(!cache[topic]){
			cache[topic] = [];
		}
		cache[topic].push(callback);
		return [topic, callback]; // Array
	};

	d.unsubscribe = function(/* Array */handle){
		// summary:
		//		Disconnect a subscribed function for a topic.
		// handle: Array
		//		The return value from a $.subscribe call.
		// example:
		//	|	var handle = $.subscribe("/something", function(){});
		//	|	$.unsubscribe(handle);

		var t = handle[0];
		cache[t] && d.each(cache[t], function(idx){
			if(this == handle[1]){
				cache[t].splice(idx, 1);
			}
		});
	};

})(jQuery);

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

jQuery.fn.extend({
	reset: function(){// reset form fields
		return this.each(function () {
			$(':input', this)
				.not(':button, :submit, :reset, :hidden')
				.val('')
				.removeAttr('checked')
				.removeAttr('selected');
		});
	}
});

jQuery.getDocHeight = function () {// fix for detecting window height
	return Math.max(
		$(document).height(),
		$(window).height(),
		// For opera:
		document.documentElement.clientHeight
	);
};

/** jQuery plugin: PutCursorAtEnd 1.0
 * http://plugins.jquery.com/project/PutCursorAtEnd
 * by teedyay
 *
 * Puts the cursor at the end of a textbox/ textarea
 */
(function ($) {
	jQuery.fn.putCursorAtEnd = function () {
		return this.each(function () {
			$(this).focus();

			if (this.setSelectionRange) {
				// (Doesn't work in IE)
				// Double the length because Opera is inconsistent about whether a carriage return is one character or two
				var len = $(this).val().length * 2;
				this.setSelectionRange(len, len);
			} else {
				// (Doesn't work in Google Chrome)
				$(this).val($(this).val());
			}

			// Scroll to the bottom, in case we're in a tall textarea (Necessary for Firefox and Google Chrome)
			this.scrollTop = 999999;
		});
	};
})(jQuery);
