<div class="models-menu">
	<ul>
	<?php foreach ($catalog as  $key => $models) { ?>
		<li><a href="/phones/<?php echo $key ?>"><?php echo $key ?></a>
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
<div class="parts-content">
<?php
if (count($parts) > 0) { ?>

			<table class="sofT" cellspacing="0">
			<tr>
				<td class="helpHed r-border">v</td><td colspan="9" class="helpHed r-border"></td>
			</tr>
			<tr>
				<td colspan="10" class="helpHed">Поиск</td>
			</tr>
			<tr>
				<td class="helpHed" data-field="phones_parts.cct_ref">Позиция</td>
				<td class="helpHed" data-field="parts.code">Код</td>
				<td class="helpHed" data-field="phones_parts.num">Испол.</td>
				<td class="helpHed" data-field="parts.name">Описание(eng)</td>
				<td class="helpHed" data-field="parts.name_rus">Описание(рус)</td>
				<td class="helpHed" data-field="ptype">Тип</td>
				<td class="helpHed" data-field="phones_parts.min_num">Мин. кол-во</td>
				<td class="helpHed" data-field="parts.price">Цена</td>
				<td class="helpHed" data-field="vendor">Вендор</td>
				<td class="helpHed" data-field="model">Модель</td>
			</tr>

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
				<td><a href="/phones/<?php echo strtolower($p['vendor']) ?>"><?php echo $p['vendor'] ?></a></td>
				<td><a href="/phones/<?php echo strtolower($p['vendor']) ?>/<?php echo strtolower(str_replace(' ', '_', $p['model'])) ?>"><?php echo $p['model'] ?></a></td>
			</tr>
			<?php } ?>

			</table>
		<?php } else { ?>
			Не найдено запчастей
		<?php } ?>
</div>