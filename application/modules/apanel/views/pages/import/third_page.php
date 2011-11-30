<pre><?php //print_r($sheets) ?></pre>

<h2>Подтвердение импорта</h2>
<br>

<p>Импорт информации из файла: <strong><?php echo $post['file'] ?></strong><br><br></p>
<table class="desc">
	<tr>
		<td class="label">производитель:</td>
		<td>
			<input type="hidden" name="vendor" value="<?php echo $post['vendor_id'] ?>">
			<?php $v = $this->vendors_model->get($post['vendor_id']); echo $v['name']; ?>
		</td>
	</tr>
	<tr>
		<td class="label">модель:</td>
		<td>
			<input type="hidden" name="model_select" value="<?php echo $post['model_select'] ?>">
			<?php $m = $this->phones_model->getModel($post['model_select']); echo $m['model']; ?>
		</td>
	</tr>
	<tr>
		<td class="label">Номер ревизии листа:</td>
		<td class="value">
			<?php echo $post['rev_num'] ?><br>
			<?php if (isset($current['model']['rev_num']) && !empty($current['model']['rev_num'])) { ?>
			Текущий:
			<span class="current-data<?php echo $post['rev_num'] != $current['model']['rev_num'] ? ' to-change' : '' ?>"><?php echo $current['model']['rev_num'] ?></span><br>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="label">Описание ревизии листа:</td>
		<td class="value">
			<?php echo $post['rev_desc'] ?><br>
			<?php if (isset($current['model']['rev_desc']) && !empty($current['model']['rev_desc'])) { ?>
			Текущее:
			<span class="current-data<?php echo $post['rev_desc'] != $current['model']['rev_desc'] ? ' to-change' : '' ?>"><?php echo $current['model']['rev_desc'] ?></span>
			<?php } ?>
		</td>
	</tr>
</table>

<br><br><br>
<p>Здесь показана информация, которая будет введена в систему, вы можете отредактировать ее перед сохранением.</p>
<br><br>

<?php foreach ($sheets as $sheet) {
	if ($sheet['count_data']['total'] <= 0) continue; ?>
	<div class="sheet-import-data" id="sheet<?php echo $sheet['id'] ?>">
	<form action="/apanel/import/save/" method="post" autocomplete="off">
		<input type="hidden" name="action" value="save_data">
		<input type="hidden" name="import_id" value="<?php echo $import_id ?>">
		<input type="hidden" name="model_id" value="<?php echo $post['model_select'] ?>">
		<input type="hidden" name="vendor_id" value="<?php echo $post['vendor_id'] ?>">
		<input type="hidden" name="sheet_id" value="<?php echo $sheet['sheet_id']?>">

		<input type="hidden" name="sheet[type]" value="<?php echo $sheet['type'] ?>">
		<h2><?php echo $sheet['name'] ?></h2>
		<p class="sheet-data-info">
			Всего <?php echo $sheet['count_data']['total'] ?> записей на <?php echo $sheet['count_data']['pages'] ?> страницах (<?php echo $sheet['count_data']['per_page'] ?> записей на странице).<br />
			Сейчас показазаны записи с <?php echo ($sheet['count_data']['page'] - 1) * $sheet['count_data']['per_page'] ?>
			по <?php echo $sheet['count_data']['total'] >= $sheet['count_data']['page'] * $sheet['count_data']['per_page'] ? $sheet['count_data']['page'] * $sheet['count_data']['per_page'] : $sheet['count_data']['total'] ?>
		</p>
		<?php if ($sheet['count_data']['pages'] > 1) { ?>
			<ul class="pages">
			<?php for($i = 1; $i <= $sheet['count_data']['pages']; $i += 1) { ?>
				<li><a <?php echo $i == $sheet['count_data']['page'] ? ' class="active"' : '' ?> href="/apanel/import/process_details/<?php echo $import_id ?>/<?php echo $i ?>"><?php echo $i ?></a></li>
			<?php } ?>
			</ul>
		<?php } ?>
		<?php if (isset($sheet['data']) && count($sheet['data']) > 0) { ?>
		<table class="clear">
			<thead>
					<th><input type="checkbox" class="check-all" id="check-all<?php echo $sheet['id'] ?>" checked="checked"></th>
				<?php foreach ($sheet['data'][0] as $fieldN => $tmp) {
					if ($fieldN == 'row_id') continue; ?>
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
							if (isset($sheet['prev_state']['phone_parts'][$row['code']]) && count($sheet['prev_state']['phone_parts'][$row['code']]) > 0) {
								$c_phone_parts = $sheet['prev_state']['phone_parts'][$row['code']]; // may be more than 1
							} else {
								$c_phone_parts = array();
							}

							$nn = 0;
						if (count($c_phone_parts) > 0 && in_array($sheet['type'], array('cabinet', 'solder'))) { // for sheets with parts data
							// narrow selection to only current part position or all parts
							$c_phone_parts = find_cct_ref_elm($row['cct_ref'], $c_phone_parts);

							foreach ($c_phone_parts as $phonePart) {
								unset($current['model_parts']['parts'][$phonePart['id']]);
								$c_regions = isset($sheet['prev_state']['regions'][$phonePart['id']]) ? $sheet['prev_state']['regions'][$phonePart['id']] : false;
						?>
						<tr class="current <?php echo $ii % 2 ? 'odd' : 'even' ?>">
							<td class="check"></td>
							<?php foreach ($row as $fieldN => $field) {
									if ($fieldN == 'row_id') continue;
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
										echo $this->currency_model->convert(end(explode('_', $this->currency_model->base)), $fieldN, $c_part['price']);;
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
						<?php } ?>
						<?php } elseif (in_array($sheet['type'], array('prices'))) { // for price sheet ?>
						<tr class="current<?php echo $ii % 2 ? ' odd' : ' even' ?>">
							<td class="check"></td>
							<?php foreach ($row as $fieldN => $field) { ?>
								<?php
									$fieldType = '';
									if (preg_match('/^price_/', $fieldN)) {
										$fieldN = (string)end(explode('_', $fieldN));
										$fieldType = 'price';
									} else {
										$region = false;
									}
								?>
								<td class="<?php echo $fieldType == 'price' ? 'price' : $fieldN ?>">
									<?php if ($fieldType == 'price') {
										$cPrice = $this->currency_model->convert(end(explode('_', $this->currency_model->base)), $fieldN, $c_part['price']);
										?>
										<span class="<?php echo ($cPrice != $row['price_' . $fieldN]) ? 'changed' : 'not_changed' ?>"><?php echo $cPrice ?></span>
									<?php } elseif (in_array($fieldN, $this->parts_model->partFields)) { ?>
										<span class="<?php echo ($c_part[$fieldN] != $row[$fieldN]) ? 'changed' : 'not_changed' ?>"><?php echo $c_part[$fieldN] ?></span>
									<?php } ?>
								</td>
							<?php } ?>
						</tr>
						<?php
						}
					}
				} ?>
				<tr class="<?php echo $ii % 2 ? 'odd' : 'even' ?><?php echo isset($nn) && $nn > 0 ? ' has_current' : '' ?>">
					<td class="check"><input type="checkbox" value="<?php echo $row['row_id'] ?>" name="sheet[rows][]" checked="checked"></td>
					<?php foreach ($row as $fieldN => $field) {
						if ($fieldN == 'row_id') continue;
						// change names of fields with region from [region_9] to [regions][9]
						$fieldN = !preg_match('/^region_/', $fieldN) ? $fieldN : 'regions][' . (int)end(explode('_', $fieldN));
					?>
					<td class="<?php echo !preg_match('/^regions/', $fieldN) ? $fieldN : 'regions' ?>">
						<input type="text" name="sheet[cols][<?php echo $rowN ?>][<?php echo $fieldN ?>]" value="<?php echo trim($field) ?>">
					</td>
					<?php } ?>
				</tr>
			<?php
				$ii += 1;
			} ?>
			</tbody>
		</table>

		<?php } else { // no data ?>

			<span class="no-data">Лист не отмечен или нет данных для импорта</span>

		<?php } ?>
		<p><br><br><input type="submit" value="Обработать данные листа"></p>
	</form>
	</div>

<?php } ?>

	<?php if (isset($current['model_parts']) && count($current['model_parts']['parts']) > 0) { ?>
	<div class="to-remove">
		<form action="/apanel/import/save/" method="post" autocomplete="off">
			<input type="hidden" name="action" value="remove_unused">
			<input type="hidden" name="import_id" value="<?php echo $import_id ?>">
			<input type="hidden" name="model_id" value="<?php echo $post['model_select'] ?>">
			<input type="hidden" name="vendor_id" value="<?php echo $post['vendor_id'] ?>">

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
					<td><span class="<?php echo $one['type'] ?>"><?php echo $this->parts_model->partTypeName[$one['type']] ?></span></td>
				</tr>
				<?php
						$ri += 1;
					} ?>
			</table>
			<input type="submit" value="Удалить отмеченные">
		</form>
	</div>
	<?php } ?>

<div class="additional-detailes">
	<ul>
		<li><span class="current-data">текст</span> - выделение информации из прошлой ревизии, которая осталась без изменений в текущей</li>
		<li><span class="current-data to-change">текст</span> - выделение информации из прошлой ревизии, которая изменена в текущей</li>
		<li>Галочки слева от строки со значениями, позволяют исключить из внесения в систему отдельные строки, которые могли быть некорректно распознаны системой как верные.</li>
	</ul>
</div>

<?php //echo '<pre>' . print_r($sheets, true) . '</pre>' ?>
<?php //echo '<pre>' . print_r($current, true) . '</pre>' ?>