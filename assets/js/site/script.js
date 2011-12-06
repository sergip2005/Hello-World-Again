$(document).ready(function () {
	/*
// search
	$("[name=search_form]").submit( function (e) {

		e.stopPropagation();
		e.preventDefault();

		var q = $("input.search_field").val();
		var p = $(".select-and-input span").attr('value');
		if(q.length > 0) {
			switch (p) {
				case 'code' :
					window.location.href = '/parts/' + encodeURI(q);
				break;
				case 'model' :
					window.location.href = '/parts/models/' + encodeURI(q);
				break;
				case 'part_name' :
					window.location.href = '/parts/search/' + encodeURI(q);
				break;
			}
		}
	});

	$(".select-and-input").delegate('span', 'click', function (e) {
		$(".select-and-input ul").show();
	});

	$(".select-and-input").delegate('ul li', 'click', function (e) {
		$(".select-and-input span").attr('value', $(this).attr('name'));
		$(".select-and-input span").text($(this).text());
		$(".select-and-input ul").hide();
	});*/
	var search = $(".select-and-input");

	/*$('form[name="search_parts_code"] input.text', search).livesearch({
		searchCallback: function(){ search.find('form[name="search_parts_code"]').trigger('submit'); },
		queryDelay: 250,
		innerText: "Код",
		minimumSearchLength: 2
	});
	$('form[name="search_model_name"] input.text', search).livesearch({
		searchCallback: function(){ search.find('form[name="search_model_name"]').trigger('submit'); },
		queryDelay: 250,
		innerText: "Модель",
		minimumSearchLength: 2
	});
	$('form[name="search_parts_name"] input.text', search).livesearch({
		searchCallback: function(){ search.find('form[name="search_parts_name"]').trigger('submit'); },
		queryDelay: 250,
		innerText: "Название",
		minimumSearchLength: 2
	});*/
	search.delegate('form[name="search_parts_code"]', 'submit', function(e){
			e.preventDefault();
			if($.trim($(this).find('input.text').val()) !== '') {
				window.location.href = '/parts/' + encodeURI($(this).find('input.text').val());
			}
		}).delegate('form[name="search_model_name"]', 'submit', function(e){
			e.preventDefault();
			if($.trim($(this).find('input.text').val()) !== '') {
				window.location.href = '/parts/models/' + encodeURI($(this).find('input.text').val());
			}
		}).delegate('form[name="search_parts_name"]', 'submit', function(e){
			e.preventDefault();
			if($.trim($(this).find('input.text').val()) !== '') {
				window.location.href = '/parts/search/' + encodeURI($(this).find('input.text').val());
			}
	});

	/*search.delegate('div.search_code input.submit', 'click', function (e) {
		var q = $(".select-and-input div.search_code input.text").val();
		e.stopPropagation();
		e.preventDefault();
		if($.trim(q) !== '') {
			window.location.href = '/parts/' + encodeURI(q);
		}
	});

	search.delegate('div.search_model input.submit', 'click', function (e) {
		var q = $(".select-and-input div.search_model input.text").val();
		e.stopPropagation();
		e.preventDefault();
		if($.trim(q) !== '') {
			window.location.href = '/parts/models/' + encodeURI(q);
		}
	});

	search.delegate('div.search_name input.submit', 'click', function (e) {
		var q = $(".select-and-input div.search_name input.text").val();
		e.stopPropagation();
		e.preventDefault();
		if($.trim(q) !== '') {
			window.location.href = '/parts/search/' + encodeURI(q);
		}
	});*/
});

jQuery.fn.ForceNumericOnly =
function()
{
	return this.each(function()
	{
		$(this).keydown(function(e)
		{
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			return (
				key == 8 ||
				key == 9 ||
				key == 46 ||
				(key >= 37 && key <= 40) ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));
		});
	});
};