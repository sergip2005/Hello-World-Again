var partsManager = {

	config: {
		vendor_id: 0,
		model_id: 0
	},

	cache: {
		vendors: {},
		models: {}
	},

	templates: {
		partTr:
			'<tr data-id="<%= id %>" class="<%= i % 2 ? \'even\' : \'odd\' %>">' +
				'<td><input type="checkbox" value="<%= id %>"></td>' +
				'<td><%= vendor_name %></td>' +
				'<td><%= model_name %></td>' +
				'<td><%= cct_ref %></td>' +
				'<td><%= code %></td>' +
				'<td><%= num %></td>' +
				'<td><%= name %></td>' +
				'<td><%= name_rus %></td>' +
				'<td><%= (available ? "+" : "-") %></td>' +
				'<td><%= price %></td>' +
				'<td><%= min_num %></td>' +
				'<td><%= ptype %></td>' +
			'</tr>'
	},

	init: function(){
		this.v = $('#vendors'),
		this.m = $('#models'),
		this.p = $('#parts'),
		this.pchbx = this.p.parent().find(':checkbox.check-all'),
		this.c = $('#controls');

		var pm = this;

		// vendors list
		pm.v.delegate('li', 'click', function(){
			$(this).addClass('active').siblings().removeClass('active');
			pm.getVendorModels($(this).data('id'));
		});

		// dynamic models list
		pm.m.delegate('li', 'click', function(e){
			$(this).addClass('active').siblings().removeClass('active');
			pm.getModelParts($(this).data('id'));
		});

		// init sortables
		pm.p.parents('table').tablesorter({ headers: { 0: { sorter: false}, widgets: ['zebra', 'repeatHeaders']} });

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
		}).delegate('tr', 'dblclick', function(e){
				var i = $(this);
				pm.showPartInfo(i.data('id'));
				app.log('dblclicked', i);
			});

		// init check all
		pm.pchbx.click(function(e){
			if (this.checked === true) {
				$(this).parents('table').find(':checkbox').attr('checked', true);
			} else {
				$(this).parents('table').find(':checkbox').attr('checked', false);
			}
		});

		//if there is path in hash -> load it
		pm.initLocationHash();
	},

	/**
	 * loads url saved in hash
	 */
	initLocationHash: function(){
		var h = document.location.hash.replace('#', ''),
			pm = this;
		if (h !== '') {
			h = h.split('/');
			if (h.length > 0) {
				if (parseInt(h[0], 10) > 0) {
					pm.getVendorModels(parseInt(h[0], 10));
				}
				if (parseInt(h[1], 10) > 0 || h[1] == 'all' || h[1] == 'none') {
					pm.getModelParts(h[1]);
				}
			}
		}
	},

	showPartInfo: function(i){
		app.showPopup({html: i, c: function(){}});
	},

	getVendorModels: function(s){
		var pm = this;
		if (s > 0) {
			app.showLoading(pm.m);
			pm.config.vendor_id = s;
			document.location.hash = s;
			$.getJSON(app.urls.getVendorModels + s, function(resp){
				var html = '<li data-id="all" class="fixed">все</li><li data-id="none" class="fixed">без модели</li>';
				if (resp.status === 1) {
					_.each(resp.data, function(v) {
						html += '<li data-id="' + v.id + '">' + v.name + '</li>';
					});
				}
				pm.m.html(html);
			});
		}
	},

	getModelParts: function(s){
		var pm = this;
		if (s > 0 || s === 'none' || s === 'all') {
			app.showLoading(pm.p);
			pm.pchbx.prop('checked', false);
			pm.config.model_id = s;
			document.location.hash = pm.config.vendor_id + '/' + s;
			$.ajax({
				url: '/apanel/parts/search',
				type: 'post',
				dataType: 'json',
				data: {
					vendor_id: pm.config.vendor_id,
					model_id: pm.config.model_id
				},
				success: function(resp){
					if (resp.status === 1) {
						if (!_.isEmpty(resp.data.parts)) {
							var html = '';
							_.each(resp.data.parts, function(v, i){
								v.i = i;
								html += _.template(pm.templates.partTr, v);
							});
							pm.p.html(html);
							pm.p.parents('table').trigger('update');
						}
					} else {
						pm.p.html(app.messages.noData);
					}
				}
			});
		}
	},

	updateControls: function(){},

	checkRow: function(tr){
		var pm = this;
		$(tr).addClass('checked');
		pm.updateControls();
	},

	uncheckRow: function(tr){
		var pm = this;
		$(tr).removeClass('checked');
		pm.updateControls();
	}
};

$(document).ready(function(){
	partsManager.init();
});
