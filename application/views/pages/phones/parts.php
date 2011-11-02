<div class="models-menu">
	<ul>
	<?php foreach ($catalog as  $key => $models) { ?>
		<li><?php echo $key ?>
		<?php if (count($models) > 0) { ?>
			<ul>
			<?php foreach ($models as $k => $m) { ?>
				<li><a href="/phones/<?php echo strtolower($key) ?>/<?php echo strtolower(str_replace(' ', '_', $m)) ?>"><?php echo $m ?></a></li>
			<?php } ?>
			</ul>
		<?php } ?>
		</li>
	<?php } ?>
	</ul>
</div>

<div class="parts-content full-transparent">
	<div id="parts">
		<span class="s selected">Корпусные </span>
		<span class="c">Паечные </span>
	</div>

	<div class="cabinet" >

	<?php
	if (count($parts) > 0) {
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
			<a href="/assets/images/testimg/E75_RM-412_RM-413_Schematics_v0_1.png" id="cabinet_img" title="E75_RM-412_RM-413_Schematics_v0_1">
				<img src="/assets/images/testimg/small_E75_RM-412_RM-413_Schematics_v0_1.png" style="border: solid 1px #999;" title="E75_RM-412_RM-413_Schematics_v0_1">
			</a>

			<table class="sofT" cellspacing="0">
			<tr>
				<td colspan="8" class="helpHed">Корпусные элементы</td>
			</tr>
			<tr>
				<td class="helpHed" data-field="phones_parts.cct_ref">Позиция</td>
				<td class="helpHed" data-field="parts.code">Код</td>
				<td class="helpHed" data-field="phones_parts.num">Испол.</td>
				<td class="helpHed" data-field="parts.name">Описание(eng)</td>
				<td class="helpHed" data-field="parts.name_rus">Описание(рус)</td>
				<td class="helpHed" data-field="order_num">Кол-во</td>
				<td class="helpHed" data-field="phones_parts.min_num">Мин. кол-во</td>
				<td class="helpHed" data-field="parts.price">Цена</td>
			</tr>

			<?php foreach ($cabinet as $c) { ?>
			<tr>
				<td><?php echo $c['cct_ref'] ?></td>
				<td><?php echo $c['code'] ?></td>
				<td><?php echo $c['num'] ?></td>
				<td><?php echo $c['name'] ?></td>
				<td><?php echo $c['name_rus'] ?></td>
				<td></td>
				<td><?php echo $c['min_num'] ?></td>
				<td><?php echo $c['price'] ?></td>
			</tr>
			<?php } ?>

			</table>
		<?php } else { ?>
			Нет паечных запчастей
		<?php } ?>
	</div>

	<div class="solder">
		<?php if (count($solder) > 0) { ?>

			<a href="/assets/images/testimg/E75_RM-412_RM-413_Schematics_v0_1.png" id="solder_img" title="E75_RM-412_RM-413_Schematics_v0_1">
				<img src="/assets/images/testimg/small_E75_RM-412_RM-413_Schematics_v0_1.png" style="border: solid 1px #999;" title="E75_RM-412_RM-413_Schematics_v0_1">
			</a>

			<table class="sofT" cellspacing="0">
			<tr>
				<td colspan="8" class="helpHed">Паечные элементы</td>
			</tr>
			<tr>
				<td class="helpHed" data-field="phones_parts.cct_ref">Позиция</td>
				<td class="helpHed" data-field="parts.code">Код</td>
				<td class="helpHed" data-field="phones_parts.num">Испол.</td>
				<td class="helpHed" data-field="parts.name">Описание(eng)</td>
				<td class="helpHed" data-field="parts.name_rus">Описание(рус)</td>
				<td class="helpHed" data-field="order_num">Кол-во</td>
				<td class="helpHed" data-field="phones_parts.min_num">Мин. кол-во</td>
				<td class="helpHed" data-field="parts.price">Цена</td>
			</tr>

			<?php foreach ($solder as $s) { ?>
			<tr>
				<td><?php echo $s['cct_ref'] ?></td>
				<td><?php echo $s['code'] ?></td>
				<td><?php echo $s['num'] ?></td>
				<td><?php echo $s['name'] ?></td>
				<td><?php echo $s['name_rus'] ?></td>
				<td></td>
				<td><?php echo $s['min_num'] ?></td>
				<td><?php echo $s['price'] ?></td>
			</tr>
			<?php } ?>

			</table>
		<?php } else { ?>
			Нет корпусных запчастей
		<?php } ?>
	</div>
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/all">Показать все регионы</a>'; ?>
</div>

<?php } else { ?>
	Нет запчастей
	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . $vendor . '/' . $model . '/all">Показать все регионы</a>'; ?>
<?php } ?>