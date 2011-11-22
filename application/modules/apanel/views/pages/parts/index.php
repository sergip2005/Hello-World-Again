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
	<div class="parts-table clearfix">
		<table class="tablesorter">
			<thead>
				<th>Произв-ль</th>
				<th>Модель</th>
				<th>Позиция</th>
				<th>Парт-номер</th>
				<th>Использ., шт</th>
				<th>Описание, eng</th>
				<th>Описание, рус</th>
				<th>Наличие</th>
				<th>Цена</th>
				<th>Мин. кол.</th>
				<th>Тип детали</th>
			</thead>
			<tbody id="parts"></tbody>
		</table>
	</div>

</div>