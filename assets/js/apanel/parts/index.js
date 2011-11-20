$(document).ready(function(){
	var v = $('#vendors'),
		m = $('#models'),
		sp = $('#show_parts');

	v.bind({
		change: function(){
			var s = $(this),
				sv = s.val();
			if (sv > 0) {
				m.attr('disabled', true);
				$.getJSON(app.urls.getVendorModels + sv, function(resp){
					m.html('<option value="0" selected="selected"> - </option>' + resp.join()).attr('disabled', false);
					sp.show();
				});
			}
		}
	});
});
