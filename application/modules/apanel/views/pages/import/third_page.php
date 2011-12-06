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
				Текущий:
				<span class="current-data<?php echo $post['rev_num'] != $current['model']['rev_num'] ? ' to-change' : '' ?>"><?php echo $current['model']['rev_num'] ?></span><br>
				<?php } ?>
				<br>
				Описание ревизии листа:<br>
				<input name="rev_desc" value="<?php echo $post['rev_desc'] ?>"><br>
				<?php if (isset($current['model']['rev_desc']) && !empty($current['model']['rev_desc'])) { ?>
				Текущее:
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

	<?php if ($sheet['type'] != 'prices') {
			if (isset($sheet['data']) && count($sheet['data']) > 0) { ?>
		<table>
			<thead>
					<th><input type="checkbox" class="check-all" id="check-all<?php echo $sheet['id'] ?>" checked="checked"></th>
				<?php foreach ($sheet['data'][0] as $fieldN => $tmp) { ?>
					<th><?php echo $sheet['fields'][$fieldN] ?></th>
				<?php } ?>
			</thead>
			<tbody>
			<?php
			$ii = 1;
			$prev_exists = isset($sheet['prev_state']) && count($sheet['prev_state']) > 0;// cache condition
			foreach ($sheet['data'] as $rowN => $row) {
				if ($prev_exists) { // if there are already any parts in db
					if (isset($sheet['prev_state']['parts'][$row['code']])) {
							$c_part = $sheet['prev_state']['parts'][$row['code']]; // 1 at a time
							$c_phone_parts = isset($sheet['prev_state']['phone_parts'][trim($row['code'])]) ? $sheet['prev_state']['phone_parts'][trim($row['code'])] : array(); // may be more than 1

							$nn = 0;
						if (count($c_phone_parts) > 0 && in_array($sheet['type'], array('cabinet', 'solder'))) { // for sheets with parts data
							// narrow selection to only current part position or all parts
							$c_phone_parts = find_cct_ref_elm($row['cct_ref'], $c_phone_parts);

							foreach ($c_phone_parts as $phonePart) {
								unset($current['model_parts']['parts'][$phonePart['id']]);
								//echo '<tr><td colspan=8>' . print_r($c_phone_parts, true) . '</td></tr>';
								$c_regions = isset($sheet['prev_state']['regions'][$phonePart['id']]) ? $sheet['prev_state']['regions'][$phonePart['id']] : false;
						?>
						<tr class="current <?php echo $ii % 2 ? 'odd' : 'even' ?>">
							<td></td>
							<?php foreach ($row as $fieldN => $field) {
									// prepare region field
									$fieldType = '';
									if (preg_match('/^region_/', $fieldN)) {
										$fieldN = (int)end(explode('_', $fieldN));
										$fieldType = 'region';
									} elseif (preg_match('/^price_/', $fieldN)) {
										$fieldN = (string)end(explode('_', $fieldN));
										$fieldType = 'price';
									} else {
										$region = false;
									}
								?>
								<td class="<?php
									if ($fieldType == 'region') {
										echo 'regions';
									} elseif ($fieldType == 'price') {
										echo 'price';
									} else {
										echo $fieldN;
									} ?>">
									<?php
									if ($fieldType == 'region') {
										echo $c_regions !== false && in_array($fieldN, $c_regions) ? 'x' : '';
									} elseif ($fieldType == 'price') {
										$this->currency_model->convert(end(explode('_', $this->currency_model->base)), $fieldN, $c_part['price']);
										echo '';
									} else {
										if (in_array($fieldN, $this->phones_model->phonePartFields)) { ?>
											<span class="<?php echo (trim($phonePart[$fieldN]) != trim($row[$fieldN])) ? 'changed' : 'not_changed' ?>"><?php echo $phonePart[$fieldN] ?></span>
										<?php } elseif (in_array($fieldN, $this->parts_model->partFields)) { ?>
											<span class="<?php echo (trim($c_part[$fieldN]) != trim($row[$fieldN])) ? 'changed' : 'not_changed' ?>"><?php echo $c_part[$fieldN] ?></span>
										<?php
										}
									}
									?>
								</td>
							<?php
								$nn += 1;
								} ?>
						</tr>
						<?php }
						}
					}
				} ?>
				<tr class="<?php echo $ii % 2 ? 'odd' : 'even' ?><?php echo isset($nn) && $nn > 0 ? ' has_current' : '' ?>">
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
			<?php
				$ii += 1;
			}
			} else { ?>
				<span class="no-data">Лист не отмечен или нет данных для импорта</span>
			<?php } ?>
			</tbody>
		</table>

		<?php } else {
			if (isset($sheet['results']) && !empty($sheet['results'])) echo $sheet['results'];
		} ?>

	</div>

<?php } ?>

	<?php if (isset($current['model_parts']) && !empty($current['model_parts']) && count($current['model_parts']['parts']) > 0) { ?>
	<div class="to-remove">
		<h3>Детали, информация о которых не найдена в текущей версии парт. листа, и они помечены к удалению</h3>
		<table>
			<thead>
				<th class="check"><input type="checkbox" checked="checked" id="check-all_n" class="check-all"></th>
				<th class="cct_ref"><?php echo $this->import_model->part_field_types['cct_ref'] ?></th>
				<th class="code"><?php echo $this->import_model->price_field_types['code'] ?></th>
				<th class="name"><?php echo $this->import_model->part_field_types['name'] ?></th>
				<th class="name"><?php echo $this->import_model->part_field_types['name_rus'] ?></th>
				<th class="ptype"><?php echo $this->import_model->part_field_types['ptype'] ?></th>
				<th class="price"><?php echo $this->import_model->price_field_types['price_eur'] ?></th>
				<th class="num"><?php echo $this->import_model->part_field_types['num'] ?></th>
				<th class="num"><?php echo $this->import_model->part_field_types['min_num'] ?></th>
				<th class="type"><?php echo $this->import_model->part_field_types['type'] ?></th>
			</thead>
			<?php $ri = 1; ?>
			<?php foreach ($current['model_parts']['parts'] as $one) { ?>
			<tr class="<?php echo $ri % 2 ? 'odd' : 'even' ?>">
				<td><input type="checkbox" value="<?php echo $one['id'] ?>" name="parts_to_remove[]" checked="checked"></td>
				<td><?php echo $one['cct_ref'] ?></td>
				<td><?php echo $one['code'] ?></td>
				<td><?php echo $one['name'] ?></td>
				<td><?php echo $one['name_rus'] ?></td>
				<td><?php echo $one['ptype'] ?></td>
				<td><?php echo $one['price'] ?></td>
				<td><?php echo $one['num'] ?></td>
				<td><?php echo $one['min_num'] ?></td>
				<td><?php echo $this->parts_model->partTypeName[$one['type']] ?></td>
			</tr>
			<?php
					$ri += 1;
				} ?>
		</table>
	</div>
	<?php } ?>

	<input type="submit" disabled="disabled" value="Внести изменения"><br><br><br>
	<a href="/apanel/import/">Вернуться на страницу импорта</a>

</form>

<div class="additional-detailes">
	<ul>
		<li><span class="current-data">текст</span> - выделение информации из прошлой ревизии, которая осталась без изменений в текущей</li>
		<li><span class="current-data to-change">текст</span> - выделение информации из прошлой ревизии, которая изменена в текущей</li>
		<li>Галочки слева от строки со значениями, позволяют исключить из внесения в систему отдельные строки, которые могли быть некорректно распознаны системой как верные.</li>
	</ul>
</div>

<?php //echo '<pre>' . print_r($sheets, true) . '</pre>' ?>
<?php //echo '<pre>' . print_r($current, true) . '</pre>' ?>