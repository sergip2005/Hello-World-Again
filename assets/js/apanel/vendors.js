$(document).ready(function () {
	var sil = $('#selected-items-list'),
		sii = $('#selected-item-info'),

		/** */
		createVendor = function (elm) {
			var a = $(elm).parent(),
				name = a.contents('span.name'),
				title = $.trim(name.text()),
				pc = $(_.template(
						app.templates.vendors.edit,
						{
							title: '',
							id: 0
						}
					)),
				cr_html = $('<li></li>').html(pc);

			pc.contents('button[name="save"]').click(function(e){
						e.preventDefault();
						if (pc.get(0).name.value.length > 0) {
							$.ajax({
								url: app.urls.saveVendor,
								type: 'post',
								dataType: 'json',
								data: pc.serialize(),
								success: function(resp){
									if (resp.status === 1) {
										app.showMessage({html: resp.message});
										cr_html.replaceWith(_.template(app.templates.vendors.item, {items: [resp.item]}));
										$(elm).show();
									} else {
										app.showMessage({html: resp.error});
									}
								}
							});
						}
					}).end()
				.contents('button[name="cancel"]').click(function(e){
						e.preventDefault();
						cr_html.remove();
						$(elm).show();
					});

			$(elm).hide();
			$('#vendors').prepend(cr_html);
			pc.contents('input[name="name"]').focus().select();
		},

		/** */
		editVendor = function (elm) {
			var a = $(elm).parent(),
				name = a.contents('span.name'),
				title = $.trim(name.text()),
				pc = $(_.template(
						app.templates.vendors.edit,
						{
							title: title,
							id: a.attr('id').substr(1)
						}
					));

			pc.contents('button[name="save"]').click(function(e){
						e.preventDefault();
						$.ajax({
							url: app.urls.saveVendor,
							type: 'post',
							dataType: 'json',
							data: pc.serialize(),
							success: function(resp){
								if (resp.status === 1) {
									app.showMessage({html: resp.message});
									name.html(resp.item.name);
									$(elm).show();
								} else {
									app.showMessage({html: resp.error});
								}
							}
						});
					}).end()
				.contents('button[name="cancel"]').click(function(e){
						e.preventDefault();
						name.html(title);
						$(elm).show();
					});

			$(elm).hide();
			name.html(pc);
			pc.contents('input[name="name"]').focus().select();
		},
		set_visibleVendor = function(elm){
			var a = $(elm).parent();
            $.ajax({
					url: app.urls.set_visibleVendor,
					type: 'post',
					dataType: 'json',
					data: {
						id: a.attr('id').substr(1),
                        show: $(elm).val()
					},
					success: function(resp){
						if (resp.status === 1) {
                            $(elm).val() == 0 ? $(elm).val( 1) : $(elm).val(0)
						} else {

						}
					}
				});
		},

		/** */
		removeVendor = function (elm) {
			var a = $(elm).parent();
			if (confirm('Вы действительно хотите удалить поставщика \'' + $.trim(a.contents('span.name').text()) + '\'')) {
				$.ajax({
					url: app.urls.removeVendor,
					type: 'post',
					dataType: 'json',
					data: {
						id: a.attr('id').substr(1)
					},
					success: function(resp){
						if (resp.status === 1) {
							app.showMessage({html: resp.message});
							// remove item li
							a.parent().remove();
						} else {
							app.showMessage({html: resp.error});
						}
					}
				});
			}
		};

	// INIT CATS NAVIGATION
	$('#vendors')
		/** VIEW ITEM
		   ----------------------------------------------*/
		.delegate('span.remove-item', 'click', function (e) {
			e.stopPropagation();e.preventDefault();
			removeVendor(this);
		/** EDIT CATEGORY
		   ----------------------------------------------*/
		}).delegate('span.edit-item', 'click', function(e){
			e.stopPropagation();e.preventDefault();
			editVendor(this);
		/** CREATE SUBCAT
		   ----------------------------------------------*/
		}).delegate('input.vendor-show', 'click', function (e) {
            set_visibleVendor(this);
        });

	// INIT UNIQUE BUTTONS ACTIONS
	/** CREATE TOP CAT
	   ----------------------------------------------*/
	$('#create-vendor').click(function(e){
		e.preventDefault();
		createVendor(this);
	});
});