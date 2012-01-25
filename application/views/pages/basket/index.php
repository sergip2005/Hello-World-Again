<form action="/basket/order" method="POST">
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
		<?php foreach ($basket as $key=>$c):?>
		<tr>			
			<td class="ptype"><?php echo $c['ptype'] ?></td>
			<td class="code"><?php echo $c['code'] ?></td>			
			<td><?php echo $c['name'] ?></td>
			<td><?php echo $c['name_rus'] ?></td>
			<td class="num_inp">
			<input name="basket[<?php echo $key?>][min_num]" type="text" value="<?php echo $c['min_num']?>" class="num w45 inputmin" title="<?php echo $c['min_num'] > 1 ? 'минимальное количество для заказа: ' . $c['min_num'] : ''; ?>" data-price="<?php echo $c['price'] ?>">
			<input type="hidden" name="basket[<?php echo $key?>][part_id]" value="<?php echo $c['id']?>"/>
			<img onclick="num = parseInt($(this).parent().find('.inputmin').val()) + <?php echo $c['min_num']?>;$(this).parent().find('.inputmin').val(num);" src="/assets/images/icons/up.png"/>
			<img onclick="if (parseInt($(this).parent().find('.inputmin').val()) > <?php echo $c['min_num']?>) {num = parseInt($(this).parent().find('.inputmin').val()) - <?php echo $c['min_num']?>;$(this).parent().find('.inputmin').val(num);}" src="/assets/images/icons/down.png"/>
			</td>
			<td class="price"><?php echo $c['price'] > 0 ? $c['price'] : 'нет данных' ?></td>			
		</tr>
		<?php endforeach;?>
	<tbody>
</table>

<input onclick="location='/basket/exel'" type="button" value="Экспорт в EXCEL"/>
<?php if ($user_id):?>
<input type="submit"  value="Заказать"/>
<?php else :?>
<input type="button" onclick="location.href="/auth/register/basket"' value="Заказать"/>
<?php endif;?>
</form>