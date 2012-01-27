Поиск по корзине

<div id="search">

	<div class="select-and-input">		
			<div class="search_field search_code">
			<form  action="" method="GET">
				<input type="text" value="<?php echo $parts_code?>" name="parts_code" class="text"/>
				<div class="button"></div>
				<input type="submit" value="Sub" class="submit"/>
				<input type="hidden" value="parts_code" class="parameter"/>
			</form>
			</div>		
	</div>	
	
	<div class="select-and-input">		
			<div class="search_field search_code">
			<form  action="" method="GET">
				<input type="text" value="<?php echo $name?>" name="name" class="text"/>
				<div class="button"></div>
				<input type="submit" value="Sub" class="submit"/>
				<input type="hidden" value="parts_code" class="parameter"/>
			</form>
			</div>		
	</div>
	
	<div class="select-and-input">		
			<div class="search_field search_code">
			<form action="" method="GET">
				<input type="text" value="<?php echo $name_rus?>" name="name_rus" class="text"/>
				<div class="button"></div>
				<input type="submit" value="Sub" class="submit"/>
				<input type="hidden" value="parts_code" class="parameter"/>				
			</form>
			</div>
	</div>

</div>

<div style="clear: both;"/>


<form action="/basket/order" method="POST">

<input onclick="location='/basket/exel'" type="button" value="Экспорт в EXCEL"/>
<?php if ($user_id):?>
<input type="submit"  value="Заказать"/>
<?php else :?>
<input type="button" onclick="location.href='/auth/register/basket'" value="Заказать"/>
<?php endif;?>
<input onclick="document.location=''" type="button" value="Пересчитать"/>
<table class="tablesorter separate clearfix">
	<thead>		
		<tr>			
			<th style="background-image:none;" class="header">Тип</th>
			<th style="background-image:none;" class="header">Парт-<br>номер</th>			
			<th style="background-image:none;" class="header">Описание(eng)</th>
			<th style="background-image:none;" class="header">Описание(рус)</th>
			<th style="background-image:none;" class="header">Кол-во</th>			
			<th style="background-image:none;" class="header">Цена сум</th>
			<th style="background-image:none;" class="header">Цена за единицу товара, грн</th>
			<th style="background-image:none;" class="header"></th>
			
		</tr>
	</thead>
	<tbody>
		<?php $totalAmount =0;$totalPrice = 0;$totalPriceGRN=0;?>
		<?php foreach ($basket as $key=>$c):?>
		<tr>			
			<td class="ptype"><?php echo $c['ptype'] ?></td>
			<td class="code"><?php echo $c['code'] ?></td>			
			<td><?php echo $c['name'] ?></td>
			<td><?php echo $c['name_rus'] ?></td>
			<td class="num_inp">
			<input onblur="sendAmount(<?php echo $c['basket_id']?>,this)" onkeyup="changeAmount(this)" name="basket[<?php echo $key?>][amount]" type="text" value="<?php echo $c['amount']?>"/>
			<input type="hidden" name="basket[<?php echo $key?>][part_id]" value="<?php echo $c['id']?>"/>
			
			</td>			
			<td class="totalPrice">
			<?php $total = $c['amount']*$c['price']?>
			<?php $totalGRN = $c['amount']*$c['price_grn']?>
			<?php echo $c['price'] > 0 ? number_format($totalGRN,2) : 'нет данных'?>
			</td>
			<td class="price"><?php echo $c['price_grn'] > 0 ? $c['price_grn'] : 'нет данных' ?></td>			
			<td>
			<a href="javascript://" onclick="removeFromBasket(<?php echo $c['basket_id']?>,this)">Удалить</a>
			</td>
		</tr>
		<?php $totalAmount = $totalAmount + $c['amount']?>
		<?php $totalPrice = $totalPrice + $total?>
		<?php $totalPriceGRN = $totalPriceGRN + $totalGRN?>
		<?php endforeach;?>
		<tr>
		<td colspan="4" align="right">Итого:</td>
		<td><?php echo $totalAmount?>
		<input type="hidden" name="totalAmount" value="<?php echo $totalAmount?>"/>
		</td>
		<td ><?php echo number_format($totalPriceGRN,2)?>
		<input type="hidden" name="totalPrice" value="<?php echo $totalPrice?>"/>
		</td>
		<td colspan="2"></td>
		</tr>
	<tbody>
</table>

</form>
