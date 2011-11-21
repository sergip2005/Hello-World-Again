<ul>
	<li><a href="/">Главная</a></li>
	<li><a href="/how-to-order">Как заказать</a></li>
	<li><a href="/contacts">Контакты</a></li>
</ul>
<script>
	$(document).ready(function(){
	$("[name=search_form]").submit( function (e) {
	e.stopPropagation();
	e.preventDefault();
	var q = $("input.search_field").val();
	var p = $("[name=parameter]").val().length > 0 ? '/' + $("[name=parameter]").val() : '';
	if(q.length > 0) window.location.href = '/parts/search/' + q + p;
	});
	});
</script>
<form name="search_form">
	<div class="select-and-input">
		<select name="parameter">
			<option value="Value1">Value1</option>
			<option value="Value2">Value2</option>
			<option value="Value3">Value3</option>
		</select>
		<input class="search_field" type="text" name="query"/>
		<div class="search_submit"></div>
		<input class="search_submit"  type="submit" value="Sub"/>
	</div>
</form>