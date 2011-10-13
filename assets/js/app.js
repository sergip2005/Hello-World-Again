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
		imgPathPrefix: 'http://www.mktel.com.ua/content/object/',
		imgThumbSufffix: 's.jpg',
		imgBigSufffix: 'b.jpg',


		saveVendor: '/Users/save/',
		removeVendor: '/Users/remove/',
		getVendor: '/apanel/vendors/get/'

	},

	text: {
		loading: 'Идет загрузка'
	},

	cache: {
		shop: {
			catsNav: {},
			items: {}
		},
		suppliers: {},
		vendors: {},
		points: {}
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

	/** shows "loading.." content in container waiting for data */
	showLoading: function(elm){
		$(elm).html(this.text.loading);
	},

	templates: {

		users: {
			edit: '<form action="<%= app.urls.saveUser %>" class="item-info users-item">' +
					'<h1><%= item.id > 0 ? ("Редактирование профиля пользователя №"  + item.id) : ("Создание нового профиля пользователя") %></h1>' +
					'<input type="hidden" name="id" value="<%= item.id %>" />' +
					'<table>' +
						'<tr><td>Имя:</td><td><input type="text" class="text" value="<%= item.name %>" name="name" /></td></tr>' +
						'<tr><td colspan="2" class="tar"><button name="cancel">Отменить</button> <button type="submit" disabled="disabled">Сохранить</button></td></tr>' +
					'</table>' +
				'</form>',
			show: '<div class="item-info" id="s<%= item.id %>">' +
					'<div class="top-icons">' +
						'<span class="remove icon-container fr" title="Удалить поставщик \'<%= item.name %>\'">' +
							'<span class="ui-icon ui-icon-close"></span>' +
						'</span>' +
						'<span class="edit icon-container fr" title="Редактировать поставщика \'<%= item.name %>\'">' +
							'<span class="ui-icon ui-icon-pencil"></span>' +
						'</span>' +
					'</div>' +
					'<h1 id="name"><%= item.name %></h1>' +
					'<span class="label">Название</span>: <span id="login"><%= item.login %></span><br />' +
					'</div>',
			ul: '<li><a id="c<%= item.id %>" href="#"><span class="name"><%= item.name %></span></a></li>'
		}
	}
};