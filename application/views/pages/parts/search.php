<div class="parts-content">
<?php if (count($parts) > 0) { ?>
	<table class="tablesorter separate" >
		<thead>
		<tr>
			<th class="header" data-field="phones_parts.cct_ref">Позиция</th>
			<th class="header" data-field="parts.code">Код</th>
			<th class="header" data-field="phones_parts.num">Испол.</th>
			<th class="header" data-field="parts.name">Описание(eng)</th>
			<th class="header" data-field="parts.name_rus">Описание(рус)</th>
			<th class="header" data-field="ptype">Тип</th>
			<th class="header" data-field="phones_parts.min_num">Мин. кол-во</th>
			<th class="header" data-field="parts.price">Цена</th>
			<th class="header" data-field="vendor">Вендор</th>
			<th class="header" data-field="model">Модель</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($parts as $p) { ?>
		<tr>
			<td><?php echo $p['cct_ref'] ?></td>
			<td><?php echo $p['code'] ?></td>
			<td><?php echo $p['num'] ?></td>
			<td><?php echo $p['name'] ?></td>
			<td><?php echo $p['name_rus'] ?></td>
			<td><?php echo $p['ptype'] ?></td>
			<td><?php echo $p['min_num'] ?></td>
			<td><?php echo $p['price'] ?></td>
			<td><a href="/phones/<?php echo strtolower($p['vendor_name']) ?>"><?php echo $p['vendor_name'] ?></a></td>
			<td><a href="/phones/<?php echo strtolower($p['vendor_name']) ?>/<?php echo strtolower(str_replace(' ', '_', $p['model_name'])) ?>"><?php echo $p['model_name'] ?></a></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } else { ?>
		Не найдено запчастей
	<?php } ?>
</div>