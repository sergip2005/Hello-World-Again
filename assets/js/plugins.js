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


/**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 */
;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

// add new widget called repeatHeaders
$.tablesorter.addWidget({
	// give the widget a id
	id: "repeatHeaders",
	// format is called when the on init and when a sorting has finished
	format: function(table) {
		// cache and collect all TH headers
		if (!this.headers) {
			var h = this.headers = [];
			$("thead th",table).each(function() {
				h.push( "" + $(this).text() + "" );
			});
		}

		// remove appended headers by classname.
		$("tr.repated-header",table).remove();

		// loop all tr elements and insert a copy of the "headers"
		var l = table.tBodies[0].rows.length;
		for(var i=0; i < l; i += 1) {
			// insert a copy of the table head every 10th row
			if ((i%5) == 4) {
				$("tbody tr:eq(" + i + ")",table).before( $("").html(this.headers.join("")) );
			}
		}
	}
});