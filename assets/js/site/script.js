$(document).ready(function () {
// search
	$("[name=search_form]").submit( function (e) {
		e.stopPropagation();
		e.preventDefault();
		var q = $("input.search_field").val();
		var p = $("[name=parameter]").val();
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
});