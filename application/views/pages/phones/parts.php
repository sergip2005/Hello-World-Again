<div class="parts-content full-transparent">
<?php $parts = $parts['parts'] ?>
<?php if (count($parts) > 0) { ?>
	<div id="parts">
		<span class="s selected">Корпусные </span>
		<span class="c">Паечные </span>
	</div>

<?php
		$types = array('solder' => array(), 'cabinet' => array());
		foreach ($parts as $row) {
			 if($row['type'] == 's') {
				$types['solder'][] = $row;
			 } else {
				$types['cabinet'][] = $row;
			 }
		}

		foreach ($types as $type => $type_parts) {
			$name1 = $type == 'solder' ? 'паечные' : 'корпусные';
			$name2 = $type == 'solder' ? 'паечных' : 'корпусных';
			if (count($type_parts) > 0) { ?>
				<div class="<?php echo $type ?>">
					<?php if (!empty($type_parts[0][$type . '_image'])) { ?>
						<a class="zoom" href="/assets/images/phones/<?php echo $type_parts[0][$type . '_image'] ?>" title="<?php echo $name1 ?> элементы <?php echo $model ?>">
							<img class="zoom_src" src="/assets/images/phones/<?php echo $type_parts[0][$type . '_image'] ?>" alt="<?php echo $name1 ?> элементы <?php echo $model ?>">
						</a>
					<?php } ?>

					<table class="tablesorter separate clearfix">
						<thead>
							<tr>
								<th class="{sorter: false}">v.<?php echo $type_parts[0]['rev_num'] ?></th>
								<th class="{sorter: false}" colspan="7"><?php
									if ($region == '') {
										echo 'Регион: ' . $default_region['name'];
									} else {
										echo 'Регион: все регионы';
									}
									?></th>
							</tr>
							<tr>
								<th class="header">Позиция</th>
								<th class="header">Тип</th>
								<th class="header">Парт-<br>номер</th>
								<th class="header">Исп-<br>ольз.</th>
								<th class="header">Описание(eng)</th>
								<th class="header">Описание(рус)</th>
								<th class="header">Кол-во</th>
								<th class="header">Цена, грн</th>
							</tr>
						</thead>

						<tbody>
						<?php foreach ($type_parts as $c) {
							$c['price'] = $this->currency_model->convert('eur', 'hrn', $c['price']);
							?>
						<tr>
							<td class="cct_ref"><?php echo $c['cct_ref'] ?></td>
							<td class="ptype"><?php echo $c['ptype'] ?></td>
							<td class="code"><?php echo $c['code'] ?></td>
							<td class="num"><?php echo $c['num'] ?></td>
							<td><?php echo $c['name'] ?></td>
							<td><?php echo $c['name_rus'] ?></td>
							<td class="num_inp"><input type="text" value="0" class="num w45" title="<?php echo $c['min_num'] > 1 ? 'минимальное количество для заказа: ' . $c['min_num'] : ''; ?>" data-price="<?php echo $c['price'] ?>"></td>
							<td class="price"><?php echo $c['price'] > 0 ? $c['price'] : 'нет данных' ?></td>
						</tr>
						<?php } ?>
						</tbody>

						<tfoot>
							<tr><td colspan="6"></td><td class="label">Всего:</td><td class="value"><span id="total">0</span> грн</td></tr>
						</tfoot>

					</table>
				</div>

			<? } else { ?>

				Нет <?php echo $type == 'solder' ? 'паечных' : 'корпусных' ?> запчастей

			<? }
			} ?>

<?php } else { ?>
	Деталей не найдено
<?php } ?>
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/0/all">Показать все регионы</a>'; ?>
</div>