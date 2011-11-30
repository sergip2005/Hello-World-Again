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
	// pages handlers
	}).delegate('ul.pages a', 'click', function(e){
		e.preventDefault();
		var a = $(this);
		if (a.hasClass('active')) return false;

		$.ajax({
			url: a.attr('href'),
			success: function(resp){
				var cont = a.parents('div.sheet-import-data');
				cont.replaceWith($(resp).find('#' + cont.attr('id')));
			}
		});
	// forms handlers
	}).delegate('div.sheet-import-data form, div.to-remove form', 'submit', function(e){
		e.preventDefault();
		var f = $(this);
		$.ajax({
			url: f.attr('action'),
			data: decodeURIComponent($(this).serialize()),
			type: 'post',
			dataType: 'json',
			success: function(resp){
				app.log(resp);
				if (resp.status == 1 && resp.refresh == 1) {
					document.location.reload();
				}
			}
		});
	});

	$('div.to-remove table').tablesorter({ headers: { 0: { sorter: false} }, widgets: ['zebra', 'repeatHeaders'] });
});