$(document).ready(function () {
	var sii = $('#selected-item-info'),
		regions = $('#regions'),

		/** shows popup and hadles ajax call to create new top level cat */
		createRegion = function (elm) {
			var pc = $(_.template(
					app.templates.regions.edit,
					{
						id: 0,
						item: '',
						name: ''

					}
				));
				pc.submit(function(e){
					e.preventDefault();
					$.ajax({
						url: this.action,
						type: 'post',
						dataType: 'json',
						data: $(this).serialize(),
						success: function(resp){
							if (resp.status === 1) {
								app.showMessage({html: resp.message});
								// update cache
								app.cache.regions[resp.item.id] = resp;
								regions.append(_.template(app.templates.regions.ul, resp));
								sii.html(_.template(app.templates.regions.show, resp));
							} else {
								app.showMessage({html: resp.error});
							}
						}
					});
				}).find('input[type="submit"]').prop('disabled', true).end()
				.find('button[name="cancel"]').click(function(e){
						e.preventDefault();
						sii.empty();
					}).end()
				.find(':text').bind('keyup', function(){
						pc.find(':submit').prop('disabled', false);
					}).end();
			sii.html(pc).find('input[name="name"]').focus();
		},

		/** */
		editRegion = function (elm) {
			var form = $(elm).parent().parent(),
				id = form.attr('id').substr(1);
			getRegion(id, function(item){
				var pc = $(_.template(app.templates.regions.edit, item));

				// bind form actions
				pc.submit(function(e){
						e.preventDefault();
						$.ajax({
							url: this.action,
							type: 'post',
							dataType: 'json',
							data: $(this).serialize(),
							success: function(resp){
								if (resp.status === 1) {
									app.showMessage({html: resp.message});
									// update cache
									app.cache.regions[resp.item.id] = resp;
									regions.find('#c' + resp.item.id).replaceWith(_.template(app.templates.regions.ul, resp));
									sii.html(_.template(app.templates.regions.show, resp));
								} else {
									app.showMessage({html: resp.error});
								}
								app.popup.add(app.splash).hide();
							}
						});
					}).find('input[type="submit"]').prop('disabled', true).end()
					.find('button[name="cancel"]').click(function(e){
							e.preventDefault();
							getRegion(id, function(item){
								sii.html(_.template(app.templates.regions.show, item));
							});
						}).end()
					.find(':text').bind('keyup', function(){
							pc.find(':submit').prop('disabled', false);
						});

				sii.html(pc);
				pc.find('input[name="title"]').focus().select();

			});
		},

		/** */
		removeRegion = function (elm) {
			var a = $(elm).parent().parent();
			if (confirm('Вы действительно хотите удалить поставщика \'' + $.trim(a.text()) + '\'')) {
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
							$('#c' + a.attr('id').substr(1)).remove();
							sii.empty();
						} else {
							app.showMessage({html: resp.error});
						}
					}
				});
			}
		},

		searchRegion = function(text){
			if (text !== false) {
				var reg = new RegExp(text, "i");
				regions.find('span.name').each(function(){
					$(this).parent().parent().css('display', this.innerHTML.search(reg) >= 0 ? 'block' : 'none');
				});
			} else {
				regions.contents('li').css('display', 'block');
				$('#search-region').get(0).text.value = '';
			}
		},

		getRegion = function(id, callback){
			if (typeof app.cache.regions[id] === 'undefined') {
				$.ajax({
					url: app.urls.getRegion,
					type: 'post',
					dataType: 'json',
					data: { id: id },
					success: function (resp) {
						if (resp.status === 1) {
							app.cache.regions[id] = resp;
							if (_.isFunction(callback)) {
								callback(resp);
							}
						} else {
							app.showMessage(resp.error);
						}
					}
				});
			} else {
				if (_.isFunction(callback)) {
					callback(app.cache.regions[id]);
				}
			}
		};

	// INIT CATS NAVIGATION
	regions.delegate('a', 'click', function (e) {
			e.preventDefault();
			regions.find('a.selected').removeClass('selected');
			var a = $(this).addClass('selected'), id = parseInt(this.id.substr(1), 10);

			// load item's descendants if this information was not already loaded or cache expired
			getRegion(id, function(item){
				// show received cats data
				sii.html(_.template(app.templates.regions.show, item));
			});
		});

	sii.delegate('span.edit', 'click', function(e){
		/** EDIT CATEGORY
		   ----------------------------------------------*/
			e.stopPropagation();
			editRegion(this);
		/** REMOVE CAT
		   ----------------------------------------------*/
		}).delegate('span.remove', 'click', function(e){
			e.stopPropagation();
			removeRegion(this);
		});

	// INIT UNIQUE BUTTONS ACTIONS
	/** CREATE TOP CAT
	   ----------------------------------------------*/
	$('#create-region').click(function(e){
		e.preventDefault();
		createRegion(this);
	});

	$('#search-region').submit(function(e){
		e.preventDefault();
		searchRegion(this.text.value);
	});

	$('#cancel-search-region').click(function(e){
		e.preventDefault();
		searchRegion(false);
	});

});