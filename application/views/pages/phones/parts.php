<?php if (count($parts) > 0) { ?>
<div class="parts-content full-transparent">
	<div id="parts">
		<span class="s selected">Корпусные </span>
		<span class="c">Паечные </span>
	</div>

	<div class="cabinet" >

<?php
		$cabinet = array();
		$solder = array();
		foreach ($parts as $row) {
			 if($row['type'] == 's') {
				 $solder[] = $row;
			 } else {
				 $cabinet[] = $row;
			 }
		}

		if (count($cabinet) > 0) { ?>
			<a class="zoom" href="/assets/images/phones/<?php echo $cabinet[0]['cabinet_image'] ?>" title="корпусные элементы <?php echo $model ?>">
				<img class="zoom_src" src="/assets/images/phones/<?php echo $cabinet[0]['cabinet_image'] ?>" alt="корпусные элементы <?php echo $model ?>">
			</a>

			<table class="tablesorter separate clearfix">
			<thead>
				<tr>
					<th class="{sorter: false}">v.<?php echo $cabinet[0]['rev_num'] ?></th>
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
			<?php foreach ($cabinet as $c) {
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
		<?php } else { ?>
			Нет корпусных запчастей
		<?php } ?>
	</div>

	<div class="solder">
		<?php if (count($solder) > 0) { ?>

		<a class="zoom" href="/assets/images/phones/<?php echo $cabinet[0]['solder_image'] ?>" title="паечные элементы <?php echo $model ?>">
			<img class="zoom_src" src="/assets/images/phones/<?php echo $cabinet[0]['solder_image'] ?>" alt="паечные элементы <?php echo $model ?>">
		</a>

		<table class="tablesorter separate clearfix">
			<thead>
				<tr>
					<th class="{sorter: false}">v.<?php echo $solder[0]['rev_num'] ?></th>
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

			<?php foreach ($solder as $s) {
				$s['price'] = $this->currency_model->convert('eur', 'hrn', $s['price']);
				?>
			<tr>
				<td class="cct_ref"><?php echo $s['cct_ref'] ?></td>
				<td class="ptype"><?php echo $s['ptype'] ?></td>
				<td class="code"><?php echo $s['code'] ?></td>
				<td class="num"><?php echo $s['num'] ?></td>
				<td><?php echo $s['name'] ?></td>
				<td><?php echo $s['name_rus'] ?></td>
				<td class="num_inp"><input type="text" value="0" class="num w45" title="<?php echo $s['min_num'] > 1 ? 'минимальное количество для заказа: ' . $s['min_num'] : ''; ?>" data-price="<?php echo $s['price'] ?>"></td>
				<td class="price"><?php echo $s['price'] > 0 ? $s['price'] : 'нет данных' ?></td>
			</tr>
			<?php } ?>
			</tbody>

			<tfoot>
			<tr><td colspan="6"></td><td class="label">Всего:</td><td class="value"><span id="total">0</span> грн</td></tr>
			</tfoot>

		</table>
		<?php } else { ?>
			Нет паечных запчастей
		<?php } ?>
	</div>
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/all">Показать все регионы</a>'; ?>
</div>

<?php } else { ?>
	Деталей не найдено
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/all">Показать все регионы</a>'; ?>
<?php } ?>