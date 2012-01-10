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

	icons: {
		close: '<img src="/assets/images/icons/close.png" class="close" title="Закрыть" />',
		noimage: '<img src="/assets/images/icons/noimage.gif" title="Нет изображения" />'
	},

	messages: {
		noData: 'Нет информации'
	},

	urls: {
		saveRegion: '/apanel/regions/save/',
		removeRegion: '/apanel/regions/remove/',
		set_defaultRegion: '/apanel/regions/set_default/',
		saveVendor: '/apanel/vendors/save/',
		removeVendor: '/apanel/vendors/remove/',
		set_visibleVendor: '/apanel/vendors/set_visible/',
		getVendorModels: '/apanel/models/get_by_vendor/',
		saveDataTpl: '/apanel/import/save_data_template/',
		removeDataTpl: '/apanel/import/remove_data_template/',
		changePN: '/apanel/parts/change_pn/',
		getPart: '/apanel/parts/get/'
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
		this.initSlideable();

		$('body').ajaxStart(function() {
				$(this).addClass('loading');
		}).ajaxStop(function(){
				$(this).removeClass('loading');
			});
	},

	templates: {

		regions: {
			edit: '<form action="<%= app.urls.saveRegion %>">' +
						'<input type="hidden" name="id" value="<%= id %>" />' +
						'<input type="text" name="name" class="w250" value="<%= title %>" />' +
						'<button name="save" type="submit">Сохранить</button>' +
						'<button name="cancel">Отменить</button>' +
					'</form>',
			item: '<% _.each(items, function(item){ %>' +
					'<li><span id="r<%= item.id %>">' +
						'<span title="Удалить регион \'<%= item.name %>\'" class="remove-item icon-container fr">' +
							'<span class="ui-icon ui-icon-close"></span>' +
						'</span>' +
						'<span title="Редактировать регион \'<%= item.name %>\'" class="edit-item icon-container fr">' +
							'<span class="ui-icon ui-icon-pencil"></span>' +
						'</span>' +
						'<span class="name"><%= item.name %></span>' +
						' <span> (</span> <input type="radio" class="region-default" name="default" value="<%= item.id %>" ><span>&nbsp;)</span>' +
					'</span></li>' +
				'<% }); %>'
		},
		vendors: {
			edit: '<form action="<%= app.urls.saveVendor %>">' +
						'<input type="hidden" name="id" value="<%= id %>" />' +
						'<input type="text" name="name" class="w250" value="<%= title %>" />' +
						'<button name="save" type="submit">Сохранить</button>' +
						'<button name="cancel">Отменить</button>' +
					'</form>',
			item: '<% _.each(items, function(item){ %>' +
					'<li><span id="v<%= item.id %>">' +
						'<span title="Удалить вендора \'<%= item.name %>\'" class="remove-item icon-container fr">' +
							'<span class="ui-icon ui-icon-close"></span>' +
						'</span>' +
						'<span title="Редактировать вендора \'<%= item.name %>\'" class="edit-item icon-container fr">' +
							'<span class="ui-icon ui-icon-pencil"></span>' +
						'</span>' +
						'<span class="name"><%= item.name %></span>' +
						'<span> (не активно</span> <input type="checkbox" class="vendor-show" name="show" value="0" ><span>)</span> '+
					'</span></li>' +
				'<% }); %>'
		}
	}
};

$(document).ready(function(){
	app.init();
});