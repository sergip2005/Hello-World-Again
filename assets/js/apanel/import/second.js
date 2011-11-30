app.messages.importpage = {
	no_data_selected: 'Не выбрано ни одного листа для сохранения',
	no_model_selected: 'Не выбрана модель телефона',
	no_code_field: 'В листе <strong><%= sheet %></strong> не задано ни одного поля со значением "Парт. номер"',
	data_tpl_saved: 'Шаблон <%= name %> успешно сохранён',
	data_tpl_removed: 'Шаблон <%= name %> удалён',
	data_tpl_conirm_remove: 'Вы действительно хотите удалить шаблон "<%= name %>"',
	rev_date: 'Дата последнего импорта:<br><%= date %>'
};

$(document).ready(function () {
	var f = $('form'),
		m_sel = $('select[name="model_select"]', f),
		v_sel = $('select[name="vendors"]', f),
		m_inp = $('input[name="model_input"]');

	// bind update of models on vendor select
	v_sel.bind({
		change: function(){
			var s = $(this),
				sv = s.val();
			if (sv > 0) {
				m_sel.attr('disabled', true);
				$.getJSON(app.urls.getVendorModels + sv, function(resp){
					var o = _.map(resp.data, function(v, k){
						return '<option value="' + v.id + '" data-rev-desc="' + v.rev_desc + '" data-rev-num="' + v.rev_num + '" data-rev-date="' + v.rev_date + '">' + v.name + '</option>';
					});
					m_sel.html('<option value="0" selected="selected"> - </option>' + o.join()).attr('disabled', false).trigger('change');
				});
			}
		}
	});

	m_sel.bind({
		change: function(){
			var s = $(this),
				os = s.contents('option:selected');
			if (s.val() != 0) {
				if (os.data('rev-num') != '') $('#rev-num').show().text('Текущее: ' + os.data('rev-num'));
				if (os.data('rev-desc') != '') $('#rev-desc').show().text('Текущее: ' + os.data('rev-desc'));
				if (os.data('rev-date') != '0000-00-00 00:00:00') $('#rev-date').show().text(_.template(app.messages.importpage.rev_date, {date: os.data('rev-date')}));
			} else {
				$('#rev-num, #rev-desc, #rev-date').hide();
			}
		}
	});

	// load \ save templates
	$('#sheets')
		.delegate('div.data-template span.ui-icon-disk', 'click', function(){// save template
			var b = $(this),
				si = b.parents('div.sheet-info'),
				s = si.find('select[name="' + si.attr('id') + '_cols_values[]"]'),
				v = [],
				n = $('select[name="vendors"] option:selected').text() + ' | ' + si.siblings('label').text();

				n = prompt('Введите название шаблона:', n);
				if (n == null) return false;

				s.each(function(){
					v.push($(this).val());
				});
				$.ajax({
					url: app.urls.saveDataTpl,
					type: 'post',
					data: {
						name: n,
						values: v.join('||')
					},
					success: function(resp){
						app.showMessage({html: _.template(app.messages.importpage.data_tpl_saved, {name: n})});
						$('#details_form').find('div.data-template select').replaceWith(resp);
					}
				});
		}).delegate('div.data-template span.ui-icon-close', 'click', function(){// remove template
			var b = $(this),
				sel = b.parents('div.data-template').contents('select'),
				n = sel.find('option:selected').text(),
				id = parseInt(sel.val());

				if (id <= 0) return false;

				if (confirm(_.template(app.messages.importpage.data_tpl_conirm_remove, {name: n}))) {
					$.ajax({
						url: app.urls.removeDataTpl + id,
						success: function(resp){
							app.showMessage({html: _.template(app.messages.importpage.data_tpl_removed, {name: n})});
							$('#details_form').find('div.data-template select').replaceWith(resp);
						}
					});
				}
		}).delegate('div.data-template span.ui-icon-extlink', 'click', function(){
			var b = $(this),
				si = b.parents('div.sheet-info'),
				s = si.find('select[name="' + si.attr('id') + '_cols_values[]"]'),
				data_s = b.parents('div.data-template').contents('select');

				if (data_s.val() <= 0) return false;

				var v = data_s.find('option:selected').data('values').split('||');

				$.each(s, function(ind, elm){
					if (typeof v[ind] !== 'undefined') {
						$(elm).val(v[ind]);
					}
				});
				
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