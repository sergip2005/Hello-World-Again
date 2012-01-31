/** Main application settings object
 */
var app = {
	settings: {
		cacheTime: 600000,//10 * 1000 * 60
		itemCacheTime: 60000,//1000 * 60
		spl_cacheTime: 60000,
		prices_rates: {
			price1: 70,
			price2: 65,
			price3: 60,
			price4: 50
		}
	},

	debug: false,

	icons: {
		close: '<img src="/assets/images/icons/close.png" class="close" title="Закрыть" />',
		noimage: '<img src="/assets/images/icons/noimage.gif" title="Нет изображения" />'
	},

	messages: {
		noData: 'Нет информации'
	},

	text: {
		loading: 'Идет загрузка'
	},

	cache: {
		vendors: {},
		regions: {},
		models: {}
	},
	/** popup */
	splash: null,
	popup: null,
	popupContent: null,
	/**
	 * @param object s
	 * s.w - width
	 * s.h - height
	 * s.html - content
	 * s.c - callback
	 */
	showPopup: function (s) { // makes popup to show
		var c = this.popupContent, p = this.popup, total_w = $(window).width(),
			w = typeof s.w === 'undefined' || s.w <= 0 ? 'auto' : s.w,
			h = typeof s.h === 'undefined' || s.h <= 0 ? 'auto' : s.h;
		// resize box
		c.html(s.html).width(w).height(h);
		// show popup
		this.splash.add(p).show();
		// position popup
		p.css('left', Math.round((total_w - p.width()) / 2) + 'px');
		// fire callback if provided
		if (_.isFunction(s.c)) {
			var clb = s.c;
			clb = _.bind(clb, c);// this reffers to #popupContent
			clb();
		}
	},

	/** message */
	message: null,
	messageContent: null,
	/**
	 * @param s - object with structure {html:string, c: function}
	 */
	showMessage: function (s) {
		var c = this.messageContent, p = this.message;
		c.html(s.html);
		p.show();
		// fire callback if provided
		if (_.isFunction(s.c)) {
			var clb = s.c;
			clb = _.bind(clb, c);// this reffers to #messageContent
			clb();
		}
	},

	log: function(){
		if (this.debug !== true) return false;
		if (typeof console.log !== undefined) {
			console.log(arguments);
		} else {
			return false;
		}
	},

	initMessages: function(){
		this.message = $('#message');
		this.messageContent = $('#message-content');

		this.popup = $('#popup');
		this.popupContent = $('#popup-content');
		this.splash = $('#splash').splash();

		var ap = this;
		ap.message.contents('img').click(function(){
			ap.message.fadeOut(250);
		});
		ap.popup.contents('img').click(function(){
			ap.popup.add(ap.splash).fadeOut(250);
		});
	},

	/** shows "loading.." content in container waiting for data */
	showLoading: function(elm){
		$(elm).html(this.text.loading);
	},

	initSlideable: function(){
		$('.slide-trigger').click(function(e){
			e.preventDefault();
			$('#' + this.id + '-content').stop().slideToggle('fast');
		});
	},

	/** @TODO INIT */
	init: function(){
		this.initMessages();
		//this.initSlideable();

		$('body').ajaxStart(function() {
				$(this).addClass('loading');
		}).ajaxStop(function(){
				$(this).removeClass('loading');
			});
	},

	templates: {
	}
};

$(document).ready(function () {
	var search = $(".select-and-input");

	/*$('form[name="search_parts_code"] input.text', search).livesearch({
		searchCallback: function(){ search.find('form[name="search_parts_code"]').trigger('submit'); },
		queryDelay: 250,
		innerText: "Код",
		minimumSearchLength: 2
	});
	$('form[name="search_model_name"] input.text', search).livesearch({
		searchCallback: function(){ search.find('form[name="search_model_name"]').trigger('submit'); },
		queryDelay: 250,
		innerText: "Модель",
		minimumSearchLength: 2
	});
	$('form[name="search_parts_name"] input.text', search).livesearch({
		searchCallback: function(){ search.find('form[name="search_parts_name"]').trigger('submit'); },
		queryDelay: 250,
		innerText: "Название",
		minimumSearchLength: 2
	});*/
	search.delegate('form[name="search_parts_code"]', 'submit', function(e){
			e.preventDefault();
			if($.trim($(this).find('input.text').val()) !== '') {
				window.location.href = '/parts/' + encodeURI($(this).find('input.text').val());
			}
		}).delegate('form[name="search_model_name"]', 'submit', function(e){
			e.preventDefault();
			if($.trim($(this).find('input.text').val()) !== '') {
				window.location.href = '/parts/models/' + encodeURI($(this).find('input.text').val());
			}
		}).delegate('form[name="search_parts_name"]', 'submit', function(e){
			e.preventDefault();
			if($.trim($(this).find('input.text').val()) !== '') {
				window.location.href = '/parts/search/' + encodeURI($(this).find('input.text').val());
			}
	});
});

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

function addToBasket(part_id,obj) {	
	var count = $('#basket').find('span').text();
	if (count =='') count = 0;
	count = parseInt(count,10);
	count = count + 1;
	var htmlText = '<a href="/basket">Товаров в корзине <span>'+count+'</span></a>';
	var amount = parseInt($(obj).parent().parent().find('.amount').val(),10);	
	if (isNaN(amount)) amount =1;	
	$.post("/basket/insertintobasket", {  part_id: part_id, amount:amount},
	function(data) {
		$('#basket').html(htmlText);
		mess = 'Количество '+amount+', итого в корзине '+count+' элементов';
		alert(mess);
	});
}

function removeFromBasket(id,obj) {	
	$(obj).parent().parent().remove();	
	$.post("/basket/removefrombasket", {  id: id },function(data) {});
}	

function changeAmount(obj) {	
	var amount = parseInt($(obj).val(),10);	
	var price = parseFloat($(obj).parent().parent().find('.price').text());
	var total = amount * price;
	total = total.toFixed(2);		 
	if (isNaN(amount) || amount==0) {
		total = price;
		$(obj).val(1);
	}
	$(obj).parent().parent().find('.totalPrice').html(total);	
}

function sendAmount(id,obj) {
	var amount = parseInt($(obj).val(),10);
	$.post("/basket/sendamount", {  id: id,amount:amount },function(data) {});
}
