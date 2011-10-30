<h2>Подтвердение импорта</h2>
<br>
<form action="/apanel/import/save/" method="post" autocomplete="off">

	<p>Здесь показана информация, которая будет введена в систему, вы можете отредактировать ее перед сохранением.</p>
	<br>

	<p>Импорт информации из файла: <strong><?php echo $import['file'] ?></strong><br>
	<table class="desc">
		<tr>
			<td class="label">производитель:</td>
			<td><select name="vendors" disabled="disabled"><?php echo $this->vendors_model->getAll('select') ?></select></td>
		</tr>
		<tr>
			<td class="label">модель:</td>
			<td>
				выберите из списка:<br>
				<select name="model_select"><?php echo $import['model_select'] ?></select>
				<br><br>
				или создайте новую:<br>
				<input type="text" name="model_input" value="<?php echo $import['model_input'] ? $import['model_input'] : '' ?>">
			</td>
		</tr>
	</table>
	</p>
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
					<td><input type="text" name="sheets_data[<?php echo $sheet['id'] ?>][cols][<?php echo $rowN ?>][<?php echo $fieldN ?>]" value="<?php echo $field ?>"></td>
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