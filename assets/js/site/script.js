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
	search = $(".select-and-input");

	search.delegate('.search_code', 'click', function (e) {
		$(this).val('');
	});
	search.delegate('.search_submit_code', 'click', function (e) {
		var q = $(".select-and-input input.search_code").val();
		e.stopPropagation();
		e.preventDefault();
		if(q.length > 0 && q!== "Код") {
			window.location.href = '/parts/' + encodeURI(q);
		}
	});

	search.delegate('.search_model', 'click', function (e) {
		$(this).val('');
	});
	search.delegate('.search_submit_model', 'click', function (e) {
		var q = $(".select-and-input input.search_model").val();
		e.stopPropagation();
		e.preventDefault();
		if(q.length > 0 && q!== "Модель") {
			window.location.href = '/parts/models/' + encodeURI(q);
		}
	});
	
	search.delegate('.search_name', 'click', function (e) {
		$(this).val('');
	});
	search.delegate('.search_submit_name', 'click', function (e) {
		var q = $(".select-and-input input.search_name").val();
		e.stopPropagation();
		e.preventDefault();
		if(q.length > 0 && q!== "Название") {
			window.location.href = '/parts/search/' + encodeURI(q);
		}
	});
});