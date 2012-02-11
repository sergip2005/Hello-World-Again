<div class="parts-content full-transparent">
<?php $parts = $parts['parts'] ?>

	<div class="model-title clearfix">
		<img src="/assets/images/phones/<?php echo $model['image'] ?>" alt="<?php echo $vendor['name'] ?> - <?php echo $model['model'] ?>">
		<h1><?php echo $vendor['name'] ?> - <?php echo $model['model'] ?></h1>
		<div id="parts">
			<span class="s selected">Механические </span>
			<span class="c">Электрические </span>
		</div>
	</div>

	<?php
		$types = array('solder' => array(), 'cabinet' => array());
		if (is_array($parts) && count($parts) > 0) {
			foreach ($parts as $row) {
				 if($row['type'] == 's') {
					$types['solder'][] = $row;
				 } else {
					$types['cabinet'][] = $row;
				 }
			}
		}

		foreach ($types as $type => $type_parts) {
			$name1 = $type == 'solder' ? 'электрические' : 'механические';
	?>
				<div class="<?php echo $type ?>">
					<?php if (!empty($model[$type . '_image'])) { ?>
						<a class="zoom" href="/assets/images/phones/<?php echo $model[$type . '_image'] ?>" title="<?php echo $name1 ?> элементы <?php echo $model['model'] ?>">
							<img class="zoom_src" src="/assets/images/phones/<?php echo $model[$type . '_image'] ?>" alt="<?php echo $name1 ?> элементы <?php echo $model['model'] ?>">
						</a>
					<?php } ?>

					<table class="tablesorter separate clearfix">
						<thead>
							<tr>
								<th class="{sorter: false}">v.<?php echo !empty($model['rev_num']) ? $model['rev_num'] : ' ---' ?></th>
								<th class="{sorter: false}" colspan="8"><?php
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
								<th class="header">Цена, грн</th>
								<th class="header">Кол-во</th>
								<th class="">Корзина</th>
							</tr>
						</thead>

						<tbody>
						<?php if (count($type_parts) > 0) { ?>
							<?php
								foreach ($type_parts as $c) {
									$c['price'] = $this->currency_model->convert('eur', 'hrn', $c['price']);
							?>
							<tr>
								<td class="cct_ref"><?php echo $c['cct_ref'] ?></td>
								<td class="ptype"><?php echo $c['ptype'] ?></td>
								<td class="code"><a href="/parts/<?php echo url_title($c['code'], '_', TRUE) ?>/<?php echo url_title($c['name'], '_', TRUE) ?>/"><?php echo $c['code'] ?></a></td>
								<td class="num"><?php echo $c['num'] ?></td>
								<td><?php echo $c['name'] ?></td>
								<td><?php echo $c['name_rus'] ?></td>
								<td class="price"><?php echo $c['price'] > 0 ? $c['price'] : 'нет данных' ?></td>
								<td class="num_inp">
									<input type="text" value="0" class="num w45 amount tooltip" title="Минимальное количество для заказа этого товара <?php echo $c['min_num']?>" data-price="<?php echo $c['price'] ?>">
								</td>
								<td>
								<?php if ($c['price'] > 0):?>
									<a href="#" onclick="addToBasket(<?php echo $c['part_id'] ?>, this)">добавить</a>
								<?php endif;?>
								</td>
								
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr><td colspan="9">Нет <?php echo $type == 'solder' ? 'электрических' : 'механических' ?> запчастей</td></tr>
						<?php } ?>
						</tbody>

						<tfoot>
							<tr><td colspan="6"></td><td class="label">Всего:</td><td class="value"><span id="total">0</span> грн</td></tr>
						</tfoot>

					</table>
				</div>

			<?php } ?>

	<?php echo $region == 'all' ? '<a id="showparts" href="/phones/' . url_title($vendor['name'], '_', TRUE) . '/' . url_title($model['model'], '_', TRUE) . '/">Показать основной регион</a>' : '<a id="showparts" href="/phones/' . url_title($vendor['name'], '_', TRUE) . '/' . url_title($model['model'], '_', TRUE) . '/0/all">Показать все регионы</a>'; ?>
</div>