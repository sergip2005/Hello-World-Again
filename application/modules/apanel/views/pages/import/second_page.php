<?php
function possible_values($CI, $sheet_id) {
	$html = '<select name="sheet' . $sheet_id . '_cols_values[]">'
			. '<option value="0"></option>';

	$used_fields = array();

	$html .= '<optgroup label="деталь">';
	foreach ($CI->import_model->part_field_types as $val => $desc) {
		if (!in_array($val, $used_fields)) {
			$used_fields[] = $val;
			$html .= '<option value="' . $val . '">' . $desc .'</option>';
		}
	}
	$html .= '</optgroup>';

	$html .= '<optgroup label="прайс-лист">';
	foreach ($CI->import_model->price_field_types as $val => $desc) {
		if (!in_array($val, $used_fields)) {
			$used_fields[] = $val;
			$html .= '<option value="' . $val . '">' . $desc .'</option>';
		}
	}
	$html .= '</optgroup>';

	

		/*. '<option value="name">Ориг. имя детали</option>'
		. '<option value="code">Парт. номер детали</option>'
		. '<option value="cct_ref">Позиция детали на рисунке</option>'
		. '<option value="type">Тип детали</option>'
		. '<option value="num">Кол-во деталей в сборке</option>'
		. '<option value="comment">Комментарий к детали</option>'
		. '<option value="price_eur">Цена детали в eur</option>'
		. '<option value="price_dol">Цена детали в $</option>'
		. '<option value="price_hrn">Цена детали в грн</option>'*/
	$html .= '</select>';
	return $html;
	//$this->import_model->part_field_types;
}

function possible_sheet_types($CI, $id) {
	$html = '<select name="sheet_type' . $id . '">'
			. '<option value="0" selected="selected"></option>';
	foreach ($CI->import_model->sheet_types as $val => $desc) {
		$html .= '<option value="' . $val . '">' . $desc .'</option>';
	}
	$html .= '</select>';
	return $html;
}
?>
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
				<p id="model_error" class="validaton-error">Модель не задана</p>
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
				Описание ревизии листа<br>
				<input name="rev_desc" value="">
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
				<!-- sheet info form -->
				Тип данных в листе: <?php echo possible_sheet_types($this, $sheet['id']) ?><br><br>
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
							<?php echo $cellIndex ?>. <?php echo possible_values($this, $sheet['id']) ?>
						</td>
						<?php if ($i != 0 && ($i + 1) % 5 == 0) { ?></tr><tr><?php } ?>
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

<br>