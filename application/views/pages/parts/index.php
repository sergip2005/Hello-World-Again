<?php

	$pages_html = generate_pagination_html($search_params['pagination'], '/parts/' . $search_params['query'] . '/%page%/');
?>
<div class="parts-content">
<?php if (count($parts) > 0) { ?>
	<div class="pages top"><?php echo $pages_html ?></div>

		<table class="tablesorter separate" >
			<thead>
			<tr>
				<th class="header">Позиция</th>
				<th class="header">Код</th>
				<th class="header">Испол.</th>
				<th class="header">Описание(eng)</th>
				<th class="header">Описание(рус)</th>
				<th class="header">Тип</th>
				<th class="header">Мин. кол-во</th>
				<th class="header">Цена</th>
				<th class="header">Вендор</th>
				<th class="header">Модель</th>
				<th class="">Корзина</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($parts as $p) { ?>
			<tr>
				<td><?php echo $p['cct_ref'] ?></td>
				<td><a href="/parts/<?php echo url_title($p['code'], '_', TRUE) ?>/<?php echo url_title($p['name'], '_', TRUE) ?>/"><?php echo $p['code'] ?></a></td>
				<td><?php echo $p['num'] ?></td>
				<td><?php echo $p['name'] ?></td>
				<td><?php echo $p['name_rus'] ?></td>
				<td><?php echo $p['ptype'] ?></td>
				<td><?php echo $p['min_num'] ?></td>
				<td><?php echo $p['price'] ?></td>
				<td><a href="/phones/<?php echo url_title($p['vendor_name'], '_', TRUE) ?>/"><?php echo $p['vendor_name'] ?></a></td>
				<td><a href="/phones/<?php echo url_title($p['vendor_name'], '_', TRUE) ?>/<?php echo url_title($p['model_name'], '_', TRUE) ?>/"><?php echo $p['model_name'] ?></a></td>
				<td>
				<?php if ($p['price'] > 0):?>
				<a href="javascript://" onclick="addToBasket(<?php echo $p['id'] ?>)">добавить</a>
				<?php endif;?>
				</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>

		<div class="pages bottom"><?php echo $pages_html ?></div>
		<?php } else { ?>
			Не найдено запчастей
		<?php } ?>
</div>