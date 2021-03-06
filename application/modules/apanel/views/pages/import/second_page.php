<h2>Детали импорта</h2>
<br>
<p>Файл загружен на сервер, для успешной его обработки необходимо ввести дополнительные сведения</p>
<br>
<p>Имя файла: <strong><?php echo $file_data['client_name'] ?></strong></p>
<br>

<form id="details_form" action="/apanel/import/process_details/" method="post">
	<table class="desc">
		<tr>
			<td class="label">Поставщик:</td>
			<td class="value">
				<select name="vendors" autocomplete="off"><?php echo $this->vendors_model->getAll('select') ?></select>
			</td>
		</tr>
		<tr>
			<td class="label" colspan="2">Модель:</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="value">выберите существующую из списка:<br>
				<select name="model_select" autocomplete="off">
					<option value="0">-</option>
					<?php echo implode('', $this->phones_model->getAllVendorModels('first', 'select')) ?>
				</select>
				<br><br>
				или создайте новую, вписав название:<br>
				<input type="text" placeholder="Модель телефона" name="model_input" value="">
				<br><br>
			</td>
		</tr>
		<tr>
			<td class="label" colspan="2">Информация о ревизии:</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="value">
				Номер ревизии листа:<br>
				<input name="rev_num" value=""><br><br>
				<p class="current-data" id="rev-num" style="display: none"></p><br>
				Описание ревизии листа:<br>
				<input name="rev_desc" value=""><br>
				<p class="current-data" id="rev-desc" style="display: none"></p><br><br>
				<p class="current-data" id="rev-date" style="display: none"></p>
			</td>
		</tr>
	</table>

	<input type="hidden" name="file" value="<?php echo $file_data['file_name'] ?>">

<?php if (count($sheets) > 0) { ?>

	<p><br>Список листов, найденных в документе (всего <?php echo count($sheets) ?>):<br>
		<i>(Отметьте те листы, данные с которых следует внести в систему, и заполните шаблон, по которому система будет обрабатывать информацию)</i>
	</p>

	<ul id="sheets">
	<?php foreach ($sheets as $sheet) { ?>
		<li>
			<input type="hidden" value="<?php echo $sheet['id'] ?>" name="sheets[]">
			<input type="hidden" value="<?php echo $sheet['name'] ?>" name="sheets_names[]">

			<label><?php echo $sheet['name'] ?></label>
			(столбцов с данными <?php echo $sheet['cols_number'] ?>, строк с данными <?php echo $sheet['rows_number'] ?>)
			<div id="sheet<?php echo $sheet['id'] ?>" class="sheet-info">

				<div class="data-template">
					Шаблон данных листа:
					<?php echo $this->import_data_tpl_model->getSelect() ?>
					<span title="Удалить шаблон" class="edit-item icon-container">
						<span class="ui-icon ui-icon-close"></span>
					</span>
					<span title="Сохранить шаблон" class="edit-item icon-container">
						<span class="ui-icon ui-icon-disk"></span>
					</span>
					<span title="Использовать шаблон" class="edit-item icon-container">
						<span class="ui-icon ui-icon-extlink"></span>
					</span>
				</div>

				Тип данных в листе: <?php echo $this->import_model->possible_sheet_types($sheet['id']) ?><br><br>
				Выберите ячейки с информацией и тип данных в них:<br>
				<div class="demo-data">
					<a href="#" class="slide-trigger" id="sheet<?php echo $sheet['id'] ?>slide">Показать пример полученных данных</a><br><br>

					<table class="slide-content" id="sheet<?php echo $sheet['id'] ?>slide-content">
					<?php foreach ($sheet['demo'] as $row) { ?>
						<tr>
							<?php foreach ($row as $col) { ?>
								<td><?php echo $col ?></td>
							<?php } ?>
						</tr>
					<?php } ?>
					</table>
				</div>

				<table class="sheet-cols-values">
					<tr>
						<?php for($i = 0; $i < $sheet['cols_number']; $i += 1) { ?>
						<?php $cellIndex = PHPExcel_Cell::stringFromColumnIndex($i); ?>
						<td>
							<?php echo $cellIndex ?>. <?php echo $this->import_model->possible_values($sheet['id']) ?>
						</td>
						<?php if ($i != 0 && ($i + 1) % 4 == 0) { ?></tr><tr><?php } ?>
						<?php } ?>
					</tr>
				</table>
			</div>
		</li>
	<?php } ?>
	</ul>
	<br><br>
	<input type="submit" value="Начать импорт">
<?php } ?>
</form>

<div class="additional-detailes">
	<ul>
		<li>Для поля, задающего регион считается, что символы + или х означают принадлежность детали этому региону. Остальные случаи - отвечают непринадлежности.</li>
		<li>Из данных, полученных из листа с данными Excel, автоматически уберутся пустые строки и строки, в которых ячейка с типом данных "Парт. номер" не содержит информации, содержит менее 4х символов или содержит только символ-заполнитель "х" или содержит только слово-заголовок "Code". Все остальные случаи обработаются системой как парт. номера детали. Уточнить\изменить информацию по обработанным данным можно будет на следующей странице</li>
	</ul>
</div>