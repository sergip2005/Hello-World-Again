<table class="tablesorter separate clearfix">
	<thead>		
		<tr>			
			<th style="background-image:none;" class="header">Тип</th>
			<th style="background-image:none;" class="header">Парт-<br>номер</th>			
			<th style="background-image:none;" class="header">Описание(eng)</th>
			<th style="background-image:none;" class="header">Описание(рус)</th>
			<th style="background-image:none;" class="header">Кол-во</th>
			<th style="background-image:none;" class="header">Цена, грн</th>
			
		</tr>
	</thead>
	<tbody>
		<?php foreach ($basket as $c):?>
		<tr>			
			<td class="ptype"><?php echo $c['ptype'] ?></td>
			<td class="code"><?php echo $c['code'] ?></td>			
			<td><?php echo $c['name'] ?></td>
			<td><?php echo $c['name_rus'] ?></td>
			<td class="num_inp"><input type="text" value="0" class="num w45" title="<?php echo $c['min_num'] > 1 ? 'минимальное количество для заказа: ' . $c['min_num'] : ''; ?>" data-price="<?php echo $c['price'] ?>"></td>
			<td class="price"><?php echo $c['price'] > 0 ? $c['price'] : 'нет данных' ?></td>			
		</tr>
		<?php endforeach;?>
	<tbody>
</table>

<input onclick="location='/basket/exel'" type="button" value="Экспорт в EXCEL"/>
<?php if ($user_id) $href = '/basket/order'; else $href='/login'?>
<input type="button" onclick="location.href='<?php echo $href?>'" value="Заказать"/>