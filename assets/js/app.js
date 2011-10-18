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

	urls: {
        saveRegion: '/apanel/regions/save/',
		removeRegion: '/apanel/regions/remove/',
		set_defaultRegion: '/apanel/regions/set_default/',
		saveVendor: '/apanel/vendors/save/',
		removeVendor: '/apanel/vendors/remove/',
        set_visibleVendor: '/apanel/vendors/set_visible/'
	},

	text: {
		loading: 'Идет загрузка'
	},

	cache: {
		vendors: {},
        regions: {}
	},
	/** popup */
	splash: null,
	popup: null,
	popupContent: null,
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
	initMessages: function(){
		this.message = $('#message');
		this.messageContent = $('#message-content');

		var ap = this;
		ap.message.contents('img').click(function(){
			ap.message.fadeOut();
		});
	},

	/** shows "loading.." content in container waiting for data */
	showLoading: function(elm){
		$(elm).html(this.text.loading);
	},

	/** @TODO INIT */
	init: function(){
		this.initMessages();
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
						'<span title="Редактировать оегион \'<%= item.name %>\'" class="edit-item icon-container fr">' +
							'<span class="ui-icon ui-icon-pencil"></span>' +
						'</span>' +
						'<span class="name"><%= item.name %></span>' +
						' (по умолчнию <input type="radio" class="region-default" name="default" value="<%= item.id %>" >)' +
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
						'<span title="Удалить поставщика \'<%= item.name %>\'" class="remove-item icon-container fr">' +
							'<span class="ui-icon ui-icon-close"></span>' +
						'</span>' +
						'<span title="Редактировать поставщика \'<%= item.name %>\'" class="edit-item icon-container fr">' +
							'<span class="ui-icon ui-icon-pencil"></span>' +
						'</span>' +
						'<span class="name"><%= item.name %></span>' +
						' (активно <input type="checkbox" class="vendor-show" name="show" value="0" >)' +
					'</span></li>' +
				'<% }); %>'
		}
	}
};

$(document).ready(function(){
	app.init();
});
