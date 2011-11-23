var partsManager = {

	config: {},

	cache: {
		vendors: {},
		models: {}
	},

	templates: {
		partTr:
			'<tr data-id="<%= id %>" class="<%= i % 2 ? \'odd\' : \'even\' %>">' +
				'<td class="check"><input type="checkbox" value="<%= id %>"></td>' +
				'<td class="vname"><%= vendor_name %></td>' +
				'<td class="mname"><%= model_name %></td>' +
				'<td class="cct_ref"><%= cct_ref %></td>' +
				'<td class="code"><%= code %></td>' +
				'<td class="num"><%= num %></td>' +
				'<td class="name"><%= name %></td>' +
				'<td class="name_rus"><%= name_rus %></td>' +
				'<td class="available"><%= (available ? "+" : "-") %></td>' +
				'<td class="price"><%= price %></td>' +
				'<td class="min_num"><%= min_num %></td>' +
				'<td class="ptype"><%= ptype %></td>' +
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
		pm.p.parents('table').tablesorter({ headers: { 0: { sorter: false} } });

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
		});

		// init check all
		pm.pchbx.click(function(e){
			if (this.checked === true) {
				$(this).parents('table').find(':checkbox').attr('checked', true);
			} else {
				$(this).parents('table').find(':checkbox').attr('checked', false);
			}
		});
	},

	getVendorModels: function(s){
		var pm = this;
		if (s > 0) {
			app.showLoading(pm.m);
			pm.config.vendor_id = s;
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
							pm.p.parents('table').trigger("update");
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
