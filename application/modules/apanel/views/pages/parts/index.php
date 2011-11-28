<div id="main" class="parts-navigation">

	<div class="clearfix">
		<div class="vendors-list">
			<h3 class="title">Производители</h3>
			<ul id="vendors">
				<?php foreach ($vendors as $vendor) { ?>
					<li data-id="<?php echo $vendor['id'] ?>"><?php echo $vendor['name'] ?></li>
				<?php } ?>
			</ul>
		</div>

		<div class="models-list">
			<h3>Модели</h3>
			<ul id="models"></ul>
		</div>
	</div>

	<div id="controls" class="controls clearfix">
		<button disabled="disabled" class="move">Переместить</button>
		<button disabled="disabled" class="change-pn">Изменить парт-номер</button>
		<button disabled="disabled" class="remove">Удалить</button>
	</div>

	<div id="search" class="select-and-input">
		<form name="search_parts_code">
			<div class="search_field search_code">
				<input class="text" placeholder="Код" type="text" name="parts_code">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="parts_code">
			</div>
		</form>
		<form name="search_model_name">
			<div class="search_field search_model">
				<input class="text" type="text" placeholder="Модель" name="model_name">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="model_name">
			</div>
		</form>
		<form name="search_parts_name">
		<div class="search_field search_name">
				<input class="text" type="text" placeholder="Название" name="parts_name">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="parts_name">
			</div>
		</form>
	</div>

	<div class="parts-table clearfix">
		<table class="tablesorter">
			<thead>
				<th class="check"><input type="checkbox" class="check-all"></th>
				<th class="vendor">Произв.</th>
				<th class="model">Модель</th>
				<th class="cct_ref">Позиция</th>
				<th class="code">Парт-номер</th>
				<th class="num">Использ.</th>
				<th class="name">Описание, eng</th>
				<th class="name">Описание, рус</th>
				<th class="num">Наличие</th>
				<th class="price">Цена</th>
				<th class="num">Мин.</th>
				<th class="ptype">Тип</th>
			</thead>
			<tbody id="parts"></tbody>
		</table>
	</div>

</div>