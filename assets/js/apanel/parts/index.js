$(document).ready(function(){
	var config = {},
		cache = {
			vendors: {},
			models: {}
		},
		templates = {
			partTr:
				'<tr data-id="<%= id %>">' +
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
		v = $('#vendors'),
		m = $('#models'),
		p = $('#parts');

	v.delegate('li', 'click', function(){// vendors list
			var s = $(this).data('id');
			if (s > 0) {
				app.showLoading(m);
				$(this).addClass('active').siblings().removeClass('active');
				config.vendor_id = s;
				$.getJSON(app.urls.getVendorModels + s, function(resp){
					var html = '<li data-id="all" class="fixed">все</li><li data-id="none" class="fixed">без модели</li>';
					if (resp.status === 1) {
						_.each(resp.data, function(v) {
							html += '<li data-id="' + v.id + '">' + v.name + '</li>';
						});
					}
					m.html(html);
				});
			}
	});

	m.delegate('li', 'click', function(e){// dynamic models list
		e.preventDefault();
		var s = $(this).data('id');
		$(this).addClass('active').siblings().removeClass('active');
		if (s > 0) {
			app.showLoading(p);
			config.model_id = s;
			$.ajax({
				url: '/apanel/parts/search',
				type: 'post',
				dataType: 'json',
				data: {
					vendor_id: config.vendor_id,
					model_id: config.model_id
				},
				success: function(resp){
					if (resp.status === 1) {
						if (!_.isEmpty(resp.data.parts)) {
							var html = '';
							_.each(resp.data.parts, function(v){
								html += _.template(templates.partTr, v);
							});
							p.html(html);
							p.parents('table').trigger("update");
						}
					} else {
						p.html(app.messages.noData);
					}
				}
			});
		}
	});

	p.parents('table').tablesorter();
});
