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
			<tr><th class="not_header">v.<?php echo $cabinet[0]['rev_num'] ?></th><th class="not_header" colspan="8"></th></tr>
			<tr>
				<th class="header" data-field="phones_parts.cct_ref">Позиция</th>
				<th class="header" data-field="parts.ptype">Тип</th>
				<th class="header" data-field="parts.code">Код</th>
				<th class="header" data-field="phones_parts.num">Испол.</th>
				<th class="header" data-field="parts.name">Описание(eng)</th>
				<th class="header" data-field="parts.name_rus">Описание(рус)</th>
				<th class="header" data-field="order_num">Кол-во</th>
				<th class="header" data-field="phones_parts.min_num">Мин. кол-во</th>
				<th class="header" data-field="parts.price">Цена</th>
			</tr>
			</thead>
			<tbody>

			<?php foreach ($cabinet as $c) { ?>
			<tr>
				<td><?php echo $c['cct_ref'] ?></td>
				<td><?php echo $c['ptype'] ?></td>
				<td><?php echo $c['code'] ?></td>
				<td><?php echo $c['num'] ?></td>
				<td><?php echo $c['name'] ?></td>
				<td><?php echo $c['name_rus'] ?></td>
				<td><input type="text" value="0" class="w45"></td>
				<td><?php echo $c['min_num'] ?></td>
				<td><?php echo $c['price'] > 0 ? $c['price'] : 'нет данных' ?></td>
			</tr>
			<?php } ?>
			</tbody>
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
			<tr><th class="not_header">v.<?php echo $solder[0]['rev_num'] ?></th><th class="not_header" colspan="8"></th></tr>
			<tr>
				<th class="header" data-field="phones_parts.cct_ref">Позиция</th>
				<th class="header" data-field="parts.ptype">Тип</th>
				<th class="header" data-field="parts.code">Парт-номер</th>
				<th class="header" data-field="phones_parts.num">Испол.</th>
				<th class="header" data-field="parts.name">Описание(eng)</th>
				<th class="header" data-field="parts.name_rus">Описание(рус)</th>
				<th class="header" data-field="order_num">Кол-во</th>
				<th class="header" data-field="phones_parts.min_num">Мин. кол-во</th>
				<th class="header" data-field="parts.price">Цена</th>
			</tr>
			</thead>
			<tbody>

			<?php foreach ($solder as $s) { ?>
			<tr>
				<td><?php echo $s['cct_ref'] ?></td>
				<td><?php echo $s['ptype'] ?></td>
				<td><?php echo $s['code'] ?></td>
				<td><?php echo $s['num'] ?></td>
				<td><?php echo $s['name'] ?></td>
				<td><?php echo $s['name_rus'] ?></td>
				<td><input type="text" value="0" class="w45"></td>
				<td><?php echo $s['min_num'] ?></td>
				<td><?php echo $s['price'] > 0 ? $c['price'] : 'нет данных' ?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
			Нет паечных запчастей
		<?php } ?>
	</div>
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/all">Показать все регионы</a>'; ?>
</div>

<?php } else { ?>
	Нет запчастей
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/all">Показать все регионы</a>'; ?>
<?php } ?>