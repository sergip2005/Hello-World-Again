$(document).ready(function () {
	var sil = $('#selected-items-list'),
		sii = $('#selected-item-info'),

		/** */
		createRegion = function (elm) {
			var a = $(elm).parent(),
				name = a.contents('span.name'),
				title = $.trim(name.text()),
				pc = $(_.template(
						app.templates.regions.edit,
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
								url: app.urls.saveRegion,
								type: 'post',
								dataType: 'json',
								data: pc.serialize(),
								success: function(resp){
									if (resp.status === 1) {
										app.showMessage({html: resp.message});
										cr_html.replaceWith(_.template(app.templates.regions.item, {items: [resp.item]}));
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
			$('#regions').prepend(cr_html);
			pc.contents('input[name="name"]').focus().select();
		},

		/** */
		editRegion = function (elm) {
			$(elm).parent().find('.reg-default').hide();
			var a = $(elm).parent(),
				name = a.contents('span.name'),
				title = $.trim(name.text()),
				pc = $(_.template(
						app.templates.regions.edit,
						{
							title: title,
							id: a.attr('id').substr(1)
						}
					));

			pc.contents('button[name="save"]').click(function(e){
						e.preventDefault();
						$.ajax({
							url: app.urls.saveRegion,
							type: 'post',
							dataType: 'json',
							data: pc.serialize(),
							success: function(resp){
								if (resp.status === 1) {
									app.showMessage({html: resp.message});
									name.html(resp.item.name);
									$(elm).show();
									$(elm).parent().find('.reg-default').show();
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
						$(elm).parent().find('.reg-default').show();

					});

			$(elm).hide();
			name.html(pc);
			pc.contents('input[name="name"]').focus().select();
		},
		set_defaultRegion = function(elm){

			$.ajax({
					url: app.urls.set_defaultRegion,
					type: 'post',
					dataType: 'json',
					data: {
						id: $(elm).val()
                       	},
					success: function(resp){
						if (resp.status === 1) {
							var temp = $(elm).parent();
							$('#regions li span span:contains("по умолчанию")').html(' ( <input type="radio" class="region-default" name="default" value="' + $('#regions li>span:contains("по умолчанию")').attr('id').substr(1) + '"> )');
							 temp.html(' ( по умолчанию <input type="radio" class="region-default" name="default" value="' + $(elm).val() + '" checked> )');

						} else {

						}
					}
				});
			
		},

		/** */
		removeRegion = function (elm) {
			var a = $(elm).parent();
			if (confirm('Вы действительно хотите удалить регион \'' + $.trim(a.contents('span.name').text()) + '\'')) {
				$.ajax({
					url: app.urls.removeRegion,
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
	$('#regions')
		/** VIEW ITEM
		   ----------------------------------------------*/
		.delegate('span.remove-item', 'click', function (e) {
			e.stopPropagation();e.preventDefault();
			removeRegion(this);
		/** EDIT CATEGORY
		   ----------------------------------------------*/
		}).delegate('span.edit-item', 'click', function(e){
			e.stopPropagation();e.preventDefault();
			editRegion(this);
		/** CREATE SUBCAT
		   ----------------------------------------------*/
		}).delegate('input.region-default', 'click', function (e) {
            set_defaultRegion(this);
        });

	// INIT UNIQUE BUTTONS ACTIONS
	/** CREATE TOP CAT
	   ----------------------------------------------*/
	$('#create-region').click(function(e){
		e.preventDefault();
		createRegion(this);
	});
});