app.messages.importpage = {
	no_data_selected: 'Не выбрано ни одного листа для сохранения',
	no_model_selected: 'Не выбрана модель телефона',
	no_code_field: 'В листе <strong><%= sheet %></strong> не задано ни одного поля со значением "Парт. номер"'
};

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
				m_sel.attr('disabled', true);
				$.getJSON(app.urls.getVendorModels + sv, function(resp){
					m_sel.html('<option value="0" selected="selected"> - </option>' + resp.join()).attr('disabled', false);
				});
			}
		}
	});

	$(f).submit(function(e){
		var error_str = [];

		// check if phone model selected
		/*
		if (m_sel.val() <= 0 && m_inp.val().length <= 0) {
			e.preventDefault();
			m_sel.add(m_inp).addClass('validaton-error-field');
			error_str.push(app.messages.importpage.no_model_selected);
		} else {
			m_sel.add(m_inp).removeClass('validaton-error-field');
		}*/

		// check sheets required fields
		var no_sheets = true, no_code;
		$('select.sheet-type-select').each(function(){
			var s = $(this), si;
			if (s.val() != 0) {
				no_sheets = false;
				no_code = true;

				si = s.parents('div.sheet-info');
				si.find('select.col-values-select').each(function(){
					if ($(this).val() == 'code') {
						no_code = false;
					}
				});
				if (no_code) error_str.push(_.template(app.messages.importpage.no_code_field, {sheet: si.siblings('label').text()}));
			}
		});

		// agregate all messages and show if any error occured by the validation end
		if (no_sheets) error_str.push(app.messages.importpage.no_data_selected);
		if (error_str.length > 0)  {
			e.preventDefault();
			app.showMessage({html: error_str.join('<hr>')});
		}
	});
});