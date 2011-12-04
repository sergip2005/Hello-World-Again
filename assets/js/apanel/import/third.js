$(document).ready(function () {

	// form events
	var m_sel = $('select[name="model_select"]'),
		v_sel = $('select[name="vendors"]'),
		m_inp = $('input[name="model_input"]');

	v_sel.bind({
		change: function(){
			var s = $(this),
				sv = s.val();
			if (sv > 0) {
				$.getJSON(app.urls.getVendorModels + sv, function(resp){
					m_sel.html('<option value="0" selected="selected"> - </option>' + resp.join());
				});
			}
		}
	});

	$('body').delegate(':checkbox.check-all', 'click', function(e){
		if (this.checked === true) {
			$(this).parents('table').find(':checkbox').attr('checked', true);
		} else {
			$(this).parents('table').find(':checkbox').attr('checked', false);
		}
	});

	$('div.to-remove table').tablesorter({ headers: { 0: { sorter: false} }, widgets: ['zebra', 'repeatHeaders'] });

	// if this is not price import
	if ($(':checkbox.check-all').size() > 0) {
		$('input[type="submit"]').prop('disabled', false);
	}
});