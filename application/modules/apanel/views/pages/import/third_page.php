<h2>Подтвердение импорта</h2>
<br>
<form action="/apanel/import/save/" method="post" autocomplete="off">

	<p>Здесь показана информация, которая будет введена в систему, вы можете отредактировать ее перед сохранением.</p>
	<br>

	<p>Импорт информации из файла: <strong><?php echo $post['file'] ?></strong><br><br></p>
	<table class="desc">
		<tr>
			<td class="label">производитель:</td>
			<td>
				<input type="hidden" name="vendor" value="<?php echo $post['vendor_id'] ?>">
				<select name="vendors" disabled="disabled"><?php echo $this->vendors_model->getAll('select', array('selected' => $post['vendor_id'])) ?></select>
			</td>
		</tr>
		<tr>
			<td class="label">модель:</td>
			<td>
				<?php if ($post['model_select'] > 0) { ?>
					<input type="hidden" name="model_select" value="<?php echo $post['model_select'] ?>">
					<select name="model_select" disabled="disabled"><?php echo implode('', $this->phones_model->getAllVendorModels($post['vendor_id'], 'select', array('selected' => $post['model_select']))) ?></select>
				<?php } else { ?>
					<input type="hidden" name="model_input" value="<?php echo $post['model_input'] ? $post['model_input'] : '' ?>">
					<input type="text" name="model_input" value="<?php echo $post['model_input'] ? $post['model_input'] : '' ?>" disabled="disabled">
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td class="label" colspan="2">Информация о ревизии:</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="value">
				Номер ревизии листа:<br>
				<input name="rev_num" value="<?php echo $post['rev_num'] ?>"><br>
				<?php if (isset($current['model']['rev_num']) && !empty($current['model']['rev_num'])) { ?>
				Сейчас:
				<span class="current-data<?php echo $post['rev_num'] != $current['model']['rev_num'] ? ' to-change' : '' ?>"><?php echo $current['model']['rev_num'] ?></span><br>
				<?php } ?>
				<br>
				Описание ревизии листа:<br>
				<input name="rev_desc" value="<?php echo $post['rev_desc'] ?>"><br>
				<?php if (isset($current['model']['rev_desc']) && !empty($current['model']['rev_desc'])) { ?>
				Сейчас:
				<span class="current-data<?php echo $post['rev_desc'] != $current['model']['rev_desc'] ? ' to-change' : '' ?>"><?php echo $current['model']['rev_desc'] ?></span>
				<?php } ?>
			</td>
		</tr>
	</table>
	<br><br><br>

<?php foreach ($sheets as $sheet) { ?>

	<div class="sheet-import-data">
		<input type="hidden" name="sheets_data[<?php echo $sheet['id'] ?>][type]" value="<?php echo $sheet['type'] ?>">
		<h2><?php echo $sheet['name'] ?></h2>

		<?php if (isset($sheet['data']) && count($sheet['data']) > 0) { ?>
		<table>
			<thead>
					<th><input type="checkbox" class="check-all" id="check-all<?php echo $sheet['id'] ?>" checked="checked"></th>
				<?php foreach ($sheet['data'][0] as $fieldN => $tmp) { ?>
					<th><?php echo $sheet['fields'][$fieldN] ?></th>
				<?php } ?>
			</thead>
			<tbody>
			<?php foreach ($sheet['data'] as $rowN => $row) { ?>
				<tr>
					<td><input type="checkbox" value="<?php echo $rowN ?>" name="sheets_data[<?php echo $sheet['id'] ?>][rows][]" checked="checked"></td>
					<?php foreach ($row as $fieldN => $field) { ?>
					<?php
						// change names of fields with region from [region_9] to [regions][9]
						$fieldN = !preg_match('/^region_/', $fieldN) ? $fieldN : 'regions][' . (int)end(explode('_', $fieldN));
					?>
					<td class="<?php echo !preg_match('/^regions/', $fieldN) ? $fieldN : 'regions' ?>">
						<input type="text" name="sheets_data[<?php echo $sheet['id'] ?>][cols][<?php echo $rowN ?>][<?php echo $fieldN ?>]" value="<?php echo trim($field) ?>">
					</td>
					<?php } ?>
				</tr>
			<?php } ?>
			</tbody>
		</table>

		<?php } else { ?>

			<span class="no-data">Лист не отмечен или нет данных для импорта</span>

		<?php } ?>

	</div>

<?php } ?>

	<input type="submit" value="Сохранить информацию">

</form>

<div class="additional-detailes">
	<ul>
		<li><span class="current-data">текст</span> - выделение информации из прошлой ревизии, которая осталась без изменений в текущей</li>
		<li><span class="current-data to-change">текст</span> - выделение информации из прошлой ревизии, которая изменена в текущей</li>
		<li>Галочки слева от строки со значениями, позволяют исключить из внесения в систему отдельные строки, которые могли быть неправильно распознаны системой как верные.</li>
	</ul>
</div>