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
		var p = $("[name=parameter]").val();
		if(q.length > 0) {
			switch (p) {
				case 'code' :
					window.location.href = '/parts/' + q;
				break;
				case 'model' :
					window.location.href = '/parts/models/' + q;
				break;
				case 'part_name' :
					window.location.href = '/parts/search/' + q;
				break;
			}
		}
		});
	});
</script>
<form name="search_form">
	<div class="select-and-input">
		<select name="parameter">
			<option value="code">Код</option>
			<option value="model">Модель</option>
			<option value="part_name">Название детали</option>
		</select>
		<input class="search_field" type="text" name="query"/>
		<div class="search_submit"></div>
		<input class="search_submit"  type="submit" value="Sub"/>
	</div>
</form>