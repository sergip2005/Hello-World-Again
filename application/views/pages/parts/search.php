<?php
	$pages_html = generate_pagination_html($search_params['pagination'], '/parts/' . $search_params['parameter'] . '/' . $search_params['query'] . '/%page%/');
?>
<div class="parts-content">
<?php if (count($parts) > 0) { ?>

	<div class="pages top"><?php echo $pages_html ?></div>

	<table class="tablesorter separate" >
		<thead>
			<tr>
				<th class="header">Произв-ль </th>
				<th class="header">Модель</th>
				<th class="header">Позиция</th>
				<th class="header">Тип</th>
				<th class="header">Парт-<br>номер</th>
				<th class="header">Исп-<br>ольз.</th>
				<th class="header">Описание(eng)</th>
				<th class="header">Описание(рус)</th>
				<th class="header">Цена, грн</th>
				<th class="header">Кол-во</th>
				<th class="">Корзина</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($parts as $p) {
				$p['price'] = $this->currency_model->convert('eur', 'hrn', $p['price']);
				?>
			<tr>
				<td><a href="/phones/<?php echo url_title($p['vendor_name'], '_', TRUE) ?>/"><?php echo $p['vendor_name'] ?></a></td>
				<td><a href="/phones/<?php echo url_title($p['vendor_name'], '_', TRUE) ?>/<?php echo url_title($p['model_name'], '_', TRUE) ?>/"><?php echo $p['model_name'] ?></a></td>
				<td><?php echo $p['cct_ref'] ?></td>
				<td><?php echo $p['ptype'] ?></td>
				<td><a href="/parts/<?php echo url_title($p['code'], '_', TRUE) ?>/<?php echo url_title($p['name'], '_', TRUE) ?>/"><?php echo $p['code'] ?></a></td>
				<td><?php echo $p['num'] ?></td>
				<td><?php echo $p['name'] ?></td>
				<td><?php echo $p['name_rus'] ?></td>
				<td><?php echo $p['price'] ?></td>
				<td class="num_inp">
					<input type="text" value="0" class="num w45 amount tooltip" title="Минимальное количество для заказа этого товара <?php echo $p['min_num']?>" data-price="<?php echo $p['price'] ?>"></td>
				<td>
				<?php if ($p['price'] > 0):?>
				<a href="javascript://" onclick="addToBasket(<?php echo $p['part_id'] ?>,this)">добавить</a>
				<?php endif;?>
				</td>

			</tr>
			<?php } ?>
		</tbody>

		<tfoot>
			<tr><td colspan="8"></td><td class="label">Всего:</td><td class="value"><span id="total">0</span> грн</td></tr>
		</tfoot>
	</table>

	<div class="pages bottom"><?php echo $pages_html ?></div>

<?php } else { ?>
	Не найдено запчастей
<?php } ?>
</div>