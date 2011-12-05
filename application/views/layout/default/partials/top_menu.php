<ul>
	<li><a href="/">Главная</a></li>
	<li><a href="/how-to-order">Как заказать</a></li>
	<li><a href="/contacts">Контакты</a></li>
</ul>

<div id="search">
	<div  class="select-and-input">
		<label>Парт-номер:</label>
		<form name="search_parts_code">
			<div class="search_field search_code">
				<input class="text" placeholder="пример: 6300314" type="text" name="parts_code">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="parts_code">
			</div>
		</form>
	</div>

	<div class="select-and-input">
		<label>Модель:</label>
		<form name="search_model_name">
			<div class="search_field search_model">
				<input class="text" type="text" placeholder="пример: N8-00 RM-596" name="model_name">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="model_name">
			</div>
		</form>
	</div>

	<div class="select-and-input">
		<label>Название детали:</label>
		<form name="search_parts_name">
		<div class="search_field search_name">
				<input class="text" type="text" placeholder="пример: Window Module" name="parts_name">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="parts_name">
			</div>
		</form>
	</div>
</div>