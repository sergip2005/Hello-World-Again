<?php
	$this->load->model('basket_model');
	$basket = $this->basket_model->getBasket();
	$count = count($basket);
?>
<div id="basket">
	<?php if ($count > 0):?>
	<a href="/basket/">Товаров в корзине <span><?php echo $count ?></span></a>
	<?php else:?>
		Корзина пуста
	<?php endif;?>
</div>