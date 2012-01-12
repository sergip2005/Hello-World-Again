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
					'<td class="model" data-id="<%= model_id %>"><input type="checkbox" value="<%= id %>"></td>' +
					'<td class="vendor" data-id="<%= vendor_id %>"><%= vendor_name %></td>' +
					'<td><%= model_name %></td>' +
					'<td class="cct_ref"><%= cct_ref %></td>' +
					'<td class="ptype"><%= ptype %></td>' +
					'<td class="code"><%= code %></td>' +
					'<td class="num"><%= num %></td>' +
					'<td class="name"><%= name %></td>' +
					'<td class="name_rus"><%= name_rus %></td>' +
					'<td class="available"><%= (available ? "+" : "-") %></td>' +
					'<td class="price"><%= price > 0 ? price : "нет данных" %></td>' +
					'<td class="min_num"><%= min_num %></td>' +
				'</tr>',

		option: '<option value="<%= value %>"><%= name %></option>',
		selectedOption: '<option value="<%= value %>" selected="selected"><%= name %></option>',

		pagination: '<p>Показано <%= current.begin + " - " + current.end %> из <%= items %> элементов на <%= pages + (pages == 1 || pages%10 == 1 ? " странице" : " страницах") %></p>' +
				'<% if (pages > 1) { %>' +
				'<ul><% for (var i = 1; i <= pages; i += 1) { %>' +
					'<li><a href="#" data-page="<%= i - 1 %>"<%= (i - 1) == page ? " class=\'active\'" : "" %>><%= i %></a></li>' +
				'<% } %></ul>' +
				'<% } %>',

		formModel: '<table class="tal">' +
					'<tr><td><label for="model_name">Имя модели:</label></td>' +
						'<td><input type="text" data-id="<%= id %>" id="model_name" name="model_name" value="<%= name %>"></td></tr>' +
					'<tr><td><label for="vendors">Производитель:</label></td>' +
						'<td><select id="vendors_popup" name="vendors"></select></td></tr>' +
					'<tr><td><label for="model_image">Изображение модели:</label></td><td></td></tr>' +
						'<tr><td colspan="2" class="tac"><%=  model_image == "" ? "" : \'<a href="/assets/images/phones/\' + model_image + \'" target="_blank">Просмотреть</a>\'  %><input type="file" id="model_image" name="model_image" /></td></tr>' +
					'<tr><td><label for="solder_image">Изображение корпусных деталей:</label></td><td></td></tr>' +
						'<tr><td colspan="2" class="tac"><%=  solder_image == "" ? "" : \'<a href="/assets/images/phones/\' + solder_image + \'" target="_blank">Просмотреть</a>\'  %><input type="file" id="solder_image" name="solder_image" /></td></tr>' +
					'<tr><td><label for="cabinet_image">Изображение паечных деталей:</label></td><td></td></tr>' +
						'<tr><td colspan="2" class="tac"><%=  cabinet_image == "" ? "" : \'<a href="/assets/images/phones/\' + cabinet_image + \'" target="_blank">Просмотреть</a>\'  %><input type="file" id="cabinet_image" name="cabinet_image" /></td></tr>' +
					'<tr><td><button name="<%= type %>_model" <%=  type == "create" ? "disabled" : "" %>><%=  type == "create" ? "Создать" : "Изменить" %></button></td>' +
						'<td><button name="close">Отмена</button></td></tr></table>',

		changeCode: '<form>' +
				'<input type="hidden" name="vendor_id" value="<%= vendor_id %>">' +
				'<table>' +
					'<tr><td>Сменить парт-номер</td>' +
						'<td><input name="pn" type="text" value="<%= code %>" readonly="readonly" /></td></tr>' +
					'<tr><td>на</td>' +
						'<td><input type="text" name="change_pn" value="" id="change-pn" /></td></tr>' +
					'<tr><td class="tar" colspan="2"><input type="submit" value="Сохранить"></td></tr>' +
				'</table>' +
			'</form>',

		partInfo: '<form class="part-info tal">' +
				'<input type="hidden" name="id" value="<%= id %>">' +
				'<input type="hidden" name="part_id" value="<%= part_id %>">' +
				// available, cabinet_image, cct_ref, code, id, min_num, model_id,
				// model_name, name, name_rus, num, part_id, price, ptype, type, vendor_id, vendor_name
				'<h2>Информация о детали</h2><br>' +
				'<table>' +
					// if this part does not belong to phone
					'<tr>' +
						'<td>Парт-номер:</td><td><input type="text" value="<%= code %>" name="code" /></td>' +
						'<td>Старый парт-номер:</td><td><input type="text" value="<%= old_code %>" name="old_code" /></td>' +
					'</tr>' +
					'<tr>' +
						'<td>Производитель:</td><td><%= vendor_name %></td>' +
						'<td>Модель:</td><td><%= model_name %></td>' +
					'</tr>' +
					'<tr>' +
						'<td>Вид детали:</td><td>' +
							'<select name="type">' +
								'<option value="s"<%= type == "s" ? \' selected="selected"\' : \'\' %>>паечная</option>' +
								'<option value="c"<%= type == "c" ? \' selected="selected"\' : \'\' %>>корпусная</option>' +
							'</select>' +
						'</td>' +
						'<td>Тип детали:</td><td><input type="text" name="ptype" value="<%= ptype %>" /></td>' +
					'</tr>' +
					'<tr><td>Ориг. имя:</td><td colspan="3"><input type="text" class="w450" name="name" value="<%= name %>" /></td></tr>' +
					'<tr><td>Имя:</td><td colspan="3"><input type="text" class="w450" name="name_rus" value="<%= name_rus %>" /></td></tr>' +
					'<tr>' +
						'<td>Цена:</td><td class="tal"><input class="w50" type="text" name="price" value="<%= price %>" /></td>' +
						'<td><input class="w50" type="text" name="price1" value="<%= price %>" /></td>' +
						'<td><input class="w50" type="text" name="price2" value="<%= price %>" /></td>' +
					'</tr>' +
					'<tr>' +
						'<td>Есть в наличии:</td><td><input type="checkbox" name="available" value="1" <%= available ? \' checked="checked"\' : \'\' %> /></td>' +
						'<td>Мин. кол-во для заказа:</td><td><input type="text" class="w50" name="min_num" value="<%= min_num %>" /></td>' +
					'</tr>' +
					'<tr>' +
						'<td>Позиция в сборке:</td><td><input type="text" name="cct_ref" value="<%= cct_ref %>" /></td>' +
						'<td>Используется в сборке:</td><td><input type="text" class="w50" name="num" value="<%= num %>" /></td>' +
					'</tr>' +

					'<tr><td class="tar" colspan="4"><input type="submit" value="Сохранить"></td></tr>' +
				'</table>' +
			'</form>',

		moveParts: '<form>' +
				'<table>' +
					'<tr><td class="tal">Выберите модель телефона,<br>в которую будут перенесены запчасти:</td></tr>' +
					'<tr><td class="tal"><select id="move-vendors" name="vendor"><%= vendors %></select></td></tr>' +
					'<tr><td class="tal"><select id="move-models" name="model"></select></td></tr>' +
					'<input type="hidden" name="move_parts_id" value="<%= ids %>">'+
					'<tr><td class="tar"><button name="move" type="submit">Переместить</button></td></tr>' +
				'</table>' +
			'</form>'
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
		this.hasUploadQueue = [];

		var pm = this;

		// vendors list
		pm.v.delegate('li', 'click', function() {
			pm.setVendorLiActive(this, false);
			pm.getVendorModels($(this).data('id'));
			pm.s.find('input.text').removeClass('active');
		});

		//popup form
		 pm.pc.delegate('select[name="vendor"]', 'change', function(e) {
			pm.getVendorModels(-1, $('select[name="vendor"] option:selected').val());
		 }).delegate('button[name="move"]', 'click', function(e) {
			pm.moveParts(pm.pc.find('#move-models :selected').val(), pm.pc.find('input[name="move_parts_id"]').val())
		 }).delegate('button[name="create_model"]', 'click', function(e) {
			pm.manageModel('save', '', $('#vendors_popup :selected').val());
		 }).delegate('button[name="edit_model"]', 'click', function(e) {
			pm.manageModel('save', $('#model_name').data('id'), $('#vendors_popup :selected').val());
			$('#model_image').uploadifyUpload();
			$('#cabinet_image').uploadifyUpload();
			$('#solder_image').uploadifyUpload();
			pm.clearCache('models', $('#vendors_popup :selected').val());
			pm.getVendorModels($('#vendors_popup :selected').val());
		 }).delegate('button[name="close"]', 'click', function(e) {
			app.popup.add(app.splash).hide();
		 }).delegate('input[name="model_name"]', 'keyup', function(e) {
			pm.pc.find('button[name="create_model"]').prop('disabled', false);
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
			if (confirm('Вы действительно хотите удалить модель ' + $(this).parent().text() + '?' )) {
				pm.manageModel('remove', $(this).data('id'), '');
			}
		});
		pm.m.delegate('li.add', 'click', function(e) {
			e.stopPropagation();
			pm.formModel(0, $(this).data('vendor_id'));
		});

		// init sortables
		pm.p.parents('table').tablesorter({ headers: { 0: { sorter: false} }, widgets: ['zebra', 'repeatHeaders'] });

		// rows clicks
		pm.p.delegate('tr :checkbox', 'click', function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).parents('tr').trigger('click');
		}).delegate('tr', 'click', function(){
			var tr = $(this);
			if (tr.hasClass('checked')) {
				pm.uncheckRow(tr);
			} else {
				pm.checkRow(tr);
			}
			pm.updateControls();
		}).delegate('tr', 'dblclick', function(e){
			pm.showPartInfo($(this).data('id'));
		});

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
		pm.pchbx.click(function(){
			$(this).parents('table').find(':checkbox').attr('checked', this.checked);
			pm.updateControls();
		});

		pm.pages.delegate('a', 'click', function(e){
			e.preventDefault();
			var a = $(this),
				h = location.hash.substr(1).split('/'),
				p = a.data('page');

			h[h.length - 1] = p + 1;
			location.hash = h.join('/');

			pm.initLocationHash();
		});

		pm.initControls();

		//if there is path in hash -> load it
		pm.initLocationHash();
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
		var pm = this,
			part = pm.getPartCache(i);
		app.log(i, part);
		app.showPopup({html: _.template(partsManager.templates.partInfo, part), c: function(){

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
			pm.config.model_id = 0;
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

	getPartCache: function(id){
		var pm = this;
		if (id <= 0) return false;
		if (!pm.cache.parts.hasOwnProperty(id)) return false;// no cache
		//if ((new Date().getTime() - pm.cache.parts[id].cache_time) > 300) return false;// cache expired
		return pm.cache.parts[id];
	},

	clearCache: function(object, id){
		if(object == 'models'){
			delete this.cache.models['/apanel/models/get_by_vendor/' + id];
		}
	},

	formModel: function(model_id, vendor_id){
		var pm = this,
			v = {
				id: '',
				name: '',
				model_image: '',
				cabinet_image: '',
				solder_image: '',
				type: 'create'
			},
			html = '',
			model_name = '';
		if(model_id == 0){
			html = _.template(pm.templates.formModel, v);
			app.showPopup({html: html, c: function() {}});
		}else{
			_.each(pm.cache.models['/apanel/models/get_by_vendor/' + vendor_id].data, function(e, i) {
				if(e.id == model_id ){
					v = {
						id: e.id,
						name: e.name,
						model_image: e.image,
						cabinet_image: e.cabinet_image,
						solder_image: e.solder_image,
						type: 'edit'
					};
					model_name = e.name;
				}
			html = _.template(pm.templates.formModel, v);
			app.showPopup({html: html, c: function() {}});
			});
		}
		html = '';
		pm.v.find('li').each(function(i){
			v = {
					value: $(this).data('id'),
					name: $(this).text()
				};
			html += _.template(v.value == vendor_id ? pm.templates.selectedOption : pm.templates.option, v);
		});
		pm.pc.find('#vendors_popup').html(html);

		$('#model_image').uploadify(pm.getUploadifySettings(model_name, model_id, 'image'));
		$('#solder_image').uploadify(pm.getUploadifySettings(model_name, model_id, 'solder_'));
		$('#cabinet_image').uploadify(pm.getUploadifySettings(model_name, model_id, 'cabinet_'));
	},

	getUploadifySettings: function(model_name, model_id, type ){
		var v = $('#vendors_popup :selected').val();
		var pm = partsManager;
		return {
			uploader	: '/assets/js/apanel/uploadify-v2.1.4/uploadify.swf',
			script		: '../../uploadify.php',
			cancelImg	: '/assets/js/apanel/uploadify-v2.1.4/cancel.png',
			folder		: '/assets/images/phones/' +  model_name,
			scriptData	: {'modelId': model_id, 'img': type},
			buttonText	: 'browse',
			onOpen		: function(){ pm.hasUploadQueue.push(1) },
			onComplete	: function(){
					pm.hasUploadQueue.pop();
					if(!pm.hasUploadQueue.length){
						pm.clearCache('models', v);
						pm.getVendorModels(v);
						app.popup.add(app.splash).hide();
					}
				}
		};
	},

	manageModel: function(url, m_id, v_id){
		var model = $('#model_name').val();
		$.ajax({
			url: '/apanel/models/' + url,
			type: 'post',
			dataType: 'json',
			data: {
				id: m_id,
				vendor_id: v_id,
				name: model
			},
			success: function(resp) {
				if (resp.status === 1) {
					app.showMessage({html: resp.message});
					var pm = partsManager;
					if(m_id == ''){
						var id = resp.item.id,
							m = resp.item.model,
							m_i = $('#model_image'),
							s_i = $('#solder_image'),
							c_i = $('#cabinet_image'),
							v = $('#vendors_popup :selected').val();
						m_i.uploadifySettings('scriptData', {'modelId': id});
						s_i.uploadifySettings('scriptData', {'modelId': id});
						c_i.uploadifySettings('scriptData', {'modelId': id});
						m_i.uploadifySettings('folder' , '/assets/images/phones/' + m);
						s_i.uploadifySettings('folder' , '/assets/images/phones/' + m);
						c_i.uploadifySettings('folder', '/assets/images/phones/' + m);
						m_i.uploadifyUpload();
						c_i.uploadifyUpload();
						s_i.uploadifyUpload();
						pm.clearCache('models', v);
						pm.getVendorModels(v);
					}
					if(url = 'remove'){
						v = $('#vendors li.active').data('id');
						pm.clearCache('models', v);
						pm.getVendorModels(v);
					}
				} else {
					app.showMessage({html: resp.error});
				}
			}
		});
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
							var html = '', time = new Date().getTime();
							_.each(resp.data.parts, function(v, i){
								v.i = i;
								html += _.template(pm.templates.partTr, v);
								i.cache_time = time;
								pm.cache.parts[i.id] = i;
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
							var html = '', time = new Date().getTime();
							_.each(resp.data.parts, function(v, i){
								v.i = i;
								html += _.template(pm.templates.partTr, v);
								v.cache_time = time;
								pm.cache.parts[v.id] = v;
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
			success: function(resp){
				if (resp.status === 1) {

				}
			}
		});
	},

	showMoveParts: function(){
		var html = '',
			pm = partsManager;

		pm.v.find('li').each(function(i){
			var li = $(this),
				v = {
					value: li.data('id'),
					name: li.text()
				};
			html += _.template(pm.config.vendor_id == v.value ? pm.templates.selectedOption : pm.templates.option, v);
		});

		app.showPopup({
			html: _.template(pm.templates.moveParts,
					{
						vendors: html,
						ids: pm.getChecked().ids.join('|')
					}),
			c: function(){
				pm.getVendorModels(-1, pm.v.find('li').first().data('id'));
			}
		});
	},

	showChangePartCode: function(){
		var pm = this, c = pm.getChecked();

		if (c.num !== 1) {
			alert('Нужно выбрать только 1 деталь');
			return;
		}

		var pn = $(c.rows[0]).find('td.code').text();
		var v = pm.config.vendor_id;

		app.showPopup({html: _.template(pm.templates.changeCode, {code: pn, vendor_id: v}), c: function(){
			var f = $(this).find('form');
			f.submit(function(e){
				e.preventDefault();

				if ($.trim($('#change-pn', f).val()) == '') {
					alert('Введите новый парт-номер');
					return;
				}

				$.ajax({
					url: app.urls.changePN,
					data: f.serialize(),
					type: 'post',
					success: function(resp){

					}
				});
			});
		}});
	},

	getChecked: function(){
		var pm = this, r = pm.p.find(':checkbox:checked'), ids = [];
		r.each(function(){
			ids.push(this.value);
		});
		return {
			rows: r.parents('tr').toArray(),
			ids: ids,
			num: r.size()
		};
	},

	initControls: function(){
		var pm = this;

		pm.c.delegate('button.move', 'click',
				function(e){
					e.preventDefault();
					pm.showMoveParts();
				}).delegate('button.remove', 'click',
				function(e){
					e.preventDefault();
					app.log('remove');
				}).delegate('button.change-pn', 'click', function(e){
					e.preventDefault();
					pm.showChangePartCode();
		});

		pm.updateControls();
	},

	updateControls: function(){
		var pm = this,
			n = pm.getChecked();
		if (n.num == 1) {
			app.log('updateControls', 1);
			this.c.find('button').attr('disabled', false);
		} else if (n.num > 1) {
			app.log('updateControls', '> 1');
			this.c.find('button').attr('disabled', false)
				.filter('.change-pn').attr('disabled', true);
		} else {// n == 0
			app.log('updateControls', 0);
			this.c.find('button').attr('disabled', true);
		}
	},

	checkRow: function(tr) {
		var pm = this;
		$(tr).addClass('checked').find(':checkbox').prop('checked', true);
	},

	uncheckRow: function(tr) {
		var pm = this;
		$(tr).removeClass('checked').find(':checkbox').prop('checked', false);
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
})