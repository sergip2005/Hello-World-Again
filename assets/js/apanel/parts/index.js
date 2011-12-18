//_.sortBy(partsManager.cache.parts, function(a){ return a.code; });
var partsManager = {


	config: {
		vendor_id: 0,
		model_id: 0
	},

	cache: {
		vendors: {},
		models: {},
		parts: {}
	},

	templates: {
		partTr:
				'<tr data-id="<%= id %>" class="<%= i % 2 ? \'even\' : \'odd\' %>">' +
					'<td><input type="checkbox" value="<%= id %>"></td>' +
					'<td><%= vendor_name %></td>' +
					'<td><%= model_name %></td>' +
					'<td><%= cct_ref %></td>' +
					'<td><%= ptype %></td>' +
					'<td><%= code %></td>' +
					'<td><%= num %></td>' +
					'<td><%= name %></td>' +
					'<td><%= name_rus %></td>' +
					'<td><%= (available ? "+" : "-") %></td>' +
					'<td><%= price > 0 ? price : "нет данных" %></td>' +
					'<td><%= min_num %></td>' +
				'</tr>',

		selectVendors: '<option value="<%= id %>" <% if(vendor_id != "") {%><%= id == vendor_id ? "selected" : ""%> <% } %>><%= name %></option>' ,

		pagination: '<p>Показано <%= current.begin + " - " + current.end %> из <%= items %> элементов на <%= pages + (pages == 1 || pages%10 == 1 ? " странице" : " страницах") %></p>' +
				'<% if (pages > 1) { %>' +
				'<ul><% for (var i = 1; i <= pages; i += 1) { %>' +
					'<li><a href="#" data-page="<%= i - 1 %>"<%= (i - 1) == page ? " class=\'active\'" : "" %>><%= i %></a></li>' +
				'<% } %></ul>' +
				'<% } %>',

		formModel:  '<table>' +
					'<tr><td><label class="fr" for="model_name">Имя модели:</label></td><td  class="fl"><input type="text" id="model_name" name="model_name" value="<%= name %>"></td></tr>' +
					'<tr><td><label class="fr" for="vendors">Модель принадлежит вендору:</label></td><td  class="fl"><select id="vendors" name="vendors"></select></td></tr>' +
					'<tr><td><label class="fr" for="model_image">Изображение модели:</label></td><td  class="fl"><% if(model_image != "") { model_image == "" ? "" : \'<a href="/assets/images/phones/">\' + model_image + \'</a>\' } %><input type="file" id="model_image" name="model_image" /></td></tr>' +
					'<tr><td><label class="fr" for="solder_image">Изображение корпусных деталей:</label></td><td class="fl"><input type="file" id="solder_image" name="solder_image" /></td></tr>' +
					'<tr><td><label class="fr" for="cabinet_image">Изображение паечных деталей:</label></td><td class="fl"><input type="file" id="cabinet_image" name="cabinet_image" /></td></tr>' +
					'<tr><td><button class="fr" name="create_model">Создать</button></td><td  class="fl"><button name="close">Отмена</button></td></tr></table>'
	},

	init: function() {
		this.v = $('#vendors');
		this.m = $('#models');
		this.p = $('#parts');
		this.pchbx = this.p.parent().find(':checkbox.check-all');
		this.c = $('#controls');
		this.s = $('#search');

		this.mm = $('#move-models');
		this.pc = $('#popup-content');

		this.pages = $('#pages-bottom, #pages-top');

		var pm = this;

		// vendors list
		pm.v.delegate('li', 'click', function() {
			pm.setVendorLiActive(this, false);
			pm.getVendorModels($(this).data('id'));
			pm.s.find('input.text').removeClass('active');
		});

		//popup change vendor select
		 pm.pc.delegate('select[name="vendor"]', 'change', function(e) {
			pm.getVendorModels(-1, $('select[name="vendor"] option:selected').val());
		 }).delegate('button[name="move"]', 'click', function(e) {
			pm.moveParts(pm.pc.find('#move-models :selected').val(), pm.pc.find('input[name="move_parts_id"]').val())
		 }).delegate('button[name="create_model"]', 'click', function(e) {
			$('#model_image').uploadifyUpload();
		 }).delegate('button[name="close"]', 'click', function(e) {
			app.popup.add(app.splash).hide();
		 });

		// dynamic models list
		pm.m.delegate('li:not(.add)', 'click', function(e) {
			pm.s.find('input.text').removeClass('active');
			pm.setModelLiActive(this, false);
			pm.getModelParts($(this).data('id'), 1);
		});

		pm.m.delegate('li span.edit-item', 'click', function(e) {
			e.stopPropagation();
			pm.formModel($(this).data('id'), $('#vendors li.active').data('id'));
		});
		pm.m.delegate('li span.remove-item', 'click', function(e) {
			e.stopPropagation();
			pm.removeModel($(this).data('id'));
		});
		pm.m.delegate('li.add', 'click', function(e) {
			e.stopPropagation();
			pm.formModel(0, $(this).data('vendor_id'));
		});

		// init sortables
		pm.p.parents('table').tablesorter({ headers: { 0: { sorter: false} }, widgets: ['zebra', 'repeatHeaders'] });

		// rows clicks
		pm.p.delegate('tr', 'click', function(e){
			$(this).find(':checkbox').trigger('click');
		}).delegate('tr :checkbox', 'click', function(e){
			e.stopPropagation();
			var i = $(this);
			if (i.prop('checked')) {
				pm.checkRow($(this).parents('tr'));
			} else {
				pm.uncheckRow($(this).parents('tr'));
			}
		}).delegate('tr', 'dblclick', function(e) {
			var i = $(this);
			pm.showPartInfo(i.data('id'));
			app.log('dblclicked', i);
		});

		/*live search
		$('form[name="search_parts_code"] input.text', pm.s).livesearch({
			searchCallback: function(){ pm.s.find('form[name="search_parts_code"]').trigger('submit'); },
			queryDelay: 2000,
			innerText: "Код",
			minimumSearchLength: 2
		});
		$('form[name="search_model_name"] input.text', pm.s).livesearch({
			searchCallback: function(){ pm.s.find('form[name="search_model_name"]').trigger('submit'); },
			queryDelay: 2000,
			innerText: "Модель",
			minimumSearchLength: 2
		});
		$('form[name="search_parts_name"] input.text', pm.s).livesearch({
			searchCallback: function(){ pm.s.find('form[name="search_parts_name"]').trigger('submit'); },
			queryDelay: 2000,
			innerText: "Название",
			minimumSearchLength: 2
        });*/


		//search bottoms clicks

		pm.s.delegate('form[name="search_parts_code"]', 'submit', function(e){
			e.preventDefault();
			pm.setVendorLiActive(false, -1);
			pm.setModelLiActive(false, -1);
			pm.s.find('input.text').removeClass('active');
			pm.searchParts($(this).find('input.text').val(), $(this).find('input.parameter').val(), 1);
		}).delegate('form[name="search_model_name"]', 'submit', function(e){
			e.preventDefault();
			pm.setVendorLiActive(false, -1);
			pm.setModelLiActive(false, -1);
			pm.s.find('input.text').removeClass('active');
			pm.searchParts($(this).find('input.text').val(), $(this).find('input.parameter').val(), 1);
		}).delegate('form[name="search_parts_name"]', 'submit', function(e){

			e.preventDefault();
			pm.setVendorLiActive(false, -1);
			pm.setModelLiActive(false, -1);
			pm.s.find('input.text').removeClass('active');
			pm.searchParts($(this).find('input.text').val(), $(this).find('input.parameter').val(), 1);
		});

		// init check all
		pm.pchbx.click(function(e) {
			$(this).parents('table').find(':checkbox').attr('checked', this.checked);
		});

		pm.initControls();

		//if there is path in hash -> load it
		pm.initLocationHash();

		pm.pages.delegate('a', 'click', function(e){
			e.preventDefault();
			var a = $(this),
				h = location.hash.substr(1).split('/'),
				p = a.data('page');

			h[h.length - 1] = p + 1;
			location.hash = h.join('/');

			pm.initLocationHash();
		});
	},

	/**
	 * loads url saved in hash
	 */

	initLocationHash: function(){
		var h = document.location.hash.substr(1),
			pm = this;
		if (h !== '') {
			h = h.split('/');
			if (h.length > 0) {
				var v = parseInt(h[0], 10);
				if (v > 0) {
					pm.getVendorModels(v, function() {
						pm.setVendorLiActive(false, v);
						if (parseInt(h[1], 10) > 0 || h[1] == 'all' || h[1] == 'none') {
							pm.getModelParts(h[1], parseInt(h[2], 10));
							pm.setModelLiActive(false, h[1]);
						}
					});
				}else{
					if(h[0] == 'model_name' || h[0] == 'parts_code' || h[0] == 'parts_name')
					{
						pm.searchParts(h[1], h[0], parseInt(h[2], 10));
					}
				}
			}
		}
	},

	showPartInfo: function(i) {
		app.showPopup({html: i, c: function() {
		}});
	},

	getVendorElm: function(vId) {
		return this.v.contents('li[data-id="' + vId + '"]');
	},

	getModelElm: function(mId) {
		return this.m.contents('li[data-id="' + mId + '"]');
	},

	setVendorLiActive: function(elm, id) {
		var pm = this;
		if (elm !== false) {
			$(elm).addClass('active').siblings().removeClass('active');
		} else if (id > 0) {
			pm.getVendorElm(id).addClass('active').siblings().removeClass('active');
		} else {
			$('#vendors li').removeClass('active');
			return false;
		}
	},

	setModelLiActive: function(elm, id) {
		var pm = this;
		if (elm !== false) {
			$(elm).addClass('active').siblings().removeClass('active');
		} else if (id > 0 || id == 'all' || id == 'none') {
			pm.getModelElm(id).addClass('active').siblings().removeClass('active');
		} else {
			$('#models li').removeClass('active');
			return false;
		}
	},


	getVendorModels: function(s, c){
		var pm = this,
			success = function(resp, cache){
				app.log('getJSON', cache);
				cache = typeof cache !== 'boolean' ? false : !! cache;
				var html = '<li data-id="add_model" data-vendor_id="' + s + '" class="fixed add">создать модель</li><li data-id="all" class="fixed">все</li><li data-id="none" class="fixed">без модели</li>';
				if (resp.status === 1) {
					_.each(resp.data, function(v) {
						html += '<li data-id="' + v.id + '">' + v.name + '<span data-id="' + v.id + '" class="remove-item icon-container fr" title="Удалить модель '+ v.name +'">' +
								'<span class="ui-icon ui-icon-close"></span></span><span data-id="' + v.id + '" class="edit-item icon-container fr" title="Редактировать модель '+ v.name +'">' +
								'<span class="ui-icon ui-icon-pencil"></span></span></li>';
					});
				}
				if (!cache) {
					pm.cache.models[app.urls.getVendorModels + s] = resp;
				}
				pm.m.html(html);
				pm.updateControls();
				if (_.isFunction(c)) {
					c();
				}

			};

		if (s > 0) {
			app.showLoading(pm.m);
			pm.config.vendor_id = s;
			document.location.hash = s;
			if (pm.cache.models.hasOwnProperty(app.urls.getVendorModels + s)) {
				app.log('cache', pm.cache.models[app.urls.getVendorModels + s]);
				success(pm.cache.models[app.urls.getVendorModels + s], true);
			} else {
				app.log('getJSON', app.urls.getVendorModels + s);
				$.getJSON(app.urls.getVendorModels + s, success);
			}
		}
	},

	formModel: function(model_id, vendor_id){
		var pm = this;
		var v = {
				'name': '',
				'model_image': '',
				'cabinet_image': '',
				'solder_image': ''
			}
		var html = '';
		if(model_id == 0)
		{
			html = _.template(pm.templates.formModel, v);
			app.showPopup({html: html, c: function() {}});
		}else{
			_.each(pm.cache.models['/apanel/models/get_by_vendor/' + vendor_id].data, function(e, i) {
				if(e.id == model_id ){
					v = {
						'name': e.name,
						'model_image': e.image,
						'cabinet_image': e.cabinet_image,
						'solder_image': e.solder_image
					}
				}
			html = _.template(pm.templates.formModel, v);
			app.showPopup({html: html, c: function() {}});
			});
		}
		html = '';
		pm.v.find('li').each(function(i) {
			v = {
					'id':$(this).data('id'),
					'name':$(this).text(),
					'vendor_id': vendor_id
				  };
		html += _.template(pm.templates.selectVendors, v);
		});
		pm.pc.find('select#vendors').html(html);
		$('#model_image').uploadify({
		  'uploader'    : '/assets/js/apanel/uploadify-v2.1.4/uploadify.swf',
		  'script'      : '/apanel/models/images_upload',
		  'cancelImg'   : '/assets/js/apanel/uploadify-v2.1.4/cancel.png',
		  'folder'      : '/uploads',
		  'buttonText'  : 'browse'
		});
	},

	addModel: function(){

	},

	editModel: function(id){
		app.log('edit');
	},

	removeModel: function(id){
		app.log('remove');
	},

	searchParts: function(query, param, p){
		var pm = this;
		if ((param == 'model_name' || param == 'parts_code' || param == 'parts_name') && query.length > 0) {
			$('form[name="search_' + param + '"]').find('input.text').addClass('active').val(query);

			app.showLoading(pm.p);
			pm.pchbx.prop('checked', false);
			pm.config.page = p;
			document.location.hash = param + '/' + encodeURI(query) + '/' + p;
			$.ajax({
				url: '/apanel/parts/search',
				type: 'post',
				dataType: 'json',
				data: {
					query: query,
					param: param,
					page: parseInt(p, 10)
				},
				success: function(resp) {
					if (resp.status === 1) {
						if (!_.isEmpty(resp.data.parts)) {
							var html = '';
							pm.cache.parts = resp.data.parts;
							_.each(resp.data.parts, function(v, i){

								v.i = i;
								html += _.template(pm.templates.partTr, v);
							});
							pm.p.html(html);
							pm.p.parents('table').trigger('update');
							pm.updatePages(resp.pagination);
						}
					} else {
						pm.p.html(app.messages.noData);
						pm.updatePages(false);
					}
					pm.updateControls();
				}
			});
		}
	},

	getModelParts: function(s, p, c){
		var pm = this;
		if (s > 0 || s === 'none' || s === 'all') {
			app.showLoading(pm.p);
			pm.pchbx.prop('checked', false);
			pm.config.model_id = s;
			pm.config.page = p;
			document.location.hash = pm.config.vendor_id + '/' + s + '/' + p;
			$.ajax({
				url: '/apanel/parts/search',
				type: 'post',
				dataType: 'json',
				data: {
					page: parseInt(p, 10),
					vendor_id: pm.config.vendor_id,
					model_id: pm.config.model_id
				},
				success: function(resp) {
					if (resp.status === 1) {
						if (!_.isEmpty(resp.data.parts)) {
							var html = '';
							pm.cache.parts = resp.data.parts;
							_.each(resp.data.parts, function(v, i){

								v.i = i;
								html += _.template(pm.templates.partTr, v);
							});
							pm.p.html(html);
							pm.p.parents('table').trigger('update');
							pm.updatePages(resp.pagination);
						}
					} else {
						pm.p.html(app.messages.noData);
						pm.updatePages(false);
					}
					pm.updateControls();
					if (_.isFunction(c)) {
						c();
					}
				}
			});
		}
	},

	moveParts: function(m, p) {
		$.ajax({
			url: '/apanel/parts/move',
			type: 'post',
			dataType: 'json',
			data: {
				parts_id: p,
				model_id: m
			},
			success: function(resp) {
				if (resp.status === 1) {

				}
			}
		});
	},

	showMoveParts: function() {
		var html = '<select id="move-vendors" name="vendor">';
		var vnd;
		var val = '';
		var pm = partsManager;
		pm.v.find('li').each(function(i) {
			vnd = {
					'id':$(this).data('id'),
					'name':$(this).text()
				  };
			html += _.template( pm.templates.selectVendors, vnd);
		});
		html += '</select>' +
				'<select id="move-models" name="model">' +
				'</select>' +
				'<button name="move" type="submit">Переместить</button><br>';
		pm.p.find(':checked').each(function(i) {
			val += $(this).val() + ' ,';
		});
		html += val + '<input type="hidden" name="move_parts_id" value="' + val + '">'
		app.showPopup({html: html, c: function() {
			}});
		pm.getVendorModels(-1, pm.v.find('li').first().data('id'));
	},

	initControls: function() {
		this.c.find('button').attr('disabled', false);

		this.c.delegate('button.move', 'click',
				function(e) {
					e.preventDefault();
					partsManager.showMoveParts();
					app.log('move');
				}).delegate('button.remove', 'click',
				function(e) {
					e.preventDefault();
					app.log('remove');
				}).delegate('button.change-pn', 'click', function(e) {
			e.preventDefault();
			app.log('change-pn');
		});
	},

	updateControls: function(){

	},

	checkRow: function(tr) {
		var pm = this;
		$(tr).addClass('checked');
		pm.updateControls();
	},

	uncheckRow: function(tr) {
		var pm = this;
		$(tr).removeClass('checked');
		pm.updateControls();
	},

	updatePages: function(p){
		var pm = this;
		if (p !== false) {
			pm.pages.html(_.template(pm.templates.pagination, p)).show();
		} else {
			pm.pages.hide();
		}
	}
};

$(document).ready(function() {
	partsManager.init();
});