<div class='mainInfo'>

	<h1>Создание нового пользователя</h1>

	<p>Введите информацию в поля ниже:</p>

	
	<?php echo form_open("register"); ?>
	<input type="hidden" name="basket" value="<?php echo $actionBasket ?>"/>
	<table>

		<tr>
			<td class="label">Email:</td>
			<td class="value"><?php echo form_input($email);?></td>
		</tr>

		<tr>
			<td class="label">Пароль (от <?php echo $this->config->item('min_password_length', 'ion_auth') ?> до <?php echo $this->config->item('max_password_length', 'ion_auth') ?> символов):</td>
			<td class="value"><?php echo form_input($password);?></td>
		</tr>

		<tr>
			<td class="label">Подтверждение пароля:</td>
			<td class="value"><?php echo form_input($password_confirm);?></td>
		</tr>

		<tr>
			<td colspan="2"><?php echo form_submit('submit', 'Зарегистрироваться');?></td>
		</tr>

	</table>
	<?php echo form_close();?>

</div>