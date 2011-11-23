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

	$(".select-and-input").delegate('.search_field1', 'click', function (e) {
		$(this).val('');
		$(".select-and-input input.search_field2").val('Модель');
		$(".select-and-input input.search_field3").val('Название') ;
	});
	$(".select-and-input").delegate('.search_submit1', 'click', function (e) {
		var q = $(".select-and-input input.search_field1").val();
		e.stopPropagation();
		e.preventDefault();
		if(q.length > 0 && q!== "Код") {
			window.location.href = '/parts/' + encodeURI(q);
		}
	});

	$(".select-and-input").delegate('.search_field2', 'click', function (e) {
		$(this).val('');
		$(".select-and-input input.search_field1").val('Код');
		$(".select-and-input input.search_field3").val('Название');
	});
	$(".select-and-input").delegate('.search_submit2', 'click', function (e) {
		var q = $(".select-and-input input.search_field2").val();
		e.stopPropagation();
		e.preventDefault();
		if(q.length > 0 && q!== "Модель") {
			window.location.href = '/parts/models/' + encodeURI(q);
		}
	});
	
	$(".select-and-input").delegate('.search_field3', 'click', function (e) {
		$(this).val('');
		$(".select-and-input input.search_field2").val('Модель');
		$(".select-and-input input.search_field1").val('Код');
	});
	$(".select-and-input").delegate('.search_submit3', 'click', function (e) {
		var q = $(".select-and-input input.search_field3").val();
		e.stopPropagation();
		e.preventDefault();
		if(q.length > 0 && q!== "Название") {
			window.location.href = '/parts/search/' + encodeURI(q);
		}
	});
});