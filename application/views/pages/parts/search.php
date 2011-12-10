<div class="parts-content">
<?php if (count($parts) > 0) { ?>
	<table class="tablesorter separate" >
		<thead>
			<tr>
				<th class="header" data-field="vendor"> Произв-ль</th>
				<th class="header" data-field="model">Модель</th>
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
			<?php foreach ($parts as $p) {
				$p['price'] = $this->currency_model->convert('eur', 'hrn', $p['price']);
				?>
			<tr>
				<td><a href="/phones/<?php echo strtolower($p['vendor_name']) ?>"><?php echo $p['vendor_name'] ?></a></td>
				<td><a href="/phones/<?php echo strtolower($p['vendor_name']) ?>/<?php echo strtolower(str_replace(' ', '_', $p['model_name'])) ?>"><?php echo $p['model_name'] ?></a></td>
				<td><?php echo $p['cct_ref'] ?></td>
				<td><?php echo $p['ptype'] ?></td>
				<td><?php echo $p['code'] ?></td>
				<td><?php echo $p['num'] ?></td>
				<td><?php echo $p['name'] ?></td>
				<td><?php echo $p['name_rus'] ?></td>
				<td class="num_inp"><input type="text" value="0" class="num w45" title="<?php echo $p['min_num'] > 1 ? 'минимальное количество для заказа: ' . $p['min_num'] : ''; ?>" data-price="<?php echo $p['price'] ?>"></td>
				<td><?php echo $p['price'] ?></td>
			</tr>
			<?php } ?>
		</tbody>

		<tfoot>
			<tr><td colspan="8"></td><td class="label">Всего:</td><td class="value"><span id="total">0</span> грн</td></tr>
		</tfoot>
	</table>
	<?php } else { ?>
		Не найдено запчастей
	<?php } ?>
</div>