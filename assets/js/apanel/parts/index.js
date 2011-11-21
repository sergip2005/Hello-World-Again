$(document).ready(function(){
	var config = {},
		cache = {
			vendors: {},
			models: {}
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
							_.each(resp.data.parts, function(i, v){
								console.log(i, v);
							});
						}
					}
				}
			});
		}
	});
});
