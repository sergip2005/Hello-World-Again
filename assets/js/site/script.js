$(document).ready(function () {
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
});

jQuery.fn.ForceNumericOnly = function(){
	return this.each(function(){
		$(this).keydown(function(e){
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

function round(amount, precision){
	var a = Math.pow(10, precision);
	return Math.round(amount * a) / a;
}