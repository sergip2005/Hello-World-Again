$(document).ready(function () {
	var sii = $('#selected-item-info'),
		vendors = $('#vendors'),

		/** shows popup and hadles ajax call to create new top level cat */
		createVendor = function (elm) {
			var pc = $(_.template(
					app.templates.vendors.edit,
					{
						id: 0,
						item: '',
						points: '',
						name: '',
						login: '',
						email: '',
						phone: '',
						title: '',
						point_id: '',
						login_allowed: '',
						role: '',
						note: ''
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
								app.cache.vendors[resp.item.id] = resp.item;
								vendors.append(_.template(app.templates.vendors.ul, resp.item));
								sii.html(_.template(app.templates.vendors.show, resp.item));
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
		editVendor = function (elm) {
			var form = $(elm).parent().parent(),
				id = form.attr('id').substr(1);
			getVendor(id, function(item){
				var pc = $(_.template(app.templates.vendors.edit, item));

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
									app.cache.vendors[resp.item.id] = resp;
									vendors.find('#c' + resp.item.id).replaceWith(_.template(app.templates.vendors.ul, resp));
									sii.html(_.template(app.templates.vendors.show, resp));
								} else {
									app.showMessage({html: resp.error});
								}
								app.popup.add(app.splash).hide();
							}
						});
					}).find('input[type="submit"]').prop('disabled', true).end()
					.find('button[name="cancel"]').click(function(e){
							e.preventDefault();
							getVendor(id, function(item){
								sii.html(_.template(app.templates.vendors.show, item));
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
		removeVendor = function (elm) {
			var a = $(elm).parent().parent();
			if (confirm('Вы действительно хотите удалить поставщика \'' + $.trim(a.text()) + '\'')) {
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
							$('#c' + a.attr('id').substr(1)).remove();
							sii.empty();
						} else {
							app.showMessage({html: resp.error});
						}
					}
				});
			}
		},

		searchVendor = function(text){
			if (text !== false) {
				var reg = new RegExp(text, "i");
				vendors.find('span.name').each(function(){
					$(this).parent().parent().css('display', this.innerHTML.search(reg) >= 0 ? 'block' : 'none');
				});
			} else {
				vendors.contents('li').css('display', 'block');
				$('#search-vendor').get(0).text.value = '';
			}
		},

		getVendor = function(id, callback){
			if (typeof app.cache.vendors[id] === 'undefined') {
				$.ajax({
					url: app.urls.getVendor,
					type: 'post',
					dataType: 'json',
					data: { id: id },
					success: function (resp) {
						if (resp.status === 1) {
							app.cache.vendors[id] = resp;
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
					callback(app.cache.vendors[id]);
				}
			}
		};

	// INIT CATS NAVIGATION
	vendors.delegate('a', 'click', function (e) {
			e.preventDefault();
			vendors.find('a.selected').removeClass('selected');
			var a = $(this).addClass('selected'), id = parseInt(this.id.substr(1), 10);

			// load item's descendants if this information was not already loaded or cache expired
			getVendor(id, function(item){
				// show received cats data
				sii.html(_.template(app.templates.vendors.show, item));
			});
		});

	sii.delegate('span.edit', 'click', function(e){
		/** EDIT CATEGORY
		   ----------------------------------------------*/
			e.stopPropagation();
			editVendor(this);
		/** REMOVE CAT
		   ----------------------------------------------*/
		}).delegate('span.remove', 'click', function(e){
			e.stopPropagation();
			removeVendor(this);
		});

	// INIT UNIQUE BUTTONS ACTIONS
	/** CREATE TOP CAT
	   ----------------------------------------------*/
	$('#create-vendor').click(function(e){
		e.preventDefault();
		createVendor(this);
	});

	$('#search-vendor').submit(function(e){
		e.preventDefault();
		searchVendor(this.text.value);
	});

	$('#cancel-search-vendor').click(function(e){
		e.preventDefault();
		searchVendor(false);
	});

});