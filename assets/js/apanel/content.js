$(document).ready(function () {
	$('#pages li').delegate('a', 'click', function (e) {
		e.preventDefault();
		var  id = parseInt(this.id.substr(1), 10);
		$('input[name="page_id"]').val(id);
		$('#form').submit();
	});
});