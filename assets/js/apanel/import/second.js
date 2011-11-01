$(document).ready(function () {
	var f = $('form'),
		m_sel = $('select[name="model_select"]', f),
		v_sel = $('select[name="vendors"]', f),
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

	$(f).submit(function(e){
		if (m_sel.val() <= 0 && m_inp.val().length <= 0) {
			e.preventDefault();
			m_sel.add(m_inp).addClass('validaton-error-field');
			$('#model_error').show();
			$.scrollTo(v_sel, 500);
		} else {
			m_sel.add(m_inp).removeClass('validaton-error-field');
			$('#model_error').hide();
		}
	});
});